<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\HRM\CompanyInfo;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StudentReportController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $user = Auth::user();
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

    public function MonthlyLeadReport($year, $month)
    {


        return response()->json([
            'year' => $year,
            'month' => $month,
            'message' => 'Monthly Lead Report Loaded Successfully'
        ]);
    }

    public function MonthlyEmpLeadReport($year, $month, $employee = null)
    {
        $company = CompanyInfo::first();
        $pdf = PDF::loadView('exports.MonthlyEmpLeadReport', [
            'year' => $year,
            'month' => $month,
            'employee' => $employee,
            'company' => $company
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
