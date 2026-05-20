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
            'accountcode' => ChartOfAccount::where('active', '1')->get(),

            'draccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSETS', 'EXPENDITURES'])->where('accountusage', 'Ledger')->get(),

            'craccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['REVENUES', 'LIABILITIES'])->where('accountusage', 'Ledger')->get(),
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

        $voucherDate = Carbon::parse($request->voucherdate);
        $details = $request->input('details', []);
        $creditDetails = $request->input('creditDetails', []);

        $totalDebit = collect($details)->sum('amount');
        $totalCredit = collect($creditDetails)->sum('amount');
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with([
                'error' => true,
                'message' => 'Total Debit and Total Credit must be equal.'
            ]);
        }

        try {
            DB::transaction(function () use ($request, $voucherDate, $details, $creditDetails) {
                // Lock the sequence row for update to prevent concurrent duplicate number generation
                $transaction = Transaction::where('name', 'Journal Voucher')
                    ->where('active', 1)
                    ->lockForUpdate()
                    ->first();

                if (!$transaction) {
                    throw new \Exception('Transaction sequence settings not found for Journal Voucher.');
                }

                $nextCode = $transaction->lastnumber + 1;
                $jurnalNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

                // Update lastnumber immediately
                $transaction->update(['lastnumber' => $nextCode]);

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

                foreach ($details as $detail) {
                    $amt = abs($detail['amount']);
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

                foreach ($creditDetails as $crDetail) {
                    $amt = abs($crDetail['amount']);
                    Voucherdetail::create([
                        'vouchernumber' => $jurnalNo,
                        'accountcode'   => $crDetail['accountcode'],
                        'currency'      => 'BDT',
                        'exchagerate'   => '1.000',
                        'primeamt'      => -$amt,
                        'baseamt'       => -$amt,
                        'branch_id'     => $request->branch_id,
                        'notes'         => $crDetail['particular'] ?: $request->notes,
                        'user_id'       => Auth::id(),
                    ]);
                }
            });

            return back()->with([
                'success' => true,
                'message' => 'Journal voucher created successfully'
            ]);
        } catch (\Throwable $e) {
            return back()->with([
                'error' => true,
                'message' => 'Failed to save Journal Voucher: ' . $e->getMessage()
            ]);
        }
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
            'notes'       => 'required|string',
            'details'     => 'required|array|min:1',
            'details.*.accountcode' => 'required|string',
            'details.*.particular' => 'nullable|string',
            'details.*.amount'     => 'required|numeric|min:0',
            'creditDetails' => 'required|array|min:1',
            'creditDetails.*.accountcode' => 'required|string',
            'creditDetails.*.particular' => 'nullable|string',
            'creditDetails.*.amount'     => 'required|numeric|min:0',
        ]);

        $details = $request->input('details', []);
        $creditDetails = $request->input('creditDetails', []);

        $totalDebit = collect($details)->sum('amount');
        $totalCredit = collect($creditDetails)->sum('amount');
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with([
                'error' => true,
                'message' => 'Total Debit and Total Credit must be equal.'
            ]);
        }

        DB::transaction(function () use ($request, $jurnal, $details, $creditDetails) {
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

            // Delete all existing details (both debit and credit rows)
            Voucherdetail::where('vouchernumber', $voucher->vouchernumber)->forceDelete();

            // Re-create debit details
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
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

            // Re-create credit details
            foreach ($creditDetails as $crDetail) {
                $amt = abs($crDetail['amount']);
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $crDetail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -$amt,
                    'baseamt'       => -$amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $crDetail['particular'] ?: $request->notes,
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
            'accountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSETS', 'EXPENDITURES'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

            'draccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSETS', 'LIABILITIES', 'EXPENDITURES'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

            'craccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSETS'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

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

        $voucherDate = Carbon::parse($request->voucherdate);
        $details = $request->input('details', []);
        $creditDetails = $request->input('creditDetails', []);

        $totalDebit = collect($details)->sum('amount');
        $totalCredit = collect($creditDetails)->sum('amount');
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with([
                'error' => true,
                'message' => 'Total Debit and Total Credit must be equal.'
            ]);
        }

        try {
            DB::transaction(function () use ($request, $voucherDate, $details, $creditDetails) {
                // Lock the sequence row for update to prevent concurrent duplicate number generation
                $transaction = Transaction::where('name', 'Payment Voucher')
                    ->where('active', 1)
                    ->lockForUpdate()
                    ->first();

                if (!$transaction) {
                    throw new \Exception('Transaction sequence settings not found for Payment Voucher.');
                }

                $nextCode = $transaction->lastnumber + 1;
                $paymentNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

                // Update lastnumber immediately
                $transaction->update(['lastnumber' => $nextCode]);

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

                foreach ($details as $detail) {
                    $amt = abs($detail['amount']);
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

                foreach ($creditDetails as $crDetail) {
                    $amt = abs($crDetail['amount']);
                    Voucherdetail::create([
                        'vouchernumber' => $paymentNo,
                        'accountcode'   => $crDetail['accountcode'],
                        'currency'      => 'BDT',
                        'exchagerate'   => '1.000',
                        'primeamt'      => -$amt,
                        'baseamt'       => -$amt,
                        'branch_id'     => $request->branch_id,
                        'notes'         => $crDetail['particular'] ?: $request->notes,
                        'user_id'       => Auth::id(),
                    ]);
                }
            });

            return back()->with([
                'success' => true,
                'message' => 'Payment voucher created successfully'
            ]);
        } catch (\Throwable $e) {
            return back()->with([
                'error' => true,
                'message' => 'Failed to save Payment Voucher: ' . $e->getMessage()
            ]);
        }
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
            'notes'       => 'required|string',
            'details'     => 'required|array|min:1',
            'details.*.accountcode' => 'required|string',
            'details.*.particular' => 'nullable|string',
            'details.*.amount'     => 'required|numeric|min:0',
            'creditDetails' => 'required|array|min:1',
            'creditDetails.*.accountcode' => 'required|string',
            'creditDetails.*.particular' => 'nullable|string',
            'creditDetails.*.amount'     => 'required|numeric|min:0',
        ]);

        $details = $request->input('details', []);
        $creditDetails = $request->input('creditDetails', []);

        $totalDebit = collect($details)->sum('amount');
        $totalCredit = collect($creditDetails)->sum('amount');
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with([
                'error' => true,
                'message' => 'Total Debit and Total Credit must be equal.'
            ]);
        }

        DB::transaction(function () use ($request, $payment, $details, $creditDetails) {
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

            // Delete all existing details
            Voucherdetail::where('vouchernumber', $voucher->vouchernumber)->forceDelete();

            // Re-create debit details per particular
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
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

            // Re-create credit details per particular
            foreach ($creditDetails as $crDetail) {
                $amt = abs($crDetail['amount']);
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $crDetail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -$amt,
                    'baseamt'       => -$amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $crDetail['particular'] ?: $request->notes,
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

            'draccountcode' => ChartOfAccount::where('active', '1')->where('accounttype', 'ASSETS')->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

            'craccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSETS', 'REVENUES', 'LIABILITIES'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

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

        $voucherDate = Carbon::parse($request->voucherdate);
        $details = $request->input('details', []);
        $creditDetails = $request->input('creditDetails', []);

        $totalDebit = collect($details)->sum('amount');
        $totalCredit = collect($creditDetails)->sum('amount');
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with([
                'error' => true,
                'message' => 'Total Debit and Total Credit must be equal.'
            ]);
        }

        try {
            DB::transaction(function () use ($request, $voucherDate, $details, $creditDetails) {
                // Lock the sequence row for update to prevent concurrent duplicate number generation
                $transaction = Transaction::where('name', 'Receipt Voucher')
                    ->where('active', 1)
                    ->lockForUpdate()
                    ->first();

                if (!$transaction) {
                    throw new \Exception('Transaction sequence settings not found for Receipt Voucher.');
                }

                $nextCode = $transaction->lastnumber + 1;
                $receiptNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

                // Update lastnumber immediately
                $transaction->update(['lastnumber' => $nextCode]);

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

                foreach ($details as $detail) {
                    $amt = abs($detail['amount']);
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

                foreach ($creditDetails as $crDetail) {
                    $amt = abs($crDetail['amount']);
                    Voucherdetail::create([
                        'vouchernumber' => $receiptNo,
                        'accountcode'   => $crDetail['accountcode'],
                        'currency'      => 'BDT',
                        'exchagerate'   => '1.000',
                        'primeamt'      => -$amt,
                        'baseamt'       => -$amt,
                        'branch_id'     => $request->branch_id,
                        'notes'         => $crDetail['particular'] ?: $request->notes,
                        'user_id'       => Auth::id(),
                    ]);
                }
            });

            return back()->with([
                'success' => true,
                'message' => 'Receipt voucher created successfully'
            ]);
        } catch (\Throwable $e) {
            return back()->with([
                'error' => true,
                'message' => 'Failed to save Receipt Voucher: ' . $e->getMessage()
            ]);
        }
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
            'notes'       => 'required|string',
            'details'     => 'required|array|min:1',
            'details.*.accountcode' => 'required|string',
            'details.*.particular' => 'nullable|string',
            'details.*.amount'     => 'required|numeric|min:0',
            'creditDetails' => 'required|array|min:1',
            'creditDetails.*.accountcode' => 'required|string',
            'creditDetails.*.particular' => 'nullable|string',
            'creditDetails.*.amount'     => 'required|numeric|min:0',
        ]);

        $details = $request->input('details', []);
        $creditDetails = $request->input('creditDetails', []);

        $totalDebit = collect($details)->sum('amount');
        $totalCredit = collect($creditDetails)->sum('amount');
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with([
                'error' => true,
                'message' => 'Total Debit and Total Credit must be equal.'
            ]);
        }

        DB::transaction(function () use ($request, $receipt, $details, $creditDetails) {
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

            // Delete all existing details
            Voucherdetail::where('vouchernumber', $voucher->vouchernumber)->forceDelete();

            // Re-create debit details
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
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

            // Re-create credit details
            foreach ($creditDetails as $crDetail) {
                $amt = abs($crDetail['amount']);
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $crDetail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -$amt,
                    'baseamt'       => -$amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $crDetail['particular'] ?: $request->notes,
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

            'draccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSETS', 'EXPENDITURES', 'REVENUES', 'LIABILITIES'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Cash', 'Non-Cash'])->get(),
            'craccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSETS', 'EXPENDITURES', 'REVENUES', 'LIABILITIES'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Cash', 'Non-Cash'])->get(),


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

        $voucherDate = Carbon::parse($request->voucherdate);
        $details = $request->input('details', []);
        $creditDetails = $request->input('creditDetails', []);

        $totalDebit = collect($details)->sum('amount');
        $totalCredit = collect($creditDetails)->sum('amount');
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with([
                'error' => true,
                'message' => 'Total Debit and Total Credit must be equal.'
            ]);
        }

        try {
            DB::transaction(function () use ($request, $voucherDate, $details, $creditDetails) {
                // Lock the sequence row for update to prevent concurrent duplicate number generation
                $transaction = Transaction::where('name', 'Reverse Voucher')
                    ->where('active', 1)
                    ->lockForUpdate()
                    ->first();

                if (!$transaction) {
                    throw new \Exception('Transaction sequence settings not found for Reverse Voucher.');
                }

                $nextCode = $transaction->lastnumber + 1;
                $reverseNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

                // Update lastnumber immediately
                $transaction->update(['lastnumber' => $nextCode]);

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

                foreach ($details as $detail) {
                    $amt = abs($detail['amount']);
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

                foreach ($creditDetails as $crDetail) {
                    $amt = abs($crDetail['amount']);
                    Voucherdetail::create([
                        'vouchernumber' => $reverseNo,
                        'accountcode'   => $crDetail['accountcode'],
                        'currency'      => 'BDT',
                        'exchagerate'   => '1.000',
                        'primeamt'      => -$amt,
                        'baseamt'       => -$amt,
                        'branch_id'     => $request->branch_id,
                        'notes'         => $crDetail['particular'] ?: $request->notes,
                        'user_id'       => Auth::id(),
                    ]);
                }
            });

            return back()->with([
                'success' => true,
                'message' => 'Reverse voucher created successfully'
            ]);
        } catch (\Throwable $e) {
            return back()->with([
                'error' => true,
                'message' => 'Failed to save Reverse Voucher: ' . $e->getMessage()
            ]);
        }
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
            'notes'       => 'required|string',
            'details'     => 'required|array|min:1',
            'details.*.accountcode' => 'required|string',
            'details.*.particular' => 'nullable|string',
            'details.*.amount'     => 'required|numeric|min:0',
            'creditDetails' => 'required|array|min:1',
            'creditDetails.*.accountcode' => 'required|string',
            'creditDetails.*.particular' => 'nullable|string',
            'creditDetails.*.amount'     => 'required|numeric|min:0',
        ]);

        $details = $request->input('details', []);
        $creditDetails = $request->input('creditDetails', []);

        $totalDebit = collect($details)->sum('amount');
        $totalCredit = collect($creditDetails)->sum('amount');
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with([
                'error' => true,
                'message' => 'Total Debit and Total Credit must be equal.'
            ]);
        }

        DB::transaction(function () use ($request, $reverse, $details, $creditDetails) {
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

            // Delete all existing details
            Voucherdetail::where('vouchernumber', $voucher->vouchernumber)->forceDelete();

            // Re-create debit details
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
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

            // Re-create credit details
            foreach ($creditDetails as $crDetail) {
                $amt = abs($crDetail['amount']);
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $crDetail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -$amt,
                    'baseamt'       => -$amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $crDetail['particular'] ?: $request->notes,
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

            'draccountcode' => ChartOfAccount::where('active', '1')->whereIn('accounttype', ['ASSETS'])->where('accountusage', 'Ledger')->whereIn('analyticalcode', ['Non-Cash', 'Cash'])->get(),

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

        $voucherDate = Carbon::parse($request->voucherdate);
        $details = $request->input('details', []);
        $creditDetails = $request->input('creditDetails', []);

        $totalDebit = collect($details)->sum('amount');
        $totalCredit = collect($creditDetails)->sum('amount');
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with([
                'error' => true,
                'message' => 'Total Debit and Total Credit must be equal.'
            ]);
        }

        try {
            DB::transaction(function () use ($request, $voucherDate, $details, $creditDetails) {
                // Lock the sequence row for update to prevent concurrent duplicate number generation
                $transaction = Transaction::where('name', 'Opening Blance')
                    ->where('active', 1)
                    ->lockForUpdate()
                    ->first();

                if (!$transaction) {
                    throw new \Exception('Transaction sequence settings not found for Opening Balance.');
                }

                $nextCode = $transaction->lastnumber + 1;
                $openingNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

                // Update lastnumber immediately
                $transaction->update(['lastnumber' => $nextCode]);

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

                foreach ($details as $detail) {
                    $amt = abs($detail['amount']);
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

                foreach ($creditDetails as $crDetail) {
                    $amt = abs($crDetail['amount']);
                    Voucherdetail::create([
                        'vouchernumber' => $openingNo,
                        'accountcode'   => $crDetail['accountcode'],
                        'currency'      => 'BDT',
                        'exchagerate'   => '1.000',
                        'primeamt'      => -$amt,
                        'baseamt'       => -$amt,
                        'branch_id'     => $request->branch_id,
                        'notes'         => $crDetail['particular'] ?: $request->notes,
                        'user_id'       => Auth::id(),
                    ]);
                }
            });

            return back()->with([
                'success' => true,
                'message' => 'Opening Balance created successfully'
            ]);
        } catch (\Throwable $e) {
            return back()->with([
                'error' => true,
                'message' => 'Failed to save Opening Balance: ' . $e->getMessage()
            ]);
        }
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
            'notes'       => 'required|string',
            'details'     => 'required|array|min:1',
            'details.*.accountcode' => 'required|string',
            'details.*.particular' => 'nullable|string',
            'details.*.amount'     => 'required|numeric|min:0',
            'creditDetails' => 'required|array|min:1',
            'creditDetails.*.accountcode' => 'required|string',
            'creditDetails.*.particular' => 'nullable|string',
            'creditDetails.*.amount'     => 'required|numeric|min:0',
        ]);


        $details = $request->input('details', []);
        $creditDetails = $request->input('creditDetails', []);

        $totalDebit = collect($details)->sum('amount');
        $totalCredit = collect($creditDetails)->sum('amount');
        if (abs($totalDebit - $totalCredit) > 0.01) {
            return back()->with([
                'error' => true,
                'message' => 'Total Debit and Total Credit must be equal.'
            ]);
        }

        DB::transaction(function () use ($request, $opening, $details, $creditDetails) {
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

            // Delete all existing details
            Voucherdetail::where('vouchernumber', $voucher->vouchernumber)->forceDelete();

            // Re-create debit details per particular
            foreach ($details as $detail) {
                $amt = abs($detail['amount']);
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

            // Re-create credit details per particular
            foreach ($creditDetails as $crDetail) {
                $amt = abs($crDetail['amount']);
                Voucherdetail::create([
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $crDetail['accountcode'],
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -$amt,
                    'baseamt'       => -$amt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $crDetail['particular'] ?: $request->notes,
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
