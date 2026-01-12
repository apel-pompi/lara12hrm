<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Default\ApprovalRequest;
use App\Models\Default\Transaction;
use App\Models\HRM\CompanyInfo;
use App\Models\Student\Student;
use App\Models\Student\StudentApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student\StudentInvoiceHD;
use App\Models\Student\StudentQuoationFee;
use App\Models\Student\StudentQuotationHD;
use App\Models\Student\StudentInvoiceDT;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentMoneyReceiptDT;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use NumberToWords\NumberToWords;
use Inertia\Inertia;

class StudentAccounts extends Controller
{
    use AuthorizesRequests;

    public function index(Student $student)
    {
        try {
            $this->authorize('StudIns.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/accouts', [
            'student' => $student,
            'quoatation' => StudentQuotationHD::with(['user'])->where('student_id', $student->id)->where('active', 1)->get(),
            'quoatation_amount' => StudentQuotationHD::where('student_id', $student->id)->where('active', 1)->sum('totalamount'),

            'invoice' => StudentInvoiceHD::where('student_id', $student->id)->whereRaw("LEFT(insnumber, 4) = 'INV-'")->where('sign', 1)->get(),
            'invoice_amount' => StudentInvoiceHD::where('student_id', $student->id)->whereRaw("LEFT(insnumber, 4) = 'INV-'")->where('sign', 1)->sum('netamount'),

            'mr' => StudentInvoiceHD::where('student_id', $student->id)
                ->whereRaw("LEFT(insnumber, 4) = 'MR--'")
                ->where('sign', -1)
                ->where(function ($q) {
                    $q->where('note', '<>', 'REFUND')
                        ->orWhereNull('note');
                })
                ->get(),
            'mr_amount' => StudentInvoiceHD::where('student_id', $student->id)
                ->whereRaw("LEFT(insnumber, 4) = 'MR--'")
                ->where('sign', -1)
                ->where(function ($q) {
                    $q->where('note', '<>', 'REFUND')
                        ->orWhereNull('note');
                })
                ->sum('netamount'),

            'application' => StudentApplication::where('student_id', $student->id)->first()
        ]);
    }

    public function return(Student $student)
    {
        try {
            $this->authorize('StudIns.refund');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $student->load('assainuser');

        return Inertia::render('allpages/Agency/Student/amountreturn', [
            'student' => $student,
            'mr' => StudentInvoiceHD::where('student_id', $student->id)
                ->whereRaw("LEFT(insnumber, 4) = 'MR--'")
                ->where('sign', -1)
                ->where(function ($q) {
                    $q->where('note', '<>', 'REFUND')
                        ->orWhereNull('note');
                })
                ->get(),
            'mr_amount' => StudentInvoiceHD::where('student_id', $student->id)
                ->whereRaw("LEFT(insnumber, 4) = 'MR--'")
                ->where('sign', -1)
                ->where(function ($q) {
                    $q->where('note', '<>', 'REFUND')
                        ->orWhereNull('note');
                })
                ->sum('netamount'),
            'srinvoice' => StudentInvoiceHD::where('student_id', $student->id)->whereRaw("LEFT(insnumber, 4) = 'SR--'")->where('sign', 1)->get(),
            'sr_amount' => StudentInvoiceHD::where('student_id', $student->id)->whereRaw("LEFT(insnumber, 4) = 'SR--'")->where('sign', 1)->where('status','<>','Cancel')->sum('netamount'),

        ]);
    }

    public function fetchMR(Student $student, $mrid)
    {
        try {
            $this->authorize('StudIns.refund');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $mrhd = StudentInvoiceHD::with(['mrdetails.fees'])->find($mrid);
        $refe_code = $mrhd->refe_code;
        $quoat = StudentInvoiceHD::where('insnumber', $refe_code)->first(['refe_code']);
        $quoatno = $quoat->refe_code;

        $service = DB::table('student_quotation_h_d_s as a')
            ->select(
                'a.quotation_no',
                'a.totalamount',
                'd.name as workflowname',
                'f.name as partnername',
                'g.branch_name as branchname',
                'e.name as productname'
            )
            ->leftJoin('student_in_services as s', 'a.product_id', '=', 's.product_id')
            ->leftJoin('workflows as d', 's.workflow_id', '=', 'd.id')
            ->leftJoin('products as e', 's.product_id', '=', 'e.id')
            ->leftJoin('partners as f', 'e.partner_id', '=', 'f.id')
            ->leftJoin('partner_branches as g', 's.partner_branch_id', '=', 'g.id')
            ->where('a.quotation_no', $quoatno)
            ->where('s.student_id', $student->id)
            ->whereNull('a.deleted_at')
            ->whereNull('s.deleted_at')
            ->whereNull('d.deleted_at')
            ->whereNull('e.deleted_at')
            ->whereNull('f.deleted_at')
            ->whereNull('g.deleted_at')
            ->get();

        return response()->json([
            'success' => true,
            'student' => [
                'stuid' => $student->id,
                'fname' => $student->fname,
                'lname' => $student->lname,
                'gender' => $student->gender,
                'email' => $student->email,
                'phone' => $student->phone,
                'descountry_id' => $student->country->name,
            ],

            'mrhd' => $mrhd,
            'service' => $service,
        ]);
    }

    private function GetInvoiceReturnNO()
    {
        $transaction = Transaction::where('name', 'Amount Refund')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function storeReturn(Student $student, Request $request)
    {
     
        if (empty($request->selectedFees)) {
            return back()->with(['success' => false, 'message' => 'No fees selected']);
        }

        $invoiceNo = $this->GetInvoiceReturnNO();
        $total = array_sum(array_column($request->selectedFees, 'amount'));

        $invoiceHD = StudentInvoiceHD::create([
            'insnumber' => $invoiceNo,
            'insdate' => now(),
            'student_id' => $student->id,
            'payterms' => null,
            'accountcode' => null,
            'bankname' => null,
            'bankbranch' => null,
            'chequeno' => null,
            'transno' => null,
            'currency' => 'BDT',
            'exchrate' => '1.00',
            'note' => null,
            'shortnote' => $request->shortnote,
            'totalamt' => array_sum(array_column($request->selectedFees, 'amount')),
            'disc_rate' => null,
            'disc_amt' => null,
            'netamount' => $total,
            'sign' => '1',
            'status' => 'pending',
            'refe_code' => $request->refe_code,
            'user_id' => Auth::id(),
        ]);

        $numericPart = (int) preg_replace('/[^0-9]/', '', $invoiceNo);
        $transaction = Transaction::where('name', 'Amount Refund')->where('active', 1)->first();
        if ($transaction) {
            $transaction->update(['lastnumber' => $numericPart]);
        }

        foreach ($request->selectedFees as $fee) {

            StudentInvoiceDT::create([
                'invoice_hd_id' => $invoiceHD->id,
                'fees_id' => $fee['fee_id'],
                'amount' => $fee['amount']
            ]);
        }

        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has created student refund invoice's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        return back()->with(['success' => true, 'message' => 'Student Refund Invoice created successfully']);
    }

    public function fetchSR(Student $student, $srid)
    {
        try {
            $this->authorize('StudIns.refund');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $mrhd = StudentInvoiceHD::find($srid);
        $refe_code = $mrhd->refe_code;
        $quoat = StudentInvoiceHD::where('insnumber', $refe_code)->first(['id', 'insdate']);
        $quoatno = $quoat->id;

        $service = StudentMoneyReceiptDT::with(['fees'])->where('mrnumber_id', $quoatno)->get();
        $return = StudentInvoiceDT::with(['fee'])->where('invoice_hd_id', $srid)->get();
        return response()->json([
            'success' => true,
            'mrhd' => $mrhd,
            'service' => $service,
            'return' => $return,
        ]);
    }

    public function returnCancel(Student $student, $confirm)
    {
        $data = StudentInvoiceHD::where('id', $confirm)->where('student_id', $student->id);
        $data->update(['status' => 'Cancel']);
    }

    public function returnConfirm(Student $student, $confirm)
    {

        if (ApprovalRequest::where('reference_id', $student->id)->where('description', $confirm)->where('remarks', 'Refund')->where('status', null)->exists()) {
            return back()->with('error', 'Invoice refund request is allready exits');
        }

        $created = ApprovalRequest::create([
            'reference_id' => $student->id,
            'description' => $confirm,
            'remarks' => 'Refund',
            'status' => null,
            'user_id' => Auth::id()
        ]);
        if ($created) {
            StudentActivities::create([
                'student_id'    => $student->id,
                'title'         => "has request student amount refund",
                'fristactivity' => null,
                'lastactivity'  => null,
                'user_id'       => Auth::id(),
            ]);
        }

        $data = StudentInvoiceHD::where('id', $confirm)->where('student_id', $student->id);
        $data->update(['status' => 'Send']);
    }



    public function create(Student $student, StudentQuotationHD $quotation)
    {
        try {
            $this->authorize('StudIns.create');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $student->load('country');
        $quotation->load('user');
        $services = DB::table('student_applications as a')
            ->select(
                'd.name as workflow',
                'c.name as partner',
                'b.branch_name',
                'e.name as product'
            )
            ->leftJoin('partner_branches as b', 'a.partner_branch_id', '=', 'b.id')
            ->leftJoin('partners as c', 'b.partner_id', '=', 'c.id')
            ->leftJoin('workflows as d', 'a.workflow_id', '=', 'd.id')
            ->leftJoin('products as e', 'a.product_id', '=', 'e.id')
            ->where('a.student_id', $student->id)
            ->where('a.product_id', $quotation->product_id)
            ->get();
        $fees = DB::table('student_quotation_h_d_s as a')
            ->select(
                'b.fee_id',
                'c.name as feename',
                'b.amount'
            )
            ->leftJoin('student_quoation_fees as b', 'a.id', '=', 'b.quotation_hd_id')
            ->leftJoin('fees as c', 'b.fee_id', '=', 'c.id')
            ->where('a.active', 1)
            ->where('a.student_id', $student->id)
            ->where('a.product_id', $quotation->product_id)
            ->where('a.id', $quotation->id)
            ->get();

        return response()->json([
            'success' => true,
            'student' => [
                'stuid' => $student->id,
                'fname' => $student->fname,
                'lname' => $student->lname,
                'gender' => $student->gender,
                'email' => $student->email,
                'phone' => $student->phone,
                'descountry_id' => $student->country->name,
            ],
            'quotation' => [
                'quot_id' => $quotation->id,
                'quotation_no' => $quotation->quotation_no,
                'adddate' => $quotation->adddate,
                'user' => $quotation->user->name ?? '-',
            ],
            'services' => $services,
            'fees' => $fees,
        ]);
    }

    private function GetInvoiceNO()
    {
        $transaction = Transaction::where('name', 'Invoice No')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }


    public function store(Student $student, StudentQuotationHD $quotation, Request $request)
    {
        try {
            $this->authorize('StudIns.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (empty($request->selectedFees)) {
            return back()->with(['success' => false, 'message' => 'No fees selected']);
        }

        $invoiceNo = $this->GetInvoiceNO();
        $total = array_sum(array_column($request->selectedFees, 'amount'));
        $netamt = $total - $request->discount;
        $invoiceHD = StudentInvoiceHD::create([
            'insnumber' => $invoiceNo,
            'insdate' => now(),
            'student_id' => $student->id,
            'payterms' => null,
            'accountcode' => null,
            'bankname' => null,
            'bankbranch' => null,
            'chequeno' => null,
            'transno' => null,
            'currency' => 'BDT',
            'exchrate' => '1.00',
            'note' => null,
            'totalamt' => array_sum(array_column($request->selectedFees, 'amount')),
            'disc_rate' => null,
            'disc_amt' => null,
            'netamount' => $netamt,
            'sign' => '1',
            'status' => 'pending',
            'refe_code' => $quotation->quotation_no,
            'user_id' => Auth::id(),
        ]);

        $numericPart = (int) preg_replace('/[^0-9]/', '', $invoiceNo);
        $transaction = Transaction::where('name', 'Invoice No')->where('active', 1)->first();
        if ($transaction) {
            $transaction->update(['lastnumber' => $numericPart]);
        }

        foreach ($request->selectedFees as $fee) {

            StudentInvoiceDT::create([
                'invoice_hd_id' => $invoiceHD->id,
                'fees_id' => $fee['fee_id'],
                'amount' => $fee['amount']
            ]);

            $chkamount = StudentQuoationFee::where('quotation_hd_id', $quotation->id)
                ->where('fee_id', $fee['fee_id'])
                ->first();

            if ($chkamount) {
                $updateamount = $chkamount->amount - $fee['amount'];
                $chkamount->update([
                    'amount' => max($updateamount, 0),
                ]);
            }
        }

        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has created student invoice's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);
        return back()->with(['success' => true, 'message' => 'Student Invoice created successfully']);
    }

    public function onDelete(Student $student, StudentInvoiceHD $confirm)
    {
        try {
            $this->authorize('StudIns.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (!$student || !$confirm) {
            return back()->with(['error' => true, 'message' => 'Invalid request']);
        }

        $invoice = StudentInvoiceHD::with(['details'])->find($confirm->id);

        if (!$invoice) {
            return back()->with(['error' => true, 'message' => 'Invoice not found']);
        }

        if ($invoice->status !== 'pending') {
            return back()->with(['error' => true, 'message' => 'Only pending invoices can be cancelled']);
        }
        $quatHD = StudentQuotationHD::where('quotation_no', $invoice->refe_code)->first(['id']);

        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has cancel student invoice's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);
        foreach ($invoice->details as $key) {
            $invamount = $key->amount;
            $fees_id = $key->fees_id;

            $updatequoat = StudentQuoationFee::where('quotation_hd_id', $quatHD->id)
                ->where('fee_id', $fees_id)
                ->first();
            if ($updatequoat) {
                $quoat_amount = $updatequoat->amount;
                $update_amount = $quoat_amount + $invamount;

                $updatequoat->update(['amount' => $update_amount]);
            }
        }

        $invoice->update(['status' => 'Cancel']);

        return back()->with(['success' => true, 'message' => 'Student Invoice cancelled successfully']);
    }

    public function onConfirm(Student $student, StudentInvoiceHD $confirm)
    {
        try {
            $this->authorize('StudIns.confirm');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (!$student || !$confirm) {
            return back()->with(['error' => true, 'message' => 'Invalid request']);
        }

        $invoice = StudentInvoiceHD::find($confirm->id);

        if (!$invoice) {
            return back()->with(['error' => true, 'message' => 'Invoice not found']);
        }

        if ($invoice->status !== 'pending') {
            return back()->with(['error' => true, 'message' => 'Only pending invoices can be confirmed']);
        }

        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has confirm student invoice's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        $invoice->update(['status' => 'Confirmed']);

        return back()->with(['success' => true, 'message' => 'Student Invoice confirmed successfully']);
    }

    public function onView(Student $student, StudentInvoiceHD $confirm)
    {
        try {
            $this->authorize('StudIns.view');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        if (!$student || !$confirm) {
            return back()->with(['error' => true, 'message' => 'Invalid request']);
        }

        $invoice = StudentInvoiceHD::with(['student.country', 'details.fee'])->where('id', $confirm->id)->where('student_id', $student->id)->first();
        $quoat = StudentQuotationHD::with(['user'])->where('quotation_no', $invoice->refe_code)->first();
        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has view student invoice's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);
        return response()->json([
            'success' => true,
            'data' => $invoice,
            'quoat' => $quoat
        ]);
    }

    public function onReport(Student $student, StudentInvoiceHD $confirm)
    {
        try {
            $this->authorize('StudIns.report');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has show student invoice's reports",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        $student->load('country');
        $company = CompanyInfo::firstOrFail();
        $invoiceHD = DB::table('student_invoice_hd as a')
            ->select('a.insnumber', 'a.insdate', 'a.shortnote', 'a.netamount', 'a.refe_code', 'name as username', 'a.payterms', 'a.chequeno', 'a.bankname', 'a.bankbranch', 'a.transno')
            ->leftJoin('users as b', 'a.user_id', '=', 'b.id')
            ->where('a.id', $confirm->id)
            ->first();

        if (!$invoiceHD) {
            abort(404, 'Invoice not found');
        }

        $invoiceDt = DB::table('student_invoice_hd as a')
            ->leftJoin('student_invoices_dt as b', 'a.id', '=', 'b.invoice_hd_id')
            ->leftJoin('student_money_receipt_d_t_s as c', function ($join) {
                $join->on('a.id', '=', 'c.insnumber_id')
                    ->on('b.fees_id', '=', 'c.fees_id');
            })
            ->leftJoin('fees as e', 'b.fees_id', '=', 'e.id')
            ->where('a.id', $confirm->id)
            ->groupBy('a.insnumber', 'b.fees_id', 'b.amount', 'e.name')
            ->selectRaw("
            a.insnumber,
        b.fees_id,
        e.name,
        b.amount as invamount,
        SUM(b.amount) as mrtotal,
        (COALESCE(b.amount, 0) + COALESCE(SUM(c.amount), 0)) as totalamount
    ")
            ->get();
          
        $dataArray = [];
        foreach ($invoiceDt as $key) {
            $fees_id = $key->fees_id;
            $paytype = $this->getPaytype($key->insnumber, $fees_id);
            
            $dataArray[] = [
                'feename' => $key->name,
                'amount' => $key->invamount,
                'totalamount' => $key->totalamount,
                'pay_type' => $paytype,
            ];
        }
       
        
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');

        $pdf = Pdf::loadView('exports.studentInvoice', [
            'student' => $student,
            'company' => $company,
            'invoiceHD' => $invoiceHD,
            'invoiceDt' => $dataArray,
            'numberTransformer' => $numberTransformer
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 10,
                'margin-right'  => 10,
                'margin-bottom' => 10,
                'margin-left'   => 10,
            ]);;

        return $pdf->stream("Invoice{$invoiceHD->insnumber}.pdf");
    }

    protected function getPaytype($insnumber,$fees_id)
    {
        
        if(substr($insnumber, 0, 4) == 'INV-'){
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
        }elseif(substr($insnumber, 0, 4) == 'SR--'){
            $getmr = StudentInvoiceHD::where('insnumber', $insnumber)->first();
            $refe_code = $getmr->refe_code;
            $getins  = StudentInvoiceHD::where('insnumber', $refe_code)->first();
            $getquot = StudentInvoiceHD::where('insnumber', $getins->refe_code)->first();
            
           

            $result = DB::table('student_invoice_hd AS a')

                ->leftJoin('student_quotation_h_d_s AS b', 'a.refe_code', '=', 'b.quotation_no')
                ->leftJoin('product_fees_hds AS c', 'b.product_id', '=', 'c.product_id')
                ->leftJoin('product_fees_dts AS d', 'c.id', '=', 'd.fees_hd_id')
                ->select('d.pay_type')
                ->where('b.quotation_no', $getquot->refe_code)
                ->whereColumn('a.student_id', 'b.student_id')
                ->where('d.fees_id', $fees_id)
                ->whereNull('a.deleted_at')
                ->first();
            $payType = $result ? $result->pay_type : null;
           
            return $payType;

        }
    }
}
