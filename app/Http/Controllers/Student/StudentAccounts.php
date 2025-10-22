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
use NumberToWords\NumberToWords;
use Inertia\Inertia;

class StudentAccounts extends Controller
{
    use AuthorizesRequests;

    public function index(Student $student)
    {
        $student->load('assainuser');
        return Inertia::render('allpages/Agency/Student/accouts', [
            'student' => $student,
            'quoatation' => StudentQuotationHD::with(['user'])->where('student_id', $student->id)->where('active', 1)->get(),
            'invoice' => StudentInvoiceHD::with(['details'])->where('student_id', $student->id)->whereRaw("LEFT(insnumber, 4) = 'INV-'")->get(),
            'application' => StudentApplication::where('student_id',$student->id)->first()
        ]);
    }

    public function create(Student $student, StudentQuotationHD $accounts)
    {


        $details = StudentQuotationHD::with([
            'student.country',
            'deatils.service.product',
            'deatils.service.partnerBranch.partner',
            'user'
        ])
            ->where('id', $accounts->id)
            ->where('student_id', $student->id)
            ->first();

        $services = [];
        foreach ($details->deatils as $d) {
            $productId = $d->service->product->id ?? null;
            $fees = [];

            if ($productId) {
                $fees = StudentQuotationHD::getFees($accounts->id, $productId)
                    ->map(function ($item) {
                        return [
                            'fee_id' => $item->fee->id ?? '-',
                            'fee_name' => $item->fee->name ?? '-',
                            'amount' => $item->amount ?? 0,
                        ];
                    });
            }

            $services[] = [
                'partner' => $d->service->partnerBranch->partner->name ?? '-',
                'branch' => $d->service->partnerBranch->branch_name ?? '-',
                'product' => $d->service->product->name ?? '-',
                'product_id' => $d->service->product->id ?? '-',
                'fees' => $fees,
            ];
        }

        return response()->json([
            'success' => true,
            'student' => [
                'id' => $details->student->id,
                'fname' => $details->student->fname,
                'lname' => $details->student->lname,
                'gender' => $details->student->gender,
                'email' => $details->student->email,
                'phone' => $details->student->phone,
                'descountry_id' => $details->student->country->name,
            ],
            'quotation' => [
                'quotation_no' => $details->quotation_no,
                'adddate' => $details->adddate,
                'user' => $details->user->name ?? '-',
            ],
            'services' => $services,
        ]);
    }

    private function GetInvoiceNO()
    {
        $transaction = Transaction::where('name', 'Invoice No')
            ->where('active', 1)
            ->first(['trncode','lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }


    public function store(Student $student, StudentQuotationHD $accounts, Request $request)
    {
       
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
            'disc_amt' => $request->discount ?? 0,
            'netamount' => $netamt,
            'sign' => '1',
            'status' => 'pending',
            'refe_code' => $accounts->quotation_no,
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
                'product_id' => $fee['product_id'],
                'fees_id' => $fee['fee_id'],
                'amount' => $fee['amount']
            ]);

            $chkamount = StudentQuoationFee::where('student_id', $student->id)
                ->where('quotation_hd_id', $accounts->id)
                ->where('fee_id', $fee['fee_id'])
                ->where('product_id', $fee['product_id'])
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

        if (!$student || !$confirm) {
            return back()->with(['error' => true, 'message' => 'Invalid request']);
        }

        $invoice = StudentInvoiceHD::find($confirm->id);

        if (!$invoice) {
            return back()->with(['error' => true, 'message' => 'Invoice not found']);
        }

        if ($invoice->status !== 'pending') {
            return back()->with(['error' => true, 'message' => 'Only pending invoices can be cancelled']);
        }

        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has cancel student invoice's",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        $invoice->update(['status' => 'Cancel']);

        return back()->with(['success' => true, 'message' => 'Student Invoice cancelled successfully']);
    }

    public function onConfirm(Student $student, StudentInvoiceHD $confirm)
    {

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

        $this->authorize('StudQuoat.report');

        StudentActivities::create([
            'student_id' => $student->id,
            'title' => "has show student invoice's reports",
            'fristactivity' => null,
            'lastactivity' => null,
            'user_id' => Auth::id()
        ]);

        $student->load('country');
        $company = CompanyInfo::firstOrFail();
        $invoice = StudentInvoiceHD::with(['details.fee', 'user'])->where('insnumber', $confirm->insnumber)->first();
        if (!$invoice) {
            abort(404, 'Invoice not found');
        }

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');

        $pdf = Pdf::loadView('exports.studentInvoice', [
            'student' => $student,
            'company' => $company,
            'invoice' => $invoice,
            'numberTransformer' => $numberTransformer
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 10,
                'margin-right'  => 10,
                'margin-bottom' => 10,
                'margin-left'   => 10,
            ]);;

        return $pdf->stream("Invoice{$invoice->insnumber}.pdf");
    }
}
