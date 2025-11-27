<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Default\Transaction;
use App\Models\HRM\CompanyInfo;
use App\Models\Student\Student;
use App\Models\Student\StudentApplication;
use App\Models\Student\StudentInvoiceDT;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student\StudentInvoiceHD;
use App\Models\Student\StudentMoneyReceiptDT;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Models\Student\StudentActivities;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;
use Inertia\Inertia;

class AccountsController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        try {
            $this->authorize('Accounts.MRIndex');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/accounts/index', [
            'invoice' => StudentInvoiceHD::with(['student', 'user'])
                ->withSum('details', 'amount')
                ->where('status', 'Confirmed')
                ->whereRaw("LEFT(insnumber, 4) = 'INV-'")
                ->having('details_sum_amount', '>', 0)
                ->paginate(15),
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


        $invoice = StudentInvoiceHD::with(['details.fee', 'user'])->where('insnumber', $insid)->whereRaw("LEFT(insnumber, 4) = 'INV-'")->first();
        $invoicemr = StudentInvoiceHD::with(['user'])->where('refe_code', $insid)->whereRaw("LEFT(insnumber, 4) = 'MR--'")->get();
        $student = StudentApplication::with(['student', 'partnerBranch.partner', 'product'])->where('student_id', $sid)->first();

        StudentActivities::create([
            'student_id' => $sid,
            'title' => "has show student money receipt",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        return Inertia::render('allpages/accounts/createmr', [
            'invoice' => $invoice,
            'invoicemr' => $invoicemr,
            'student' => $student
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
        $header = StudentInvoiceHD::create([
            'insnumber' => $mrNo,
            'insdate' => now(),
            'student_id' => $student,
            'payterms' => $request->paytype,
            'accountcode' => '104-001',
            'bankname' => $request->bankname,
            'bankbranch' => $request->bankbranch,
            'chequeno' => $request->chequeno,
            'transno' => $request->transactionNo,
            'currency' => 'BDT',
            'exchrate' => '1.00',
            'note' => null,
            'totalamt' => $totalamt,
            'disc_rate' => null,
            'disc_amt' => $request->discount,
            'netamount' => $netamount,
            'sign' => '-1',
            'status' => 'Open',
            'refe_code' => $insnumber,
            'user_id' => auth()->id(),
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

        $invoice = StudentInvoiceHD::with(['user'])->where('insnumber',$confirm->refe_code)->first();
        $money_reecive = StudentInvoiceHD::with(['student.country','mrdetails.fees'])->where('id',$confirm->id)->first();
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
            $invoice_amount->update(['status' => 'Delivered']);
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


        if (!$confirm) {
            return back()->with(['error' => true, 'message' => 'Invalid request']);
        }

        $invoice = StudentInvoiceHD::find($confirm->id);

        if (!$invoice) {
            return back()->with(['error' => true, 'message' => 'Invoice not found']);
        }

        if ($invoice->status !== 'Open') {
            return back()->with(['error' => true, 'message' => 'Only open receive can be confirmed']);
        }

        $invoice->update(['status' => 'Confirmed']);

        $stud = Student::where('id', $confirm->student_id)->first();
        $stud->update(['status' => 3]);

        $mramount = StudentInvoiceHD::where('refe_code', $confirm->refe_code)
            ->where('sign', '-1')
            ->where('status', '<>', 'Cancel')
            ->sum(DB::raw('disc_amt + netamount'));

        $invoice_amount = StudentInvoiceHD::where('insnumber', $confirm->refe_code)->first(['netamount', 'status']);
        if ($mramount == $invoice_amount->netamount) {
            $invoice_amount->update(['status' => 'Delivered']);
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
}
