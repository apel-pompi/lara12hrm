<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;

use App\Models\Accounts\Voucherheader;
use App\Http\Requests\Voucherheader\StoreVoucherheaderRequest;
use App\Models\Accounts\ChartOfAccount;
use App\Models\Accounts\VoucherApalc;
use App\Models\Accounts\Voucherdetail;
use App\Models\Default\Transaction;
use App\Models\HRM\Branch;
use App\Models\HRM\CompanyInfo;
use App\Models\Accounts\VoucherBalance;
use App\Models\Student\StudentInvoiceHD;
use App\Services\Accounts\jurnalVoucherService;
use App\Services\Accounts\paymentVoucherService;
use App\Services\Accounts\receiptVoucherService;
use App\Services\Accounts\reverseVoucherService;
use App\Services\Accounts\openingVoucherService;
use App\Services\Accounts\allVoucherService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VoucherheaderController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function jurnalVoucher(Request $request, jurnalVoucherService $jurnal_voucher)
    {
        try {
            $this->authorize('voucher.jurnal');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $perPage = $request->query('per_page', 10);

        return Inertia::render('allpages/accounts/voucher/jurnalVoucher', [
            'filters'   => $jurnal_voucher->get($request->query()),
            'voucherheader' => $jurnal_voucher->get(array_merge($request->query(), ['per_page' => $perPage])),
            'branch' => Branch::all(),
            'allvoucher' => Voucherheader::whereRaw("LEFT(vouchernumber, 4) = 'JV--'")->get(),
            'accountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSET', 'REVENUES', 'LIABILITIES', 'EXPENDITURE'])->where('accountusage', 'Ledger')->where('analyticalcode', 'Non-Cash')->get(),

            'draccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSET', 'EXPENDITURE'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

            'craccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['REVENUES', 'LIABILITIES'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),
        ]);
    }

    private function GetJurnalNO()
    {
        $transaction = Transaction::where('name', 'Journal Voucher')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function jurnalStore(StoreVoucherheaderRequest $request)
    {
        try {
            $this->authorize('voucher.jurnalCreate');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $jurnalNo = $this->GetJurnalNO();
        $voucherDate = Carbon::parse($request->voucherdate);
        $details = $request->input('details', []);
        DB::transaction(function () use ($request, $jurnalNo, $voucherDate, $details) {
            Voucherheader::create([
                'vouchernumber' => $jurnalNo,
                'voucherdate'   => $request->voucherdate,
                'referance'     => $request->referance,
                'yearname'      => $voucherDate->year,
                'monthname'     => $voucherDate->month,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);

            $totalDebit = 0;
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
                $totalDebit += $amt;
                Voucherdetail::create([
                    'vouchernumber' => $jurnalNo,
                    'accountcode'   => $detail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $amt,
                    'baseamt'       => $amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $detail['particular'],
                    'user_id'       => Auth::id(),
                ]);
            }

            Voucherdetail::create([
                'vouchernumber' => $jurnalNo,
                'accountcode'   => $request->creditAcc,
                'currency'      => 'BDT',
                'exchagerate'   => '1.000',
                'primeamt'      => -$totalDebit,
                'baseamt'       => -$totalDebit,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);
        });
        $numericPart = (int) preg_replace('/\D/', '', $jurnalNo);
        Transaction::where('name', 'Journal Voucher')
            ->where('active', 1)
            ->update(['lastnumber' => $numericPart]);

        return back()->with([
            'success' => true,
            'message' => 'Journal voucher created successfully'
        ]);
    }

    public function jurnalEdit(Voucherheader $jurnal)
    {

        return Voucherheader::with(['voucherdt.ChartOFAccount', 'branch'])->findOrFail($jurnal->id);
    }

    public function jurnalUpdate(Request $request, $jurnal)
    {

        try {
            $this->authorize('voucher.jurnalUpdate');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $request->validate([
            'voucherdate' => 'required|date',
            'referance'   => 'required|string',
            'branch_id'   => 'required|exists:branches,id',
            'creditAcc'   => 'required|string',
            'notes'       => 'required|string',
            'details'     => 'required|array|min:1',
            'details.*.accountcode' => 'required|string',
            'details.*.particular' => 'nullable|string',
            'details.*.amount'     => 'required|numeric|min:0',
        ]);

        $details = $request->input('details', []);

        DB::transaction(function () use ($request, $jurnal, $details) {
            $voucher = Voucherheader::findOrFail($jurnal);
            $voucherDate = Carbon::parse($request->voucherdate);
            $voucher->update([
                'voucherdate' => $request->voucherdate,
                'referance'   => $request->referance,
                'yearname'    => $voucherDate->year,
                'monthname'   => $voucherDate->month,
                'branch_id'   => $request->branch_id,
                'notes'       => $request->notes,
            ]);

            // Delete all existing debit details
            $rows = Voucherdetail::where('vouchernumber', $voucher->vouchernumber)
                ->where('primeamt', '>', 0)
                ->get();

            foreach ($rows as $row) {
                $row->forceDelete();
            }

            // Re-create debit details
            $totalDebit = 0;
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
                $totalDebit += $amt;
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $detail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $amt,
                    'baseamt'       => $amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $detail['particular'],
                    'user_id'       => Auth::id(),
                ]);
            }

            // Update or create single credit entry
            $creditDetail = Voucherdetail::where('vouchernumber', $voucher->vouchernumber)
                ->where('primeamt', '<', 0)
                ->first();

            if ($creditDetail) {
                $creditDetail->update([
                    'accountcode' => $request->creditAcc,
                    'primeamt'    => -$totalDebit,
                    'baseamt'     => -$totalDebit,
                    'branch_id'   => $request->branch_id,
                    'notes'       => $request->notes,
                    'user_id'     => Auth::id(),
                ]);
            } else {
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $request->creditAcc,
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -$totalDebit,
                    'baseamt'       => -$totalDebit,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                ]);
            }
        });

        return back()->with([
            'success' => true,
            'message' => 'Journal voucher updated successfully',
        ]);
    }

    public function jurnalConfirm(Voucherheader $jurnal)
    {

        try {
            $this->authorize('voucher.jurnalConfirm');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {

            DB::beginTransaction();

            DB::statement(
                'CALL sp_am_voucherpost(?, ?)',
                [$jurnal->vouchernumber, Auth::id()]
            );

            DB::commit();
            return back()->with([
                'success' => true,
                'message' => 'Journal voucher posted successfully'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with([
                'error' => true,
                'message' => 'Journal voucher posting failed. Please contact administrator.',
            ]);
        }
    }

    public function singleReport(Voucherheader $voucherID)
    {
        try {
            $this->authorize('voucher.singleReport');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $company = CompanyInfo::firstOrFail();

        $voucher = Voucherheader::with(['voucherdt.ChartOFAccount', 'branch'])->where('id', $voucherID->id)->first();

        $rows = [];
        $totalDebit  = 0;
        $totalCredit = 0;

        foreach ($voucher->voucherdt as $item) {
            $amount = (float) $item->primeamt;

            $debit  = $amount > 0 ? $amount : null;
            $credit = $amount < 0 ? abs($amount) : null;

            $totalDebit  += $debit  ?? 0;
            $totalCredit += $credit ?? 0;

            $rows[] = [
                'accountcode' => $item->accountcode,
                'description' => $item->ChartOFAccount->description ?? '',
                'notes' => $amount > 0
                    ? ($item->notes ?? '')
                    : ($item->ChartOFAccount->description ?? ''),
                'debit'       => $debit,
                'credit'      => $credit,
            ];
        }

        $pdf = PDF::loadView('exports.accounts.gl_single_voucher', [
            'company' => $company,
            'voucher'     => $voucher,
            'rows'        => $rows,
            'totalDebit'  => $totalDebit,
            'totalCredit' => $totalCredit,
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("gl_single_voucher.pdf");
    }

    public function paymentVoucher(Request $request, paymentVoucherService $payment_voucher)
    {
        try {
            $this->authorize('voucher.payment');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $perPage = $request->query('per_page', 10);
        return Inertia::render('allpages/accounts/voucher/paymentVoucher', [
            'filters'   => $payment_voucher->get($request->query()),
            'voucherheader' => $payment_voucher->get(array_merge($request->query(), ['per_page' => $perPage])),
            'branch' => Branch::all(),
            'allvoucher' => Voucherheader::whereRaw("LEFT(vouchernumber, 4) = 'PAY-'")->get(),
            'accountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSET', 'EXPENDITURE'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

            'draccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSET', 'LIABILITIES', 'EXPENDITURE'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

            'craccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSET'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

        ]);
    }

    private function GetpaymentNO()
    {
        $transaction = Transaction::where('name', 'Payment Voucher')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function paymentStore(StoreVoucherheaderRequest $request)
    {
        try {
            $this->authorize('voucher.paymentCreate');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $paymentNo = $this->GetpaymentNO();
        $voucherDate = Carbon::parse($request->voucherdate);
        $details = $request->input('details', []);

        DB::transaction(function () use ($request, $paymentNo, $voucherDate, $details) {
            Voucherheader::create([
                'vouchernumber' => $paymentNo,
                'voucherdate'   => $request->voucherdate,
                'referance'     => $request->referance,
                'yearname'      => $voucherDate->year,
                'monthname'     => $voucherDate->month,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);

            // Each particular creates a separate debit voucher detail
            $totalDebit = 0;
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
                $totalDebit += $amt;
                Voucherdetail::create([
                    'vouchernumber' => $paymentNo,
                    'accountcode'   => $detail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $amt,
                    'baseamt'       => $amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $detail['particular'],
                    'user_id'       => Auth::id(),
                ]);
            }

            // Credit entry is created once with total amount (negative)
            Voucherdetail::create([
                'vouchernumber' => $paymentNo,
                'accountcode'   => $request->creditAcc,
                'currency'      => 'BDT',
                'exchagerate'   => '1.000',
                'primeamt'      => -$totalDebit,
                'baseamt'       => -$totalDebit,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);
        });

        $numericPart = (int) preg_replace('/\D/', '', $paymentNo);
        Transaction::where('name', 'Payment Voucher')
            ->where('active', 1)
            ->update(['lastnumber' => $numericPart]);

        return back()->with([
            'success' => true,
            'message' => 'Payment voucher created successfully'
        ]);
    }

    public function paymentEdit(Voucherheader $payment)
    {
        return Voucherheader::with(['voucherdt.ChartOFAccount', 'branch'])->findOrFail($payment->id);
    }

    public function paymentUpdate(Request $request, $payment)
    {
        try {
            $this->authorize('voucher.paymentUpdate');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $request->validate([
            'voucherdate' => 'required|date',
            'referance'   => 'required|string',
            'branch_id'   => 'required|exists:branches,id',
            'creditAcc'   => 'required|string',
            'notes'       => 'required|string',
            'details'     => 'required|array|min:1',
            'details.*.accountcode' => 'required|string',
            'details.*.particular' => 'nullable|string',
            'details.*.amount'     => 'required|numeric|min:0',
        ]);

        $details = $request->input('details', []);

        DB::transaction(function () use ($request, $payment, $details) {
            $voucher = Voucherheader::findOrFail($payment);
            $voucherDate = Carbon::parse($request->voucherdate);
            $voucher->update([
                'voucherdate' => $request->voucherdate,
                'referance'   => $request->referance,
                'yearname'    => $voucherDate->year,
                'monthname'   => $voucherDate->month,
                'branch_id'   => $request->branch_id,
                'notes'       => $request->notes,
            ]);

            // Delete all existing debit details (primeamt > 0)
            $rows = Voucherdetail::where('vouchernumber', $voucher->vouchernumber)
                ->where('primeamt', '>', 0)
                ->get();

            foreach ($rows as $row) {
                $row->forceDelete();
            }

            // Re-create debit details per particular
            $totalDebit = 0;
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
                $totalDebit += $amt;
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $detail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $amt,
                    'baseamt'       => $amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $detail['particular'],
                    'user_id'       => Auth::id(),
                ]);
            }

            // Update or create the single credit entry
            $creditDetail = Voucherdetail::where('vouchernumber', $voucher->vouchernumber)
                ->where('primeamt', '<', 0)
                ->first();

            if ($creditDetail) {
                $creditDetail->update([
                    'accountcode' => $request->creditAcc,
                    'primeamt'    => -$totalDebit,
                    'baseamt'     => -$totalDebit,
                    'branch_id'   => $request->branch_id,
                    'notes'       => $request->notes,
                    'user_id'     => Auth::id(),
                ]);
            } else {
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $request->creditAcc,
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -$totalDebit,
                    'baseamt'       => -$totalDebit,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                ]);
            }
        });

        return back()->with([
            'success' => true,
            'message' => 'Payment voucher updated successfully',
        ]);
    }

    public function paymentConfirm(Voucherheader $payment)
    {
        try {
            $this->authorize('voucher.paymentConfirm');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {

            DB::beginTransaction();

            DB::statement(
                'CALL sp_am_voucherpost(?, ?)',
                [$payment->vouchernumber, Auth::id()]
            );

            DB::commit();
            return back()->with([
                'success' => true,
                'message' => 'Payment voucher posted successfully'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with([
                'error' => true,
                'message' => 'Payment voucher posting failed. Please contact administrator.',
            ]);
        }
    }

    private function GetreceiptNO()
    {
        $transaction = Transaction::where('name', 'Receipt Voucher')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function receiptVoucher(Request $request, receiptVoucherService $receipt_voucher)
    {
        try {
            $this->authorize('voucher.receipt');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->query('per_page', 10);
        return Inertia::render('allpages/accounts/voucher/receiptVoucher', [
            'filters'   => $receipt_voucher->get($request->query()),
            'voucherheader' => $receipt_voucher->get(array_merge($request->query(), ['per_page' => $perPage])),
            'branch' => Branch::all(),
            'allvoucher' => Voucherheader::whereRaw("LEFT(vouchernumber, 4) = 'RCV-'")->get(),

            'draccountcode' => ChartOfAccount::where('active', '1')->where('accounttype', 'ASSET')->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

            'craccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSET', 'REVENUES', 'LIABILITIES'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

        ]);
    }

    public function receiptStore(StoreVoucherheaderRequest $request)
    {
        try {
            $this->authorize('voucher.receiptCreate');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $receiptNo = $this->GetreceiptNO();
        $voucherDate = Carbon::parse($request->voucherdate);
        $details = $request->input('details', []);
        DB::transaction(function () use ($request, $receiptNo, $voucherDate, $details) {
            Voucherheader::create([
                'vouchernumber' => $receiptNo,
                'voucherdate'   => $request->voucherdate,
                'referance'     => $request->referance,
                'yearname'      => $voucherDate->year,
                'monthname'     => $voucherDate->month,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);

            $totalDebit = 0;
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
                $totalDebit += $amt;
                Voucherdetail::create([
                    'vouchernumber' => $receiptNo,
                    'accountcode'   => $detail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $amt,
                    'baseamt'       => $amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $detail['particular'],
                    'user_id'       => Auth::id(),
                ]);
            }

            Voucherdetail::create([
                'vouchernumber' => $receiptNo,
                'accountcode'   => $request->creditAcc,
                'currency'      => 'BDT',
                'exchagerate'   => '1.000',
                'primeamt'      => -$totalDebit,
                'baseamt'       => -$totalDebit,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);
        });
        $numericPart = (int) preg_replace('/\D/', '', $receiptNo);
        Transaction::where('name', 'Receipt Voucher')
            ->where('active', 1)
            ->update(['lastnumber' => $numericPart]);

        return back()->with([
            'success' => true,
            'message' => 'Receipt voucher created successfully'
        ]);
    }

    public function receiptEdit(Voucherheader $receipt)
    {
        return Voucherheader::with(['voucherdt.ChartOFAccount', 'branch'])->findOrFail($receipt->id);
    }

    public function receiptUpdate(Request $request, $receipt)
    {
        try {
            $this->authorize('voucher.receiptUpdate');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $request->validate([
            'voucherdate' => 'required|date',
            'referance'   => 'required|string',
            'branch_id'   => 'required|exists:branches,id',
            'creditAcc'   => 'required|string',
            'notes'       => 'required|string',
            'details'     => 'required|array|min:1',
            'details.*.accountcode' => 'required|string',
            'details.*.particular' => 'nullable|string',
            'details.*.amount'     => 'required|numeric|min:0',
        ]);

        $details = $request->input('details', []);

        DB::transaction(function () use ($request, $receipt, $details) {
            $voucher = Voucherheader::findOrFail($receipt);
            $voucherDate = Carbon::parse($request->voucherdate);
            $voucher->update([
                'voucherdate' => $request->voucherdate,
                'referance'   => $request->referance,
                'yearname'    => $voucherDate->year,
                'monthname'   => $voucherDate->month,
                'branch_id'   => $request->branch_id,
                'notes'       => $request->notes,
            ]);

            // Delete all existing debit details
            $rows = Voucherdetail::where('vouchernumber', $voucher->vouchernumber)
                ->where('primeamt', '>', 0)
                ->get();

            foreach ($rows as $row) {
                $row->forceDelete();
            }

            // Re-create debit details
            $totalDebit = 0;
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
                $totalDebit += $amt;
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $detail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $amt,
                    'baseamt'       => $amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $detail['particular'],
                    'user_id'       => Auth::id(),
                ]);
            }

            // Update or create single credit entry
            $creditDetail = Voucherdetail::where('vouchernumber', $voucher->vouchernumber)
                ->where('primeamt', '<', 0)
                ->first();

            if ($creditDetail) {
                $creditDetail->update([
                    'accountcode' => $request->creditAcc,
                    'primeamt'    => -$totalDebit,
                    'baseamt'     => -$totalDebit,
                    'branch_id'   => $request->branch_id,
                    'notes'       => $request->notes,
                    'user_id'     => Auth::id(),
                ]);
            } else {
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $request->creditAcc,
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -$totalDebit,
                    'baseamt'       => -$totalDebit,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                ]);
            }
        });

        return back()->with([
            'success' => true,
            'message' => 'Receipt voucher updated successfully',
        ]);
    }

    public function receiptConfirm(Voucherheader $receipt)
    {
        try {
            $this->authorize('voucher.receiptConfirm');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {

            DB::beginTransaction();

            DB::statement(
                'CALL sp_am_voucherpost(?, ?)',
                [$receipt->vouchernumber, Auth::id()]
            );

            DB::commit();
            return back()->with([
                'success' => true,
                'message' => 'Receipt voucher posted successfully'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with([
                'error' => true,
                'message' => 'Receipt voucher posting failed. Please contact administrator.',
            ]);
        }
    }

    private function GetreverseNO()
    {
        $transaction = Transaction::where('name', 'Reverse Voucher')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function reverseVoucher(Request $request, reverseVoucherService $reverse_voucher)
    {
        try {
            $this->authorize('voucher.reverse');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->query('per_page', 10);
        return Inertia::render('allpages/accounts/voucher/reverseVoucher', [
            'filters'   => $reverse_voucher->get($request->query()),
            'voucherheader' => $reverse_voucher->get(array_merge($request->query(), ['per_page' => $perPage])),
            'branch' => Branch::all(),
            'allvoucher' => Voucherheader::whereRaw("LEFT(vouchernumber, 4) = 'REV-'")->get(),

            'draccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSET', 'EXPENDITURE', 'REVENUES', 'LIABILITIES'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Cash', 'Non-Cash'])->get(),
            'craccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSET', 'EXPENDITURE', 'REVENUES', 'LIABILITIES'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Cash', 'Non-Cash'])->get(),


        ]);
    }

    public function reverseStore(StoreVoucherheaderRequest $request)
    {
        try {
            $this->authorize('voucher.reverseCreate');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $reverseNo = $this->GetreverseNO();
        $voucherDate = Carbon::parse($request->voucherdate);
        $details = $request->input('details', []);
        DB::transaction(function () use ($request, $reverseNo, $voucherDate, $details) {
            Voucherheader::create([
                'vouchernumber' => $reverseNo,
                'voucherdate'   => $request->voucherdate,
                'referance'     => $request->referance,
                'yearname'      => $voucherDate->year,
                'monthname'     => $voucherDate->month,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);

            $totalDebit = 0;
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
                $totalDebit += $amt;
                Voucherdetail::create([
                    'vouchernumber' => $reverseNo,
                    'accountcode'   => $detail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $amt,
                    'baseamt'       => $amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $detail['particular'],
                    'user_id'       => Auth::id(),
                ]);
            }

            Voucherdetail::create([
                'vouchernumber' => $reverseNo,
                'accountcode'   => $request->creditAcc,
                'currency'      => 'BDT',
                'exchagerate'   => '1.000',
                'primeamt'      => -$totalDebit,
                'baseamt'       => -$totalDebit,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);
        });
        $numericPart = (int) preg_replace('/\D/', '', $reverseNo);
        Transaction::where('name', 'Reverse Voucher')
            ->where('active', 1)
            ->update(['lastnumber' => $numericPart]);

        return back()->with([
            'success' => true,
            'message' => 'Reverse voucher created successfully'
        ]);
    }

    public function reverseEdit(Voucherheader $reverse)
    {

        return Voucherheader::with(['voucherdt.ChartOFAccount', 'branch'])->findOrFail($reverse->id);
    }

    public function reverseUpdate(Request $request, $reverse)
    {
        try {
            $this->authorize('voucher.reverseUpdate');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $request->validate([
            'voucherdate' => 'required|date',
            'referance'   => 'required|string',
            'branch_id'   => 'required|exists:branches,id',
            'creditAcc'   => 'required|string',
            'notes'       => 'required|string',
            'details'     => 'required|array|min:1',
            'details.*.accountcode' => 'required|string',
            'details.*.particular' => 'nullable|string',
            'details.*.amount'     => 'required|numeric|min:0',
        ]);

        $details = $request->input('details', []);

        DB::transaction(function () use ($request, $reverse, $details) {
            $voucher = Voucherheader::findOrFail($reverse);
            $voucherDate = Carbon::parse($request->voucherdate);
            $voucher->update([
                'voucherdate' => $request->voucherdate,
                'referance'   => $request->referance,
                'yearname'    => $voucherDate->year,
                'monthname'   => $voucherDate->month,
                'branch_id'   => $request->branch_id,
                'notes'       => $request->notes,
            ]);

            // Delete all existing debit details
            $rows = Voucherdetail::where('vouchernumber', $voucher->vouchernumber)
                ->where('primeamt', '>', 0)
                ->get();

            foreach ($rows as $row) {
                $row->forceDelete();
            }

            // Re-create debit details
            $totalDebit = 0;
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
                $totalDebit += $amt;
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $detail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $amt,
                    'baseamt'       => $amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $detail['particular'],
                    'user_id'       => Auth::id(),
                ]);
            }

            // Update or create single credit entry
            $creditDetail = Voucherdetail::where('vouchernumber', $voucher->vouchernumber)
                ->where('primeamt', '<', 0)
                ->first();

            if ($creditDetail) {
                $creditDetail->update([
                    'accountcode' => $request->creditAcc,
                    'primeamt'    => -$totalDebit,
                    'baseamt'     => -$totalDebit,
                    'branch_id'   => $request->branch_id,
                    'notes'       => $request->notes,
                    'user_id'     => Auth::id(),
                ]);
            } else {
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $request->creditAcc,
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -$totalDebit,
                    'baseamt'       => -$totalDebit,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                ]);
            }
        });

        return back()->with([
            'success' => true,
            'message' => 'Reverse voucher updated successfully',
        ]);
    }

    public function reverseConfirm(Voucherheader $reverse)
    {
        try {
            $this->authorize('voucher.reverseConfirm');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {

            DB::beginTransaction();

            DB::statement(
                'CALL sp_am_voucherpost(?, ?)',
                [$reverse->vouchernumber, Auth::id()]
            );

            DB::commit();
            return back()->with([
                'success' => true,
                'message' => 'Reverse voucher posted successfully'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with([
                'error' => true,
                'message' => 'Reverse voucher posting failed. Please contact administrator.',
            ]);
        }
    }

    private function GetopeningNO()
    {
        $transaction = Transaction::where('name', 'Opening Blance')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function openingVoucher(Request $request, openingVoucherService $opening_voucher)
    {
        try {
            $this->authorize('voucher.opening');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->query('per_page', 10);
        return Inertia::render('allpages/accounts/voucher/openingVoucher', [
            'filters'   => $opening_voucher->get($request->query()),
            'voucherheader' => $opening_voucher->get(array_merge($request->query(), ['per_page' => $perPage])),
            'branch' => Branch::all(),
            'allvoucher' => Voucherheader::whereRaw("LEFT(vouchernumber, 4) = 'OB--'")->get(),
            'accountcode' => ChartOfAccount::where('active', '1')->where('accountusage', '<>', 'Ledger')->get(),

            'draccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSET'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

            'craccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['LIABILITIES'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

        ]);
    }

    public function openingStore(StoreVoucherheaderRequest $request)
    {
        try {
            $this->authorize('voucher.openingCreate');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $openingNo = $this->GetopeningNO();
        $voucherDate = Carbon::parse($request->voucherdate);
        $details = $request->input('details', []);
        DB::transaction(function () use ($request, $openingNo, $voucherDate, $details) {
            Voucherheader::create([
                'vouchernumber' => $openingNo,
                'voucherdate'   => $request->voucherdate,
                'referance'     => $request->referance,
                'yearname'      => $voucherDate->year,
                'monthname'     => $voucherDate->month,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);

            // Each particular creates a separate debit voucher detail
            $totalDebit = 0;
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
                $totalDebit += $amt;
                Voucherdetail::create([
                    'vouchernumber' => $openingNo,
                    'accountcode'   => $detail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $amt,
                    'baseamt'       => $amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $detail['particular'],
                    'user_id'       => Auth::id(),
                ]);
            }

            // Credit entry is created once with total amount (negative)
            Voucherdetail::create([
                'vouchernumber' => $openingNo,
                'accountcode'   => $request->creditAcc,
                'currency'      => 'BDT',
                'exchagerate'   => '1.000',
                'primeamt'      => -$totalDebit,
                'baseamt'       => -$totalDebit,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);
        });


        $numericPart = (int) preg_replace('/\D/', '', $openingNo);
        Transaction::where('name', 'Opening Blance')
            ->where('active', 1)
            ->update(['lastnumber' => $numericPart]);

        return back()->with([
            'success' => true,
            'message' => 'Opening Blance created successfully'
        ]);
    }

    public function openingEdit(Voucherheader $opening)
    {

        return Voucherheader::with(['voucherdt.ChartOFAccount', 'branch'])->findOrFail($opening->id);
    }

    public function openingUpdate(Request $request, $opening)
    {

        try {
            $this->authorize('voucher.openingUpdate');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $request->validate([
            'voucherdate' => 'required|date',
            'referance'   => 'required|string',
            'branch_id'   => 'required|exists:branches,id',
            'creditAcc'   => 'required|string',
            'notes'       => 'required|string',
            'details'     => 'required|array|min:1',
            'details.*.accountcode' => 'required|string',
            'details.*.particular' => 'nullable|string',
            'details.*.amount'     => 'required|numeric|min:0',
        ]);


        $details = $request->input('details', []);

        DB::transaction(function () use ($request, $opening, $details) {
            $voucher = Voucherheader::findOrFail($opening);
            $voucherDate = Carbon::parse($request->voucherdate);
            $voucher->update([
                'voucherdate' => $request->voucherdate,
                'referance'   => $request->referance,
                'yearname'    => $voucherDate->year,
                'monthname'   => $voucherDate->month,
                'branch_id'   => $request->branch_id,
                'notes'       => $request->notes,
            ]);

            // Delete all existing debit details (primeamt > 0)
            $rows = Voucherdetail::where('vouchernumber', $voucher->vouchernumber)
                ->where('primeamt', '>', 0)
                ->get();

            foreach ($rows as $row) {
                $row->forceDelete();
            }

            // Re-create debit details per particular
            $totalDebit = 0;
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
                $totalDebit += $amt;
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $detail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $amt,
                    'baseamt'       => $amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $detail['particular'],
                    'user_id'       => Auth::id(),
                ]);
            }

            // Update or create the single credit entry
            $creditDetail = Voucherdetail::where('vouchernumber', $voucher->vouchernumber)
                ->where('primeamt', '<', 0)
                ->first();

            if ($creditDetail) {
                $creditDetail->update([
                    'accountcode' => $request->creditAcc,
                    'primeamt'    => -$totalDebit,
                    'baseamt'     => -$totalDebit,
                    'branch_id'   => $request->branch_id,
                    'notes'       => $request->notes,
                    'user_id'     => Auth::id(),
                ]);
            } else {
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $request->creditAcc,
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -$totalDebit,
                    'baseamt'       => -$totalDebit,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                ]);
            }
        });



        return back()->with([
            'success' => true,
            'message' => 'Opening Blance updated successfully',
        ]);
    }

    public function openingConfirm(Voucherheader $opening)
    {
        try {
            $this->authorize('voucher.openingConfirm');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {

            DB::beginTransaction();

            DB::statement(
                'CALL sp_am_voucherpost(?, ?)',
                [$opening->vouchernumber, Auth::id()]
            );

            DB::commit();
            return back()->with([
                'success' => true,
                'message' => 'Opening Blance posted successfully'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with([
                'error' => true,
                'message' => 'Opening Blance posting failed. Please contact administrator.',
            ]);
        }
    }

    public function allVoucher(Request $request, allVoucherService $all_voucher)
    {
        try {
            $this->authorize('voucher.allvoucher');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->query('per_page', 10);
        return Inertia::render('allpages/accounts/voucher/allVoucher', [
            'filters'   => $all_voucher->get($request->query()),
            'voucherheader' => $all_voucher->get(array_merge($request->query(), ['per_page' => $perPage])),
            'branch' => Branch::all(),
            'allvoucher' => Voucherheader::all(),
            'accountcode' => ChartOfAccount::where('active', '1')->get(),
        ]);
    }


    public function allvoucherConfirm(Voucherheader $allvoucher)
    {
        try {
            $this->authorize('voucher.allvoucherConfirm');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {

            DB::beginTransaction();

            DB::statement(
                'CALL sp_am_voucherpost(?, ?)',
                [$allvoucher->vouchernumber, Auth::id()]
            );

            DB::commit();
            return back()->with([
                'success' => true,
                'message' => 'Voucher Blance posted successfully'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with([
                'error' => true,
                'message' => 'Voucher Blance posting failed. Please contact administrator.',
            ]);
        }
    }

    public function allvoucherBalance(Voucherheader $allvoucher)
    {
        $this->authorize('voucher.allvoucherBalance');

        DB::transaction(function () use ($allvoucher) {
            if (substr($allvoucher->vouchernumber, 0, 4) == 'APV-') {
                VoucherApalc::where('vouchernumber', $allvoucher->vouchernumber)
                    ->forceDelete();
                VoucherBalance::where('vouchernumber', $allvoucher->vouchernumber)
                    ->forceDelete();
                Voucherdetail::where('vouchernumber', $allvoucher->vouchernumber)
                    ->forceDelete();
                $allvoucher->update([
                    'status' => null
                ]);
            } elseif (substr($allvoucher->vouchernumber, 0, 4) == 'MR--') {
                VoucherBalance::where('vouchernumber', $allvoucher->vouchernumber)
                    ->forceDelete();
                Voucherheader::where('vouchernumber', $allvoucher->vouchernumber)
                    ->forceDelete();
                Voucherdetail::where('vouchernumber', $allvoucher->vouchernumber)
                    ->forceDelete();
                $invoice = StudentInvoiceHD::where('insnumber', $allvoucher->vouchernumber)->first();
                $invoice->update(['status' => 'Open']);
            } else {
                VoucherBalance::where('vouchernumber', $allvoucher->vouchernumber)
                    ->forceDelete();
                $allvoucher->update([
                    'status' => 'Balanced'
                ]);
            }
        });

        return back()->with([
            'success' => true,
            'message' => 'Voucher successfully reverted to Balanced.'
        ]);
    }
    public function getAccountBalance($accountcode)
    {
        $balance = VoucherBalance::where('accountcode', $accountcode)
            ->sum('primeamt');

        return response()->json(['balance' => $balance]);
    }
}
