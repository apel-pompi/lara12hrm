<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccount;
use App\Models\Accounts\GroupOne;
use App\Models\HRM\CompanyInfo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountsReportController extends Controller
{
    use AuthorizesRequests;
    
    public function index()
    {
        try {
            $this->authorize('accsetting.GroupOne');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/reports/accounts/index', [
            'accounts' => ChartOfAccount::where('active', 1)
                ->get()
                ->groupBy('accounttype'),
        ]);
    }

    public function chartOfAccountReport(Request $request)
    {

        $company = CompanyInfo::firstOrFail();

        $accountType = $request->input('accounttype');

        $groupOne = GroupOne::with([
            'GroupTwo.GroupThree.chartOfAccounts'
        ])
            ->where('active', 1)
            ->when($accountType, function ($q) use ($accountType) {
                $q->where('description', $accountType);
            })
            ->get()
            ->filter(function ($group) {
                return $group->GroupTwo
                    ->flatMap->GroupThree
                    ->flatMap->chartOfAccounts
                    ->isNotEmpty();
            })
            ->values();


        $pdf = PDF::loadView('exports.accounts.chartofaccounts', [
            'company' => $company,
            'accounts' => $groupOne,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("chartofaccounts.pdf");
    }
}
