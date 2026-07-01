<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\HRM\CompanyInfo;
use App\Models\HRM\PersonalInfo;
use App\Models\Student\Student;
use App\Models\Student\StudentInService;
use App\Models\User;
use App\Models\Student\StudentInvoiceHD;
use App\Models\Student\StudentQuotationHD;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use NumberToWords\NumberToWords;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class StudentReportController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        try {

            $this->authorize('leadReports.monthly-lead-info');
        } catch (AuthorizationException $e) {

            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        if ($roles->contains('superadmin')  or $roles->contains('Admin') or $roles->contains('Manager')) {

            return Inertia::render('allpages/reports/LeadInfoReport', [
                'months' => collect($this->createMonth())
                    ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                    ->values()
                    ->toArray(),
                'years' => collect($this->createYear())
                    ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                    ->values()
                    ->toArray(),
                'UsersWithRoles' => User::with('roles')->get(),
                'isAdmin' => true,
            ]);
        } else {
            return Inertia::render('allpages/reports/LeadInfoReport', [
                'months' => collect($this->createMonth())
                    ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                    ->values()
                    ->toArray(),
                'years' => collect($this->createYear())
                    ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                    ->values()
                    ->toArray(),
                'isAdmin' => false,
            ]);
        }
    }


    public function MonthlyEmpLeadReport($formdate, $todate, $isAdmin, $employee = null)
    {
        if ($isAdmin == true) {
            if ($employee == null) {
                $users = User::all();
                $dataArray = [];
                foreach ($users as $user) {
                    $person = PersonalInfo::where('empname', 'like', "%{$user->name}%")->first();
                    if (!$person) continue;

                    $statuses = Student::selectRaw('status, COUNT(*) as total')
                        ->where('assain_user', $user->id)
                        ->whereBetween(DB::raw('DATE(created_at)'), [$formdate, $todate])
                        ->groupBy('status')
                        ->pluck('total', 'status');

                    $sumQuoat = StudentQuotationHD::whereHas('student', function ($q) use ($user) {
                        $q->where('assain_user', $user->id);
                    })
                        ->whereBetween(DB::raw('DATE(adddate)'), [$formdate, $todate])
                        ->sum('totalamount');

                    $sumInvoice = StudentInvoiceHD::whereHas('student', function ($q) use ($user) {
                        $q->where('assain_user', $user->id);
                    })
                        ->whereBetween(DB::raw('DATE(insdate)'), [$formdate, $todate])
                        ->where('status', 'Confirmed')
                        ->whereRaw("LEFT(insnumber, 4) = 'INV-'")
                        ->sum('netamount');

                    $sumMR = Student::leftJoin('student_invoice_hd as b', 'students.id', '=', 'b.student_id')
                        ->where('students.assain_user', $user->id)
                        ->whereBetween(DB::raw('DATE(b.insdate)'), [$formdate, $todate])
                        ->whereRaw("LEFT(b.insnumber, 4) = 'MR--'")
                        ->whereRaw("LEFT(b.refe_code, 4) <> 'SR--'")
                        ->where('b.status', 'Confirmed')
                        ->sum(DB::raw('b.netamount'));

                    $sumRefundInvoice = StudentInvoiceHD::whereHas('student', function ($q) use ($user) {
                        $q->where('assain_user', $user->id);
                    })
                        ->whereBetween(DB::raw('DATE(insdate)'), [$formdate, $todate])
                        ->where('status', 'Confirmed')
                        ->whereRaw("LEFT(insnumber, 4) = 'SR--'")
                        ->sum('netamount');

                    $sumRefundMR = Student::leftJoin('student_invoice_hd as b', 'students.id', '=', 'b.student_id')
                        ->where('students.assain_user', $user->id)
                        ->whereBetween(DB::raw('DATE(b.insdate)'), [$formdate, $todate])
                        ->whereRaw("LEFT(b.insnumber, 4) = 'MR--'")
                        ->whereRaw("LEFT(b.refe_code, 4) = 'SR--'")
                        ->where('b.status', 'Confirmed')
                        ->sum(DB::raw('b.netamount'));

                    $dataArray[] = [
                        'employee' => $person->empname,
                        'statuses' => $statuses,
                        'sumQuoat' => $sumQuoat,
                        'sumInvoice' => $sumInvoice,
                        'sumMR' => $sumMR,
                        'sumRefundInvoice' => $sumRefundInvoice,
                        'sumRefundMR' => $sumRefundMR,
                    ];
                }
            } else {
                $user = User::find($employee);
                $person = PersonalInfo::where('empname', 'like', "%{$user->name}%")->first();
                $statuses = Student::selectRaw('status, COUNT(*) as total')
                    ->where('assain_user', $user->id)
                    ->whereBetween(DB::raw('DATE(created_at)'), [$formdate, $todate])
                    ->groupBy('status')
                    ->pluck('total', 'status');
                $sumQuoat = StudentQuotationHD::whereHas('student', function ($q) use ($user) {
                    $q->where('assain_user', $user->id);
                })
                    ->whereBetween(DB::raw('DATE(adddate)'), [$formdate, $todate])
                    ->sum('totalamount');

                $sumInvoice = StudentInvoiceHD::whereHas('student', function ($q) use ($user) {
                    $q->where('assain_user', $user->id);
                })
                    ->whereBetween(DB::raw('DATE(insdate)'), [$formdate, $todate])
                    ->where('status', 'Confirmed')
                    ->whereRaw("LEFT(insnumber, 4) = 'INV-'")
                    ->sum('netamount');

                $sumMR = Student::leftJoin('student_invoice_hd as b', 'students.id', '=', 'b.student_id')
                    ->where('students.assain_user', $user->id)
                    ->whereBetween(DB::raw('DATE(b.insdate)'), [$formdate, $todate])
                    ->whereRaw("LEFT(b.insnumber, 4) = 'MR--'")
                    ->whereRaw("LEFT(b.refe_code, 4) <> 'SR--'")
                    ->where('b.status', 'Confirmed')
                    ->sum(DB::raw('b.netamount'));

                $sumRefundInvoice = StudentInvoiceHD::whereHas('student', function ($q) use ($user) {
                    $q->where('assain_user', $user->id);
                })
                    ->whereBetween(DB::raw('DATE(insdate)'), [$formdate, $todate])
                    ->where('status', 'Confirmed')
                    ->whereRaw("LEFT(insnumber, 4) = 'SR--'")
                    ->sum('netamount');

                $sumRefundMR = Student::leftJoin('student_invoice_hd as b', 'students.id', '=', 'b.student_id')
                    ->where('students.assain_user', $user->id)
                    ->whereBetween(DB::raw('DATE(b.insdate)'), [$formdate, $todate])
                    ->whereRaw("LEFT(b.insnumber, 4) = 'MR--'")
                    ->whereRaw("LEFT(b.refe_code, 4) = 'SR--'")
                    ->where('b.status', 'Confirmed')
                    ->sum(DB::raw('b.netamount'));

                $dataArray[] = [
                    'employee' => $person->empname,
                    'statuses' => $statuses,
                    'sumQuoat' => $sumQuoat,
                    'sumInvoice' => $sumInvoice,
                    'sumMR' => $sumMR,
                    'sumRefundInvoice' => $sumRefundInvoice,
                    'sumRefundMR' => $sumRefundMR,
                ];
            }
        } else {
            $user = User::find($employee);
            $person = PersonalInfo::where('empname', 'like', "%{$user->name}%")->first();
            $statuses = Student::selectRaw('status, COUNT(*) as total')
                ->where('assain_user', $user->id)
                ->whereBetween(DB::raw('DATE(created_at)'), [$formdate, $todate])
                ->groupBy('status')
                ->pluck('total', 'status');
            $sumQuoat = StudentQuotationHD::whereHas('student', function ($q) use ($user) {
                $q->where('assain_user', $user->id);
            })
                ->whereBetween(DB::raw('DATE(adddate)'), [$formdate, $todate])
                ->sum('totalamount');

            $sumInvoice = StudentInvoiceHD::whereHas('student', function ($q) use ($user) {
                $q->where('assain_user', $user->id);
            })
                ->whereBetween(DB::raw('DATE(insdate)'), [$formdate, $todate])
                ->where('status', 'Confirmed')
                ->whereRaw("LEFT(insnumber, 4) = 'INV-'")
                ->sum('netamount');

            $sumMR = Student::leftJoin('student_invoice_hd as b', 'students.id', '=', 'b.student_id')
                ->where('students.assain_user', $user->id)
                ->whereBetween(DB::raw('DATE(b.insdate)'), [$formdate, $todate])
                ->whereRaw("LEFT(b.insnumber, 4) = 'MR--'")
                ->whereRaw("LEFT(b.refe_code, 4) <> 'SR--'")
                ->where('b.status', 'Confirmed')
                ->sum(DB::raw('b.netamount'));

            $sumRefundInvoice = StudentInvoiceHD::whereHas('student', function ($q) use ($user) {
                $q->where('assain_user', $user->id);
            })
                ->whereBetween(DB::raw('DATE(insdate)'), [$formdate, $todate])
                ->where('status', 'Confirmed')
                ->whereRaw("LEFT(insnumber, 4) = 'SR--'")
                ->sum('netamount');

            $sumRefundMR = Student::leftJoin('student_invoice_hd as b', 'students.id', '=', 'b.student_id')
                ->where('students.assain_user', $user->id)
                ->whereBetween(DB::raw('DATE(b.insdate)'), [$formdate, $todate])
                ->whereRaw("LEFT(b.insnumber, 4) = 'MR--'")
                ->whereRaw("LEFT(b.refe_code, 4) = 'SR--'")
                ->where('b.status', 'Confirmed')
                ->sum(DB::raw('b.netamount'));

            $dataArray[] = [
                'employee' => $person->empname,
                'statuses' => $statuses,
                'sumQuoat' => $sumQuoat,
                'sumInvoice' => $sumInvoice,
                'sumMR' => $sumMR,
                'sumRefundInvoice' => $sumRefundInvoice,
                'sumRefundMR' => $sumRefundMR,
            ];
        }

        $pdf = PDF::loadView('exports.MonthlyEmpLeadReport', [
            'formdate' => $formdate,
            'todate' => $todate,
            'dataArray' => $dataArray,
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("MonthlyEmpLeadReport.pdf");
    }

    public function studentTransaction()
    {

        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        try {

            $this->authorize('leadReports.student-transaction');
        } catch (AuthorizationException $e) {

            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        if ($roles->contains('superadmin')  or $roles->contains('Admin') or $roles->contains('Manager')) {

            return Inertia::render('allpages/reports/studentTransaction', [
                'student' => Student::where('student_id', '<>', null)->get(),
            ]);
        } else {

            return Inertia::render('allpages/reports/studentTransaction', [

                'student' => Student::where('student_id', '<>', null)->where('assain_user', Auth::id())->get(),
            ]);
        }
    }


    public function studentTransactionReport($student)
    {

        $data = Student::find($student);

        $data->load('country');
        $company = CompanyInfo::firstOrFail();
        $service = StudentInService::with(['workflow', 'partnerBranch.partner', 'product', 'productfees'])->where('student_id', $data->id)->where('status', 'converted')->get();

        $productIds = $service->pluck('product_id')->unique()->toArray();
        $quotationFeesHd = StudentQuotationHD::with(['user'])
            ->where('student_id', $data->id)
            ->whereIn('product_id', $productIds)
            ->get();

        $allQuotationFees = collect();

        foreach ($quotationFeesHd as $quotation) {
            $fees = $this->getQuoation($quotation->id);
            $fees->each(function ($fee) use ($quotation) {
                $fee->quotation_id = $quotation->id;
                $fee->quotation_no = $quotation->quotation_no;
            });
            $allQuotationFees = $allQuotationFees->merge($fees);
        }

        $allInvoiceFees = DB::table('student_invoice_hd as a')
            ->leftJoin('student_invoices_dt as b', 'a.id', '=', 'b.invoice_hd_id')
            ->leftJoin('student_money_receipt_d_t_s as c', function ($join) {
                $join->on('a.id', '=', 'c.insnumber_id')
                    ->on('b.fees_id', '=', 'c.fees_id');
            })
            ->leftJoin('fees as e', 'b.fees_id', '=', 'e.id')
            ->where('a.student_id', $data->id)
            ->whereRaw("LEFT(a.insnumber, 4) = 'INV-'")
            ->where('a.status', 'Confirmed')
            ->groupBy('a.id', 'a.insnumber', 'a.disc_amt', 'a.insdate', 'a.refe_code', 'b.fees_id', 'b.amount', 'e.name')
            ->selectRaw("
                    a.id as invoice_hd_id,
                    a.insnumber as invoice_no,
                    a.insdate as invoice_date,
                    a.disc_amt as disc_amt,
                    a.refe_code as refe_code,
                    e.name as fee_name,
                    b.amount as invoice_amount,
                    COALESCE(SUM(c.amount), 0) as receipt_amount,
                    (b.amount + COALESCE(SUM(c.amount), 0)) as total_amount
                ")
            ->orderBy('a.insnumber', 'ASC')
            ->get();


        $invoicesGrouped = $allInvoiceFees->groupBy('invoice_hd_id');

        $allInvoiceReturnFees = DB::table('student_invoice_hd as a')
            ->leftJoin('student_invoices_dt as b', 'a.id', '=', 'b.invoice_hd_id')
            ->leftJoin('student_money_receipt_d_t_s as c', function ($join) {
                $join->on('a.id', '=', 'c.insnumber_id')
                    ->on('b.fees_id', '=', 'c.fees_id');
            })
            ->leftJoin('fees as e', 'b.fees_id', '=', 'e.id')
            ->where('a.student_id', $data->id)
            ->whereRaw("LEFT(a.insnumber, 4) = 'SR--'")
            ->where('a.status', 'Confirmed')
            ->groupBy('a.id', 'a.insnumber', 'a.disc_amt', 'a.insdate', 'a.refe_code', 'b.fees_id', 'b.amount', 'e.name')
            ->selectRaw("
                    a.id as invoice_hd_id,
                    a.insnumber as invoice_no,
                    a.insdate as invoice_date,
                    a.disc_amt as disc_amt,
                    a.refe_code as refe_code,
                    e.name as fee_name,
                    b.amount as invoice_amount,
                    COALESCE(SUM(c.amount), 0) as receipt_amount,
                    (b.amount + COALESCE(SUM(c.amount), 0)) as total_amount
                ")
            ->orderBy('a.insnumber', 'ASC')
            ->get();
        $invoicesReturnGrouped = $allInvoiceReturnFees->groupBy('invoice_hd_id');

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $pdf = PDF::loadView('exports.studentTransaction', [
            'student' => $data,
            'company' => $company,
            'service' => $service,
            'quotationFeesHd' => $quotationFeesHd,
            'quotationFeesDt' => $allQuotationFees,
            'invoicesGrouped' => $invoicesGrouped,
            'invoicesReturnGrouped' => $invoicesReturnGrouped,
            'numberTransformer' => $numberTransformer
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("studentledger.pdf");
    }

    public function studentLedger()
    {

        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        try {

            $this->authorize('leadReports.student-ladger');
        } catch (AuthorizationException $e) {

            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        if ($roles->contains('superadmin')  or $roles->contains('Admin') or $roles->contains('Manager')) {

            return Inertia::render('allpages/reports/studentLedger', [
                'student' => Student::where('student_id', '<>', null)->get(),
            ]);
        } else {

            return Inertia::render('allpages/reports/studentLedger', [

                'student' => Student::where('student_id', '<>', null)->where('assain_user', Auth::id())->get(),
            ]);
        }
    }

    public function studentLedgerReport($student)
    {

        $data = Student::find($student);

        $data->load('country');
        $company = CompanyInfo::firstOrFail();
        $query = StudentInvoiceHD::with([
            'mrdetails.fees',
        ])
            ->where('student_id', $data->id)
            ->whereRaw("LEFT(insnumber, 4) = 'MR--'")
            ->where('status', 'Confirmed')
            ->get();

        $values = [];
        foreach ($query as $invoice) {
            $mrdate = $invoice->insdate;
            $mrno = $invoice->insnumber;
            $mrstatus = $invoice->note;
            foreach ($invoice->mrdetails as $mr) {
                $values[] = [
                    'mrdate' => $mrdate ?? '',
                    'feesname' => $mr->fees->name ?? '',
                    'mrno' => $mrno ?? '',
                    'mrstatus' => $mrstatus ?? '',
                    'primeamt' => $mr->amount,
                ];
            }
        }

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');
        $dataCollection = collect($values);
        $pdf = PDF::loadView('exports.studentledger', [
            'student' => $data,
            'company' => $company,
            'data'    => $dataCollection,
            'numberTransformer' => $numberTransformer
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("studentLedger.pdf");
    }

    protected function getQuoation($quoat)
    {
        $quoation = DB::table('student_quotation_h_d_s as a')
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
            ->where('a.id', $quoat)
            ->where('a.active', 1)
            ->groupBy('b.fee_id', 'b.amount', 'b.paytype', 'f.name', 'a.id')
            ->selectRaw("
                    f.name,
                    b.amount as quoatamount,
                    b.paytype,
                    SUM(d.amount) as invoicetotal,
                    (COALESCE(b.amount, 0) + COALESCE(SUM(d.amount), 0) + COALESCE(SUM(e.amount),0)) as totalamount,
                    a.id as quotation_id
                ")
            ->get();
        return $quoation;
    }



    public function studentRevenue()
    {
        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        try {

            $this->authorize('leadReports.student-revenue');
        } catch (AuthorizationException $e) {

            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        if ($roles->contains('superadmin')  or $roles->contains('Admin') or $roles->contains('Manager')) {

            return Inertia::render('allpages/reports/studentRevenue', [
                'UsersWithRoles' => User::with('roles')->get(),
                'isAdmin' => true,
            ]);
        } else {

            return Inertia::render('allpages/reports/studentRevenue', [
                'isAdmin' => false,
            ]);
        }
    }

    public function studentRevenueReport($formdate, $todate, $isAdmin, $employee = null)
    {

        $query = StudentInvoiceHD::with(['student.service'])
            ->where('status', 'Confirmed')
            ->whereBetween('insdate', [$formdate, $todate]);
        if (! $isAdmin && $employee) {
            $query->whereHas('student', function ($q) use ($employee) {
                $q->where('assain_user', $employee->id);
            });
        }
        $records = $query->get();
        $grouped = $records->groupBy('student_id');
        dd($grouped);
        $totalStudents = 0;
        $totalInvoiced = 0;
        $totalReceived = 0;
        foreach ($grouped as $studentId => $rows) {
            $invoice = $rows->filter(
                fn($r) =>
                str_starts_with($r->insnumber, 'INV-') && $r->sign == 1
            )->sum('netamount');

            $receive = $rows->filter(
                fn($r) =>
                str_starts_with($r->insnumber, 'MR--') && $r->sign == -1 && $r->note <> 'REFUND'
            )->sum('netamount');

            if ($invoice == 0 || $receive == 0) {
                continue;
            }

            $totalStudents++;
            $totalInvoiced += $invoice;
            $totalReceived += $receive;
        }
        $totalDue = $totalInvoiced - $totalReceived;

        $company = CompanyInfo::firstOrFail();

        $authUser = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $authUser */
        $roles = $authUser->getRoleNames();

        $personalinfo = null;
        $targetUserId = null;
        if ($roles->intersect(['superadmin', 'Admin', 'Manager'])->isNotEmpty()) {
            if ($employee) {
                $targetUserId = $employee;
            }
        } else {
            $targetUserId = $authUser->id;
        }

        if ($targetUserId) {
            $targetUserName = User::where('id', $targetUserId)->value('name');
            $personalinfo = PersonalInfo::with('designation')->where('empname', $targetUserName)->first();
        }


        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');

        $pdf = PDF::loadView('exports.studentRevenue', [
            'company' => $company,
            'personalinfo' => $personalinfo,
            'totalStudents' => $totalStudents,
            'totalInvoiced' => $totalInvoiced,
            'totalReceived' => $totalReceived,
            'totalDue' => $totalDue,
            'grouped' => $grouped,
            'numberTransformer' => $numberTransformer
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("studentRevenue.pdf");
    }

    public function studentRefund()
    {
        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        try {

            $this->authorize('leadReports.student-refund');
        } catch (AuthorizationException $e) {

            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        if ($roles->contains('superadmin')  or $roles->contains('Admin') or $roles->contains('Manager')) {

            return Inertia::render('allpages/reports/studentRefund', [
                'UsersWithRoles' => User::with('roles')->get(),
                'isAdmin' => true,
            ]);
        } else {

            return Inertia::render('allpages/reports/studentRefund', [
                'isAdmin' => false,
            ]);
        }
    }

    public function studentRefundReport($formdate, $todate, $isAdmin, $employee = null)
    {

        $query = StudentInvoiceHD::with(['student.service.workflow'])
            ->where('status', 'Confirmed')
            ->whereBetween('insdate', [$formdate, $todate]);
        if (! $isAdmin && $employee) {
            $query->whereHas('student', function ($q) use ($employee) {
                $q->where('assain_user', $employee->id);
            });
        }
        $records = $query->get();
        $grouped = $records->groupBy('student_id');

        $totalStudents = 0;
        $totalInvoiced = 0;
        $totalReceived = 0;
        foreach ($grouped as $studentId => $rows) {
            $invoice = $rows->filter(
                fn($r) =>
                str_starts_with($r->insnumber, 'SR--') && $r->sign == 1
            )->sum('netamount');

            $receive = $rows->filter(
                fn($r) =>
                str_starts_with($r->insnumber, 'MR--') && $r->sign == -1 && $r->note == 'REFUND'
            )->sum('netamount');

            if ($invoice == 0 || $receive == 0) {
                continue;
            }

            $totalStudents++;
            $totalInvoiced += $invoice;
            $totalReceived += $receive;
        }
        $totalDue = $totalInvoiced - $totalReceived;

        $company = CompanyInfo::firstOrFail();

        $authUser = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $authUser */
        $roles = $authUser->getRoleNames();

        $personalinfo = null;
        $targetUserId = null;
        if ($roles->intersect(['superadmin', 'Admin', 'Manager'])->isNotEmpty()) {
            if ($employee) {
                $targetUserId = $employee;
            }
        } else {
            $targetUserId = $authUser->id;
        }

        if ($targetUserId) {
            $targetUserName = User::where('id', $targetUserId)->value('name');
            $personalinfo = PersonalInfo::with('designation')->where('empname', $targetUserName)->first();
        }

        $numberToWords = new NumberToWords();
        $numberTransformer = $numberToWords->getNumberTransformer('en');

        $pdf = PDF::loadView('exports.studentRefund', [
            'company' => $company,
            'personalinfo' => $personalinfo,
            'totalStudents' => $totalStudents,
            'totalInvoiced' => $totalInvoiced,
            'totalReceived' => $totalReceived,
            'totalDue' => $totalDue,
            'grouped' => $grouped,
            'numberTransformer' => $numberTransformer
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("studentRefund.pdf");
    }

    public function createMonth()
    {
        $a = array();
        for ($i = 1; $i <= 12; $i++) {
            $a[$i] = date("F", mktime(0, 0, 0, $i, $i));
        }
        return $a;
    }

    public function createYear()
    {
        $a = array();
        for ($i = date('Y'); $i >= date('Y') - 5; $i--) {
            $a[$i] = $i;
        }
        return $a;
    }
}
