<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AgencySetting\Fees;
use App\Models\Default\ApprovalRequest;
use App\Models\Default\Transaction;
use App\Models\HRM\CompanyInfo;
use App\Models\Product\Product;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentInService;
use App\Models\Student\StudentInvoiceHD;
use App\Models\Student\StudentQuoationFee;
use App\Models\Student\StudentQuotation;
use App\Models\Student\StudentQuotationHD;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;
use NumberToWords\NumberToWords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentQuotations extends Controller
{
    use AuthorizesRequests;

    public function index(Student $student)
    {
        try {
            $this->authorize('StudQuoat.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $user = Auth::user();
        $roles = $user->getRoleNames();


        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/quoatation', [
            'student' => $student,

            'service' => DB::table('student_in_services as a')
                ->leftJoin('workflows as b', 'a.workflow_id', '=', 'b.id')
                ->leftJoin('partner_branches as c', 'a.partner_branch_id', '=', 'c.id')
                ->leftJoin('partners as d', 'c.partner_id', '=', 'd.id')
                ->leftJoin('product_fees_hds as e', 'a.product_id', '=', 'e.product_id')
                ->leftJoin('products as f', 'a.product_id', '=', 'f.id')
                ->select(
                    'a.id',
                    'b.name as workflow',
                    'd.name as partner',
                    'c.branch_name as partnerbranch',
                    'f.name as product',
                    'a.product_id',
                    'a.status',
                    DB::raw('SUM(e.netamount) as amount')
                )
                ->where('a.student_id', $student->id)
                ->where('e.deleted_at', null)
                ->where('a.deleted_at', null)
                ->where('a.deleted_at', null)
                ->groupBy('a.id', 'a.product_id', 'b.name', 'd.name', 'c.branch_name', 'f.name', 'a.status')
                ->get(),
            'quoatation' => StudentQuotationHD::where('student_id', $student->id)->get(),
            'roles' => $roles,
            'feestype' => Fees::where('active', 1)->get(),
        ]);
    }

    public function fetchData($student, $product)
    {
        $fees = DB::table('student_in_services as a')
            ->leftJoin('product_fees_hds as b', 'a.product_id', '=', 'b.product_id')
            ->leftJoin('product_fees_dts as c', 'b.id', '=', 'c.fees_hd_id')
            ->leftJoin('fees as d', 'c.fees_id', '=', 'd.id')
            ->where('a.student_id', $student)
            ->where('a.product_id', $product)
            ->where('b.deleted_at', null)
            ->where('c.deleted_at', null)
            ->select(
                'd.id as feesid',
                'd.name as feename',
                'c.amount',
                'c.insqty',
                'c.pay_type',
                'c.totalamount'
            )
            ->get();
        return response()->json([
            'success' => true,
            'fees' => $fees
        ]);
    }


    private function GetInvoiceNO()
    {
        $transaction = Transaction::where('name', 'Quoatations No')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function store(Student $student, Request $request)
    {

        try {
            $this->authorize('StudQuoat.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $QuaotNo = $this->GetInvoiceNO();
        if ($QuaotNo) {


            $createHd = StudentQuotationHD::create([
                'quotation_no' => $QuaotNo,
                'student_id' => $student->id,
                'product_id' => $request->product_id,
                'totalamount' => $request->grandTotal,
                'notes' => $request->note,
                'status' => 0,
                'adddate' => date('y-m-d'),
                'user_id' => auth()->id(),
                'active' => 0,
            ]);
            if ($createHd) {

                foreach ($request['fees'] as $value) {
                    StudentQuoationFee::create([
                        'student_id' => $student->id,
                        'quotation_hd_id' => $createHd->id,
                        'fee_id' => $value['feesid'],
                        'amount' => $value['amount'],
                        'quaotamount' => $value['amount'],
                        'paytype' => $value['pay_type'],
                        'user_id' => auth()->id()
                    ]);
                }
            }
            $numericPart = (int) preg_replace('/[^0-9]/', '', $QuaotNo);
            $transaction = Transaction::where('name', 'Quoatations No')->where('active', 1)->first();
            if ($transaction) {
                $transaction->update(['lastnumber' => $numericPart]);
            }

            StudentActivities::create([
                'student_id' => $student->id,
                'title' => "has created student quotation's",
                'fristactivity' => null,
                'lastactivity' => null,
                'user_id' => Auth::id()
            ]);

            $chkamount = DB::table('student_quotation_h_d_s as a')
                ->select(
                    'a.totalamount',
                    'a.quotation_no',
                    DB::raw('SUM(b.netamount) as amount')
                )
                ->leftJoin('product_fees_hds as b', 'a.product_id', '=', 'b.product_id')
                ->where('a.student_id', $student->id)
                ->where('a.product_id', $request->product_id)
                ->where('a.id', $createHd->id)
                ->where('a.deleted_at', null)
                ->where('b.deleted_at', null)
                ->groupBy('a.totalamount', 'a.quotation_no')
                ->first();

            $total = $chkamount->amount;
            $totalnet = $request->grandTotal;
            if ($total == $totalnet) {
            } else {
                ApprovalRequest::create([
                    'reference_id' => $student->id,
                    'description' => $createHd->id,
                    'remarks' => 'quotation',
                    'status' => null,
                    'user_id' => Auth::id()
                ]);
            }

            return back()->with('success', 'Quotation(s) created successfully.');
        } else {
            return back()->withErrors([
                'purposeID' => 'Unable to generate Student ID.',
            ]);
        }
    }

    public function confirm(Student $student, Product $product, Request $request)
    {

        $chkamount = DB::table('student_quotation_h_d_s as a')
            ->select(
                'a.totalamount',
                'a.quotation_no',
                DB::raw('SUM(b.netamount) as amount')
            )
            ->leftJoin('product_fees_hds as b', 'a.product_id', '=', 'b.product_id')
            ->where('a.student_id', $student->id)
            ->where('a.product_id', $product->id)
            ->where('a.id', $request->status)
            ->where('a.deleted_at', null)
            ->where('b.deleted_at', null)
            ->groupBy('a.totalamount', 'a.quotation_no')
            ->first();
        if (!$chkamount) {
            return back()->with([
                'success' => false,
                'message' => 'Quotation not found.'
            ]);
        }
        $chkamoun_without_product = StudentQuotationHD::select('totalamount')->where('student_id', $student->id)->where('product_id', $product->id)->where('id', $request->status)->first();

        $total = $chkamount->totalamount;
        $totalnet = $chkamount->amount;

        if ($total == $totalnet && $totalnet == $chkamoun_without_product->totalamount) {
            if (auth()->user()->can('StudQuoat.confirm')) {
                // Log activity
                StudentActivities::create([
                    'student_id' => $student->id,
                    'title' => "has approved student quotation's",
                    'fristactivity' => null,
                    'lastactivity' => null,
                    'user_id' => Auth::id()
                ]);

                // Update quotation
                DB::table('student_quotation_h_d_s')->where('id', $request->status)
                    ->update(['active' => 1]);

                return back()->with([
                    'success' => true,
                    'message' => 'Quotation confirmed successfully.'
                ]);
            } else {
                return back()->with([
                    'success' => true,
                    'message' => 'Unauthorized: You cannot confirm this quotation.'
                ]);
            }
        }
        // Case 2: total != totalnet
        else {
            if (auth()->user()->can('StudQuoat.approval')) {
                // Log activity
                StudentActivities::create([
                    'student_id' => $student->id,
                    'title' => "has approved quotation with amount mismatch",
                    'fristactivity' => null,
                    'lastactivity' => null,
                    'user_id' => Auth::id()
                ]);

                // Update quotation
                DB::table('student_quotation_h_d_s')->where('id', $request->status)
                    ->update(['active' => 1]);

                return back()->with([
                    'success' => true,
                    'message' => 'Amount mismatch. Approval confirmed.'
                ]);
            } else {
                return back()->with([
                    'success' => true,
                    'message' => 'Unauthorized: You cannot approve this quotation.'
                ]);
            }
        }
    }


    public function destory(Student $student, Product $product, Request $request)
    {

        try {
            $this->authorize('StudQuoat.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $chk = ApprovalRequest::where('reference_id', $student->id)->where('description', $request->status)->exists();
        if ($chk) {
            return back()->with([
                'error' => true,
                'message' => 'Quotations cancel not working approval pending'
            ]);
        }
        DB::table('student_quotation_h_d_s')->where('id', $request->status)
            ->update(['active' => 2]);
        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has cancel student quotation's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        return back()->with([
            'success' => true,
            'message' => 'Quotation cancel successfully.',
        ]);
    }


    // PDF Export
    public function exportPdfGeneral(Student $student, Product $product, StudentQuotationHD $quoatation)
    {
        try {
            $this->authorize('StudQuoat.report');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has show student quotation's reports",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        $student->load('country');
        $company = CompanyInfo::firstOrFail();
        $service = DB::table('student_in_services as a')
            ->leftJoin('workflows as b', 'a.workflow_id', '=', 'b.id')
            ->leftJoin('partner_branches as c', 'a.partner_branch_id', '=', 'c.id')
            ->leftJoin('partners as d', 'c.partner_id', '=', 'd.id')
            ->leftJoin('products as f', 'a.product_id', '=', 'f.id')
            ->select(
                'b.name as workflow',
                'd.name as partner',
                'c.branch_name as partnerbranch',
                'f.name as product'
            )
            ->where('a.student_id', $student->id)
            ->where('a.product_id', $product->id)
            ->first();

        $fees = DB::table('student_quotation_h_d_s as a')
            ->leftJoin('student_quoation_fees as b', 'a.id', '=', 'b.quotation_hd_id')
            ->leftJoin('student_invoice_hd as c', 'a.quotation_no', '=', 'c.refe_code')
            ->leftJoin('student_invoices_dt as d', function ($join) {
                $join->on('c.id', '=', 'd.invoice_hd_id')
                    ->on('b.fee_id', '=', 'd.fees_id');
            })
            ->leftJoin('student_money_receipt_d_t_s as e', function ($join) {
                $join->on('c.id', '=', 'e.insnumber_id')
                    ->on('b.fee_id', '=', 'e.fees_id');
            })
            ->leftJoin('fees as f', 'b.fee_id', '=', 'f.id')
            ->where('a.quotation_no', $quoatation->quotation_no)
            ->groupBy('b.fee_id', 'b.amount', 'b.quaotamount', 'b.paytype', 'f.name')
            ->selectRaw("
                    f.name,
                    b.amount,
                    b.quaotamount,
                    b.paytype,
                    SUM(d.amount) as invoicetotal,
                    (COALESCE(b.amount, 0) + COALESCE(SUM(d.amount), 0) + COALESCE(SUM(e.amount),0)) as totalamount
                ")
            ->get();


        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $pdf = Pdf::loadView('exports.studentQuatation', [
            'student' => $student,
            'quatHd' => $quoatation,
            'company' => $company,
            'service' => $service,
            'fees' => $fees,
            'numberTransformer' => $numberTransformer
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 10,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 10,
            ]);;

        return $pdf->stream("Quotation_{$quoatation->quotation_no}.pdf");
    }

    public function exportPdfApproved(Student $student, Product $product, StudentQuotationHD $quoatation)
    {
        
        try {
            $this->authorize('StudQuoat.ApprovedReport');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has show student quotation's reports without approval",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        $student->load('country');
        $company = CompanyInfo::firstOrFail();
        $service = DB::table('student_in_services as a')
            ->leftJoin('workflows as b', 'a.workflow_id', '=', 'b.id')
            ->leftJoin('partner_branches as c', 'a.partner_branch_id', '=', 'c.id')
            ->leftJoin('partners as d', 'c.partner_id', '=', 'd.id')
            ->leftJoin('products as f', 'a.product_id', '=', 'f.id')
            ->select(
                'b.name as workflow',
                'd.name as partner',
                'c.branch_name as partnerbranch',
                'f.name as product'
            )
            ->where('a.student_id', $student->id)
            ->where('a.product_id', $product->id)
            ->first();
        $fees = DB::table('student_quotation_h_d_s as a')
            ->leftJoin('student_quoation_fees as b', 'a.id', '=', 'b.quotation_hd_id')
            ->leftJoin('student_invoice_hd as c', 'a.quotation_no', '=', 'c.refe_code')
            ->leftJoin('student_invoices_dt as d', function ($join) {
                $join->on('c.id', '=', 'd.invoice_hd_id')
                    ->on('b.fee_id', '=', 'd.fees_id');
            })
            ->leftJoin('student_money_receipt_d_t_s as e', function ($join) {
                $join->on('c.id', '=', 'e.insnumber_id')
                    ->on('b.fee_id', '=', 'e.fees_id');
            })
            ->leftJoin('fees as f', 'b.fee_id', '=', 'f.id')
            ->where('a.quotation_no', $quoatation->quotation_no)
            ->groupBy('b.fee_id', 'b.amount', 'b.quaotamount', 'b.paytype', 'f.name')
            ->selectRaw("
                    f.name,
                    b.amount,
                    b.quaotamount,
                    b.paytype,
                    SUM(d.amount) as invoicetotal,
                    (COALESCE(b.amount, 0) + COALESCE(SUM(d.amount), 0) + COALESCE(SUM(e.amount),0)) as totalamount
                ")
            ->get();
        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $pdf = Pdf::loadView('exports.studentQuatationApproval', [
            'student' => $student,
            'quatHd' => $quoatation,
            'company' => $company,
            'service' => $service,
            'fees' => $fees,
            'numberTransformer' => $numberTransformer
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 10,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 10,
            ]);;

        return $pdf->stream("Quotation_{$quoatation->quotation_no}.pdf");
    }
}
