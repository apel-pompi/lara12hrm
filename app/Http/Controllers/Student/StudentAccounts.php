<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
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
        $this->authorize('StudIns.index');
        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/accouts', [
            'student' => $student,
            'quoatation' => StudentQuotationHD::with(['user'])->where('student_id', $student->id)->where('active', 1)->get(),
            
            'invoice' => StudentInvoiceHD::where('student_id', $student->id)->whereRaw("LEFT(insnumber, 4) = 'INV-'")->get(),
            'application' => StudentApplication::where('student_id', $student->id)->first()
        ]);
    }

    public function create(Student $student, StudentQuotationHD $quotation)
    {
        $this->authorize('StudIns.create');

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

        $this->authorize('StudIns.store');

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
            'user_id' => auth()->id(),
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
        
        $this->authorize('StudIns.destroy');

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
        $quatHD = StudentQuotationHD::where('quotation_no',$invoice->refe_code)->first(['id']);
 
        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has cancel student invoice's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);
        foreach ($invoice->details as $key) {
            $invamount = $key->amount;
            $invoice_id = $key->invoice_hd_id;
            $fees_id = $key->fees_id;

            $updatequoat = StudentQuoationFee::where('quotation_hd_id', $quatHD->id)
                ->where('fee_id', $fees_id)
                ->first();
            //dd($invamount,$invoice_id,$fees_id,$updatequoat);
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

        $this->authorize('StudIns.confirm');

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



    public function onReport(Student $student, StudentInvoiceHD $confirm)
    {

        $this->authorize('StudIns.report');

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
            ->select('a.insnumber', 'a.insdate', 'a.netamount', 'a.refe_code', 'name as username', 'a.payterms', 'a.chequeno', 'a.bankname', 'a.bankbranch', 'a.transno')
            ->leftJoin('users as b', 'a.user_id', '=', 'b.id')
            ->where('a.id', $confirm->id)
            ->first();
        $invoiceDt = DB::table('student_invoices_dt as a')
            ->select('b.name', 'a.amount')
            ->leftJoin('fees as b', 'a.fees_id', '=', 'b.id')
            ->where('a.invoice_hd_id', $confirm->id)
            ->get();
        if (!$invoiceHD) {
            abort(404, 'Invoice not found');
        }

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');

        $pdf = Pdf::loadView('exports.studentInvoice', [
            'student' => $student,
            'company' => $company,
            'invoiceHD' => $invoiceHD,
            'invoiceDt' => $invoiceDt,
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
}
