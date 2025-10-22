<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Default\Transaction;
use App\Models\HRM\CompanyInfo;
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
            'studentService' => StudentInService::with(['workflow', 'partnerBranch.partner', 'product', 'productfees.details.fees'])->where('student_id', $student->id)->get(),
            'studentquoatation' => StudentQuotationHD::with(['deatils', 'user'])->where('student_id', $student->id)->get()
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

    public function generalStore(Student $student, Request $request)
    {

        $this->authorize('StudQuoat.store');

        $QuaotNo = $this->GetInvoiceNO();
        if ($QuaotNo) {
            $validated = $request->validate([
                'service_ids' => 'required|array',
                'service_ids.*' => 'exists:student_in_services,id',
                'fees' => 'required|array',
                'fees.*.id' => 'required|exists:fees,id',
                'fees.*.amount' => 'required|numeric|min:0',
                'fees.*.product_id' => 'required|exists:products,id',
                'note' => 'nullable|string|max:500',
            ]);

            $createHd = StudentQuotationHD::create([
                'quotation_no' => $QuaotNo,
                'student_id' => $student->id,
                'sumamount' => $request->amount,
                'notes' => $request->note,
                'status' => 0,
                'adddate' => date('y-m-d'),
                'user_id' => auth()->id(),
                'active' => 0,
            ]);
            if ($createHd) {
                foreach ($validated['service_ids'] as $serviceId) {
                    StudentQuotation::create([
                        'quotation_hd_id' => $createHd->id,
                        'service_id' => $serviceId,
                        'user_id' => auth()->id(),
                    ]);
                }
                foreach ($validated['fees'] as $value) {
                    StudentQuoationFee::create([
                        'student_id' => $student->id,
                        'quotation_hd_id' => $createHd->id,
                        'fee_id' => $value['id'],
                        'product_id' => $value['product_id'],
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

    public function confirmGeneral(Request $request, $confirm)
    {
        
        $this->authorize('StudQuoat.confirm');
        $quotation = StudentQuotationHD::findOrFail($request->active);

        $fees = StudentQuoationFee::with('productfee')
            ->where('quotation_hd_id', $quotation->id)
            ->get();

        $totalFeeAmount = $fees->sum('amount');
        $productNetAmount = 0;

        foreach ($fees as $fee) {
            if ($fee->productfee) {
                $productNetAmount = $fee->productfee->netamount;
                break;
            }
        }
        StudentActivities::create([
            'student_id' => $confirm,
            'title' => "has confirmed student quotation's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        // Amount check (floating point safe)
        if ($totalFeeAmount == $productNetAmount) {
            $this->authorize('StudQuoat.confirm');
            $quotation->update(['active' => 1]);
            return back()->with('success', 'Quotation confirmed successfully.');
        } else {
            // approval 
            $this->authorize('StudQuoat.approval');
            $quotation->update(['active' => 1]);

            return back()->with('success', 'Amount mismatch. Approval confirmed.');
        }
    }


    public function generalDelete(Request $request, $confirm)
    {
        $this->authorize('StudQuoat.destroy');

        $quotation = StudentQuotationHD::findOrFail($request->active);
        StudentQuotation::where('quotation_hd_id', $quotation->id)->delete();

        StudentActivities::create([
            'student_id' => $confirm,
            'title' => "has deleted student quotation's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        $quotation->delete();

        return back()->with([
            'success' => true,
            'message' => 'Quotation deleted successfully.',
        ]);
    }


    // PDF Export
    public function exportPdfGeneral(Student $student, StudentQuotationHD $quoatation)
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
        $quatHd = StudentQuotationHD::with(['deatils', 'user'])->where('quotation_no', $quoatation->quotation_no)->get();
        $feesDetails = StudentQuotationHD::with(['deatils.service.product', 'deatils.service.partnerBranch.partner'])->where('id', $quoatation->id)->where('student_id', $student->id)->get();

        $feename = DB::table('student_quoation_fees', 'a')
            ->select('fees.name', 'a.amount', 'product_fees_dts.pay_type')
            ->leftJoin('fees', 'a.fee_id', '=', 'fees.id')
            ->leftJoin('product_fees_dts', 'a.product_id', '=', 'product_fees_dts.id')
            ->where('a.quotation_hd_id', $quoatation->id)
            ->where('a.student_id', $student->id)
            ->get();

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $pdf = Pdf::loadView('exports.studentQuatation', [
            'quatHd' => $quatHd,
            'student' => $student,
            'company' => $company,
            'feesDetails' => $feesDetails,
            'feename' => $feename,
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
