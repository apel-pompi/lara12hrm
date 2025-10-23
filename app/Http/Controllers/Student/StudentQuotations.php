<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Default\Transaction;
use App\Models\HRM\CompanyInfo;
use App\Models\Product\Product;
use App\Models\Student\Student;
use App\Models\Student\StudentActivities;
use App\Models\Student\StudentInService;
use App\Models\Student\StudentQuoationFee;
use App\Models\Student\StudentQuotation;
use App\Models\Student\StudentQuotationHD;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
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
        $this->authorize('StudQuoat.index');

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
                ->groupBy('a.id', 'a.product_id', 'b.name', 'd.name', 'c.branch_name', 'f.name', 'a.status')
                ->get(),
            'quoatation' => StudentQuotationHD::where('student_id', $student->id)->get(),
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

        $this->authorize('StudQuoat.store');

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
            ->groupBy('a.totalamount', 'a.quotation_no')
            ->first();
        if (!$chkamount) {
            return back()->with([
                'success' => false,
                'message' => 'Quotation not found.'
            ]);
        }


        $total = $chkamount->totalamount;
        $totalnet = $chkamount->amount;


        if ($total == $totalnet) {
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
                abort(403, 'Unauthorized: You cannot confirm this quotation.');
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
                abort(403, 'Unauthorized: You cannot approve this quotation.');
            }
        }
    }


    public function destory(Student $student, Product $product, Request $request)
    {
        $this->authorize('StudQuoat.destroy');
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
        $this->authorize('StudQuoat.report');

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
            ->leftJoin('fees as c', 'b.fee_id', '=', 'c.id')
            ->leftJoin('product_fees_hds as d', 'a.product_id', '=', 'd.product_id')
            ->leftJoin('product_fees_dts as e', 'd.id', '=', 'e.fees_hd_id')
            ->select(
                'c.name',
                'b.amount as quoatamount',
                'e.totalamount as pamount',
                'e.pay_type'
            )
            ->where('a.id', $quoatation->id)
            ->where('a.student_id', $student->id)
            ->where('a.product_id', $product->id)
            ->where('a.active', 1)
            ->whereColumn('b.fee_id', '=', 'e.fees_id')
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
}
