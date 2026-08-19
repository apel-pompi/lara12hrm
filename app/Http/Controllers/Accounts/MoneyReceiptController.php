<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccount;
use App\Models\Accounts\CodesParam;
use App\Models\Accounts\Voucherdetail;
use App\Models\Accounts\Voucherheader;
use App\Models\Default\Transaction;
use App\Models\HRM\CompanyInfo;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentApplication;
use App\Models\Student\StudentInvoiceDT;
use App\Models\Student\StudentInvoiceHD;
use App\Models\Student\StudentMoneyReceiptDT;
use App\Services\Accounts\AllInvoiceDueService;
use App\Services\Accounts\AllInvoiceService;
use App\Services\Accounts\AllMoneyReceiptService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;
use Inertia\Inertia;

class MoneyReceiptController extends Controller
{
    use AuthorizesRequests;

    public function AllInvoiceList(Request $request, AllInvoiceService $allInvoice)
    {
        try {
            $this->authorize('Accounts.MRIndex');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $perPage = $request->integer('per_page', 10);
        $invoices = $allInvoice->get(
            array_merge($request->query(), ['per_page' => $perPage])
        );
        return Inertia::render('allpages/accounts/invoicelist/allindexlist', [
            'filters'     => $request->only(['insnumber', 'insdate', 'fname', 'lname', 'phone']),
            'invoice'     => $invoices,
            'allstudent'  => StudentInvoiceHD::with('student')
                ->where('status', 'Confirmed')
                ->whereIn(DB::raw("LEFT(insnumber, 4)"), ['INV-', 'SR--'])
                ->get(),
        ]);
    }

    public function DueInvoiceList(Request $request, AllInvoiceDueService $alldueInvoice)
    {

        try {
            $this->authorize('Accounts.MRIndex');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->integer('per_page', 10);
        $invoices = $alldueInvoice->get(
            array_merge($request->query(), ['per_page' => $perPage])
        );
        return Inertia::render('allpages/accounts/invoicelist/dueinvoicelist', [
            'filters'     => $request->only(['insnumber', 'insdate', 'fname', 'lname', 'phone']),
            'invoice'     => $invoices,
            'allinvoice' => StudentInvoiceHD::with('student')
                ->withSum('details', 'amount')
                ->where('status', 'Confirmed')
                ->whereIn(DB::raw("LEFT(insnumber, 4)"), ['INV-', 'SR--'])
                ->whereHas(
                    'student',
                    fn($q) =>
                    $q->whereNotNull('student_id')
                )
                ->having('details_sum_amount', '>', 0)
                ->get(),
        ]);
    }

    public function MRList(Request $request, AllMoneyReceiptService $allMoneyReceipt)
    {
        try {
            $this->authorize('Accounts.MRIndex');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->integer('per_page', 10);
        $moneyreceipt = $allMoneyReceipt->get(
            array_merge($request->query(), ['per_page' => $perPage])
        );

        return Inertia::render('allpages/accounts/invoicelist/allmoneyreceipt', [
            'filters'     => $request->only(['insnumber', 'insdate', 'fname', 'lname', 'phone', 'status']),
            'invoice'     => $moneyreceipt,
        ]);
    }

    public function createmr($insid, $sid)
    {
        try {
            $this->authorize('Accounts.CreateMR');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $invoice = StudentInvoiceHD::with(['details.fee', 'user'])->where('insnumber', $insid)->whereIn(DB::raw("LEFT(insnumber, 4)"), ['INV-', 'SR--'])->first();
        $invoicemr = StudentInvoiceHD::with(['user'])->where('refe_code', $insid)->whereRaw("LEFT(insnumber, 4) = 'MR--'")->get();
        $invoicemrSum = StudentInvoiceHD::where('refe_code', $insid)->whereRaw("LEFT(insnumber, 4) = 'MR--'")->where('status', 'Confirmed')->sum('netamount');
        $student = StudentApplication::with(['student', 'partnerBranch.partner', 'product'])->where('student_id', $sid)->first();

        StudentActivities::create([
            'student_id' => $sid,
            'title' => "has show student money receipt",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        $chartAccounts = ChartOfAccount::where('active', '1')
            ->where('accounttype', 'ASSETS')
            ->where('accountusage', 'Ledger')
            ->select('id', 'accountcode', 'description as accountname')
            ->get();

        return Inertia::render('allpages/accounts/createmr', [
            'invoice' => $invoice,
            'invoicemr' => $invoicemr,
            'invoicemrSum' => $invoicemrSum,
            'student' => $student,
            'chartAccounts' => $chartAccounts,
        ]);
    }

    private function GetMoneyNO()
    {
        $transaction = Transaction::where('name', 'Money Received')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function storeMR($insnumber, $student, Request $request)
    {


        try {
            $this->authorize('Accounts.storeMR');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }



        $mrNo = $this->GetMoneyNO();
        if (!$insnumber || !$student) {
            return back()->with(['error' => true, 'message' => 'Invalid request']);
        }

        $invoice = StudentInvoiceHD::where('insnumber', $insnumber)->first();

        if (!$invoice) {
            return back()->with(['error' => true, 'message' => 'Money receipt not found']);
        }

        if ($invoice->status !== 'Confirmed') {
            return back()->with(['error' => true, 'message' => 'Only Confirmed invoices can be create money received']);
        }
        $totalamt = $request->netamount;
        $netamount = $totalamt - $request->discount;
        $notes = substr($insnumber, 0, 4) == 'SR--' ? 'REFUND' : null;
        $payType = $request->paytype;
        $bank = '';
        if ($payType == 'Cash') {
            $codePharams = CodesParam::where('type', 'Student Advance')->select('cracc')->first();
            if (! $codePharams || ! $codePharams->cracc) {
                return back()->with([
                    'error' => true,
                    'message' => 'Accounting setup missing for Student Advance'
                ]);
            }
            $accountCode = $codePharams->cracc;
            $bankname = ChartOfAccount::where('accountcode', $accountCode)->first();
            $bank = $bankname->description;
        } else {
            $bankname = ChartOfAccount::where('accountcode', $request->bankname)->first();
            $accountCode = $request->bankname;
            $bank = $bankname->description;
        }

        $header = StudentInvoiceHD::create([
            'insnumber' => $mrNo,
            'insdate' => now(),
            'student_id' => $student,
            'payterms' => $request->paytype,
            'accountcode' => $accountCode,
            'bankname' => $bank,
            'bankbranch' => $request->bankbranch,
            'chequeno' => $request->chequeno,
            'transno' => $request->transactionNo,
            'currency' => 'BDT',
            'exchrate' => '1.00',
            'note' => $notes,
            'shortnote' => $request->shortnote,
            'totalamt' => $totalamt,
            'disc_rate' => null,
            'disc_amt' => $request->discount,
            'netamount' => $netamount,
            'sign' => '-1',
            'status' => 'Open',
            'refe_code' => $insnumber,
            'user_id' => Auth::id(),
        ]);
        foreach ($request->fees as $fee) {
            StudentMoneyReceiptDT::create([
                'insnumber_id' => $fee['invoice_hd_id'],
                'mrnumber_id'  => $header->id,
                'fees_id'      => $fee['fee_id'],
                'amount'       => $fee['amount'],
            ]);

            $details = StudentInvoiceDT::where('invoice_hd_id', $fee['invoice_hd_id'])
                ->where('fees_id', $fee['fee_id'])
                ->first();

            if ($details) {
                $update_amount = $details->amount - $fee['amount'];

                $details->update([
                    'amount' => $update_amount,
                ]);
            }
        }
        $numericPart = (int) preg_replace('/[^0-9]/', '', $mrNo);
        $transaction = Transaction::where('name', 'Money Received')->where('active', 1)->first();
        if ($transaction) {
            $transaction->update(['lastnumber' => $numericPart]);
        }

        StudentActivities::create([
            'student_id' => $student,
            'title' => "has create student money receipt",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        return back()->with(['success' => true, 'message' => 'Student money received create successfully']);
    }

    public function onView(StudentInvoiceHD $confirm)
    {
        try {
            $this->authorize('Accounts.ViewMR');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (!$confirm) {
            return back()->with(['error' => true, 'message' => 'Invalid request']);
        }

        $invoice = StudentInvoiceHD::with(['user'])->where('insnumber', $confirm->refe_code)->first();
        $money_reecive = StudentInvoiceHD::with(['student.country', 'mrdetails.fees'])->where('id', $confirm->id)->first();
        return response()->json([
            'success' => true,
            'data' => $money_reecive,
            'invoice' => $invoice
        ]);
    }

    public function onCancel(StudentInvoiceHD $confirm)
    {
        try {
            $this->authorize('Accounts.CancelMR');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (!$confirm) {
            return back()->with(['error' => true, 'message' => 'Invalid request']);
        }

        $invoice = StudentInvoiceHD::find($confirm->id);

        if (!$invoice) {
            return back()->with(['error' => true, 'message' => 'Invoice not found']);
        }

        if ($invoice->status !== 'Open') {
            return back()->with(['error' => true, 'message' => 'Only open receive can be Cancel']);
        }

        $invoice->update(['status' => 'Cancel']);

        $mramount = StudentInvoiceHD::where('refe_code', $confirm->refe_code)
            ->where('sign', '-1')
            ->where('status', '<>', 'Cancel')
            ->sum(DB::raw('disc_amt + netamount'));

        $invoice_amount = StudentInvoiceHD::where('insnumber', $confirm->refe_code)->first(['netamount', 'status']);
        if ($mramount == $invoice_amount->netamount) {
            $invoice_amount->update(['status' => 'Confirmed']);
        }

        StudentActivities::create([
            'student_id' => $confirm->student_id,
            'title' => "has confirm student money receipt",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        return back()->with(['success' => true, 'message' => 'Money receive confirmed successfully']);
    }


    public function onConfirm(StudentInvoiceHD $confirm)
    {

        try {
            $this->authorize('Accounts.ConfirmMR');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        if ($confirm->status !== 'Open') {
            return back()->with([
                'error' => true,
                'message' => 'Only open money receipt can be confirmed'
            ]);
        }

        $getstudent_id = Student::where('id', $confirm->student_id)->select('student_id')->first();
        $getcrCode = ChartOfAccount::where('description', $getstudent_id->student_id)->first('accountcode');

        $dr_account = '';
        $cr_account = '';
        $branch_id = '';
        if ($confirm->payterms == 'Cash') {
            $codePharams = CodesParam::where('type', 'Student Advance')->select('dracc', 'branch_id')->first();
            if (! $codePharams || ! $codePharams->dracc || ! $codePharams->branch_id) {
                return back()->with([
                    'error' => true,
                    'message' => 'Accounting setup missing for Student Ladger'
                ]);
            }
            $cr_account = $getcrCode->accountcode;
            $dr_account = $codePharams->dracc;
            $branch_id = $codePharams->branch_id;
        } else {
            $codePharams = CodesParam::where('type', 'Student Advance')->select('cracc', 'branch_id')->first();
            if (! $codePharams || ! $codePharams->cracc || ! $codePharams->branch_id) {
                return back()->with([
                    'error' => true,
                    'message' => 'Accounting setup missing for Student Ladger'
                ]);
            }
            $cr_account = $getcrCode->accountcode;
            $dr_account = $confirm->accountcode;
            $branch_id = $codePharams->branch_id;
        }


        $voucherDate = Carbon::parse($confirm->insdate);

        $money_reecive = StudentInvoiceHD::with(['mrdetails.fees'])
            ->where('id', $confirm->id)
            ->first();

        $notes = $money_reecive->mrdetails
            ->pluck('fees.name')
            ->implode(' and ');


        $credit_account = '';
        $debit_account = '';
        $credit_note = '';
        if ($confirm->note == 'REFUND') {
            $credit_account = $cr_account;
            $debit_account = $dr_account;
            $credit_note = $notes . ' (REFUND)';
        } else {
            $credit_account = $dr_account;
            $debit_account = $cr_account;
            //notes
            $credit_note = $notes;
        }

        DB::transaction(function () use ($confirm, $branch_id, $voucherDate, $getstudent_id, $credit_account, $debit_account, $credit_note) {
            Voucherheader::create([
                'vouchernumber' => $confirm->insnumber,
                'voucherdate'   => $confirm->insdate,
                'referance'     => $credit_note . ' ( ' . $getstudent_id->student_id . ' )',
                'yearname'      => $voucherDate->year,
                'monthname'     => $voucherDate->month,
                'branch_id'     => $branch_id,
                'notes'         => 'This is Money Receipt',
                'user_id'       => Auth::id(),
            ]);
            Voucherdetail::insert([
                [
                    'vouchernumber' => $confirm->insnumber,
                    'accountcode'   => $credit_account,
                    'subacccode'    => $getstudent_id->student_id,
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $confirm->netamount,
                    'baseamt'       => $confirm->netamount,
                    'branch_id'     => $branch_id,
                    'notes'         => $credit_note,
                    'user_id'       => Auth::id(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ],
                [
                    'vouchernumber' => $confirm->insnumber,
                    'accountcode'   => $debit_account,
                    'subacccode'    => $getstudent_id->student_id,
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => abs($confirm->netamount) * -1,
                    'baseamt'       => abs($confirm->netamount) * -1,
                    'branch_id'     => $branch_id,
                    'notes'         => $credit_note,
                    'user_id'       => Auth::id(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            ]);
        });
        $confirm->update(['status' => 'Confirmed']);

        DB::statement(
            'CALL sp_am_voucherpost(?, ?)',
            [$confirm->insnumber, Auth::id()]
        );

        $stud = Student::where('id', $confirm->student_id)->first();
        $stud->update(['status' => 3]);

        $received  = StudentInvoiceHD::where('refe_code', $confirm->refe_code)
            ->where('sign', -1)
            ->where('status', '<>', 'Cancel')
            ->sum(DB::raw('disc_amt + netamount'));

        $invoice = StudentInvoiceHD::where('insnumber', $confirm->refe_code)->first();
        if ($invoice && $received >= $invoice->netamount) {
            $invoice->update(['status' => 'Confirmed']);
        }

        StudentActivities::create([
            'student_id' => $confirm->student_id,
            'title' => "has confirm student money receipt",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        return back()->with(['success' => true, 'message' => 'Money receive confirmed successfully']);
    }


    public function onReport(StudentInvoiceHD $onReport)
    {


        try {
            $this->authorize('Accounts.ReportMR');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $company = CompanyInfo::firstOrFail();
        $student = Student::with(['country'])->where('id', $onReport->student_id)->first();

        $receipt = StudentInvoiceHD::with(['mrdetails.fees', 'user'])->where('id', $onReport->id)->first();

        if (!$receipt) {
            abort(404, 'Invoice not found');
        }

        $dataArray = [];
        foreach ($receipt->mrdetails as $item) {
            $item->pay_type = $this->getPaytype($onReport->refe_code, $item->fees_id);
            $dataArray[] = [
                'feename' => $item->fees->name,
                'amount' => $item->amount,
                'pay_type' => $item->pay_type,
            ];
        }


        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');

        StudentActivities::create([
            'student_id' => $onReport->student_id,
            'title' => "has confirm student money receipt",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        $pdf = Pdf::loadView('exports.MoneyRecive', [
            'company' => $company,
            'student' => $student,
            'receipt' => $receipt,
            'dataArray' => $dataArray,
            'numberTransformer' => $numberTransformer
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 10,
                'margin-bottom' => 5,
                'margin-left'   => 10,
            ]);;

        return $pdf->stream("MoneyReceipt{$receipt->insnumber}.pdf");
    }

    protected function getPaytype($insnumber, $fees_id)
    {

        if (substr($insnumber, 0, 4) == 'INV-') {
            $result = DB::table('student_invoice_hd AS a')

                ->leftJoin('student_quotation_h_d_s AS b', 'a.refe_code', '=', 'b.quotation_no')
                ->leftJoin('product_fees_hds AS c', 'b.product_id', '=', 'c.product_id')
                ->leftJoin('product_fees_dts AS d', 'c.id', '=', 'd.fees_hd_id')
                ->select('d.pay_type')
                ->where('a.insnumber', $insnumber)
                ->whereColumn('a.student_id', 'b.student_id')
                ->where('d.fees_id', $fees_id)
                ->whereNull('a.deleted_at')
                ->first();
            $payType = $result ? $result->pay_type : null;
            return $payType;
        } elseif (substr($insnumber, 0, 4) == 'SR--') {
            $getins  = StudentInvoiceHD::where('refe_code', $insnumber)->first();
            $getmr = StudentInvoiceHD::where('insnumber', $getins->refe_code)->first();
            $getquot = StudentInvoiceHD::where('insnumber', $getmr->refe_code)->first();
            $result = DB::table('student_invoice_hd AS a')

                ->leftJoin('student_quotation_h_d_s AS b', 'a.refe_code', '=', 'b.quotation_no')
                ->leftJoin('product_fees_hds AS c', 'b.product_id', '=', 'c.product_id')
                ->leftJoin('product_fees_dts AS d', 'c.id', '=', 'd.fees_hd_id')
                ->select('d.pay_type')
                ->where('a.insnumber', $getquot->refe_code)
                ->whereColumn('a.student_id', 'b.student_id')
                ->where('d.fees_id', $fees_id)
                ->whereNull('a.deleted_at')
                ->first();
            $payType = $result ? $result->pay_type : null;
            return $payType;
        }
    }
}
