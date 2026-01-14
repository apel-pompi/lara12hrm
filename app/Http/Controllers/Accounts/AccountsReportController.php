<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccount;
use App\Models\Accounts\GroupOne;
use App\Models\Accounts\VoucherBalance;
use App\Models\Accounts\Voucherheader;
use App\Models\Default\Transaction;
use App\Models\HRM\Branch;
use App\Models\HRM\CompanyInfo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use PhpParser\Node\Expr\Cast\Void_;

class AccountsReportController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        try {
            $this->authorize('accountsreport.index');
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
        try {
            $this->authorize('accountsreport.chartOfAccountReport');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

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

    public function ActoGL()
    {
        try {
            $this->authorize('accountsreport.ActoGL');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/reports/accounts/actogl', [
            'branch' => Branch::where('active', 1)->get(),
            'accounts' => ChartOfAccount::where('accountusage', '=', 'Ledger')->where('active', 1)->get(),
        ]);
    }

    public function ActoGLReport(Request $request)
    {

        try {
            $this->authorize('accountsreport.ActoGL');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $opening = VoucherBalance::with(['ChartOFAccount', 'branch'])
            ->where('voucherdate', '<', $request->startdate)
            ->where('status', 'Post')
            ->whereRaw("LEFT(vouchernumber, 4) = 'OB--'")
            ->where('accountcode', $request->account)
            ->when($request->filled('branch_id'), function ($q) use ($request) {
                $q->where('voucher_balances.branch_id', $request->branch_id);
            })
            ->select(
                'accountcode',
                'branch_id',
                'currency',
                DB::raw('COALESCE(SUM(primeamt), 0) as opening')
            )
            ->groupBy('accountcode', 'branch_id', 'currency')
            ->first();
        if (!$opening) {
            $opening = VoucherBalance::with(['ChartOFAccount', 'branch'])
                ->where('accountcode', $request->account)
                ->when($request->filled('branch_id'), function ($q) use ($request) {
                    $q->where('branch_id', $request->branch_id);
                })
                ->select(
                    'accountcode',
                    'branch_id',
                    DB::raw("'BDT' as currency"),
                    DB::raw('0 as opening')
                )
                ->first();
        }


        $voucher = VoucherBalance::with('branch')
            ->whereBetween('voucherdate', [$request->startdate, $request->enddate])
            ->where('accountcode', $request->account)
            ->where('status', 'Post')

            ->when($request->filled('branch_id'), function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            })
            ->orderBy('voucherdate', 'asc')
            ->get();

        $company = CompanyInfo::firstOrFail();
        $pdf = PDF::loadView('exports.accounts.actogl', [
            'company' => $company,
            'opening' => $opening,
            'voucher' => $voucher,
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("AcToGl-Reports.pdf");
    }

    public function CashBook()
    {
        try {
            $this->authorize('accountsreport.CashBook');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/reports/accounts/cashbook', [
            'branch' => Branch::where('active', 1)->get(),
        ]);
    }

    public function CashBookReport(Request $request)
    {
        $opening = VoucherBalance::query()
            ->join('chart_of_accounts as coa', 'voucher_balances.accountcode', '=', 'coa.accountcode')
            ->join('branches as b', 'voucher_balances.branch_id', '=', 'b.id')
            ->where('coa.accountusage', 'Ledger')
            ->where('coa.analyticalcode', 'Cash') // Ledger – Cash
            ->where('voucher_balances.voucherdate', '<', $request->startdate)
            ->where('voucher_balances.status', 'Post')
            ->where('voucher_balances.primeamt', '>', 0)
            ->whereRaw("LEFT(voucher_balances.vouchernumber, 4) = 'OB--'")
            ->when($request->filled('branch_id'), function ($q) use ($request) {
                $q->where('voucher_balances.branch_id', $request->branch_id);
            })
            ->select(
                'b.branchname',
                DB::raw('COALESCE(SUM(voucher_balances.primeamt), 0) as opening')
            )
            ->groupBy('b.branchname')
            ->first();




        $cashBook = VoucherBalance::query()
            ->with(['voucherHeader', 'branch'])
            ->join('chart_of_accounts as coa', 'voucher_balances.accountcode', '=', 'coa.accountcode')
            ->join('voucherheaders as vh', 'voucher_balances.vouchernumber', '=', 'vh.vouchernumber')
            ->whereBetween('voucher_balances.voucherdate', [
                $request->startdate,
                $request->enddate
            ])
            ->where('voucher_balances.status', 'Post')
            ->where('vh.status', 'Posted')
            ->where('coa.accountusage', 'Ledger')
            ->where('coa.analyticalcode', 'Cash')   // Only Cash & Bank
            ->when($request->filled('branch_id'), function ($q) use ($request) {
                $q->where('voucher_balances.branch_id', $request->branch_id);
            })
            ->select(
                'voucher_balances.voucherdate',
                'voucher_balances.vouchernumber',
                'voucher_balances.primeamt',
                'coa.description',
            )
            ->orderBy('voucher_balances.voucherdate')
            ->orderBy('voucher_balances.vouchernumber')
            ->get();
        $company = CompanyInfo::firstOrFail();
        $pdf = PDF::loadView('exports.accounts.cashbook', [
            'company' => $company,
            'opening' => $opening,
            'cashBook' => $cashBook,
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("CashBook-Reports.pdf");
    }

    public function CashFlow()
    {
        try {
            $this->authorize('accountsreport.CashFlow');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/reports/accounts/cashflow', [
            'branch' => Branch::where('active', 1)->get(),
        ]);
    }

    public function CashFlowReport(Request $request)
    {

        $from = $request->startdate;
        $to   = $request->enddate;
        $branchId = $request->branch_id;
        /* =============================
         | 1. CASH & BANK ACCOUNT CODES
         ============================= */
        $cashAccounts = ChartOfAccount::where('accountusage', ['Ledger', 'AR'])
            ->whereIn('analyticalcode', ['Cash'])
            ->pluck('accountcode');
        /* =============================
         | 2. OPENING CASH BALANCE
         ============================= */
        $openingCash = VoucherBalance::whereIn('accountcode', $cashAccounts)
            ->where('voucherdate', '<', $from)
            ->where('status', 'Post')
            ->when(
                $branchId,
                fn($q) =>
                $q->where('branch_id', $branchId)
            )
            ->sum('primeamt');
        /* =============================
         | 3. OPERATING ACTIVITIES
         | (Income & Expense related cash)
         ============================= */
        $operating = VoucherBalance::select(
            'chart_of_accounts.description',
            DB::raw('SUM(voucher_balances.primeamt) as amount')
        )
            ->join('chart_of_accounts', 'chart_of_accounts.accountcode', '=', 'voucher_balances.accountcode')
            ->whereBetween('voucherdate', [$from, $to])
            ->where('voucher_balances.status', 'Post')
            ->whereIn('chart_of_accounts.accounttype', ['REVENUES', 'EXPENDITURES'])
            ->whereIn('voucher_balances.accountcode', $cashAccounts)
            ->when(
                $branchId,
                fn($q) =>
                $q->where('voucher_balances.branch_id', $branchId)
            )
            ->groupBy('chart_of_accounts.description')
            ->get();
        $netOperating = $operating->sum('amount');
        /* =============================
         | 4. INVESTING ACTIVITIES
         | (Fixed Asset purchase/sale)
         ============================= */
        $investing = VoucherBalance::select(
            'chart_of_accounts.description',
            DB::raw('SUM(voucher_balances.primeamt) as amount')
        )
            ->join('chart_of_accounts', 'chart_of_accounts.accountcode', '=', 'voucher_balances.accountcode')
            ->whereBetween('voucherdate', [$from, $to])
            ->where('voucher_balances.status', 'Post')
            ->where('chart_of_accounts.accounttype', 'ASSET')
            ->whereNotIn('chart_of_accounts.analyticalcode', ['Cash'])
            ->whereIn('voucher_balances.accountcode', $cashAccounts)
            ->when(
                $branchId,
                fn($q) =>
                $q->where('voucher_balances.branch_id', $branchId)
            )
            ->groupBy('chart_of_accounts.description')
            ->get();

        $netInvesting = $investing->sum('amount');
        /* =============================
         | 5. FINANCING ACTIVITIES
         | (Loan, Capital, Drawings)
         ============================= */
        $financing = VoucherBalance::select(
            'chart_of_accounts.description',
            DB::raw('SUM(voucher_balances.primeamt) as amount')
        )
            ->join('chart_of_accounts', 'chart_of_accounts.accountcode', '=', 'voucher_balances.accountcode')
            ->whereBetween('voucherdate', [$from, $to])
            ->where('voucher_balances.status', 'Post')
            ->whereIn('chart_of_accounts.accounttype', ['LIABILITIES'])
            ->whereIn('voucher_balances.accountcode', $cashAccounts)
            ->when(
                $branchId,
                fn($q) =>
                $q->where('voucher_balances.branch_id', $branchId)
            )
            ->groupBy('chart_of_accounts.description')
            ->get();

        $netFinancing = $financing->sum('amount');

        /* =============================
         | 6. CLOSING CASH
         ============================= */
        $closingCash = $openingCash + $netOperating + $netInvesting + $netFinancing;

        $company = CompanyInfo::firstOrFail();
        $pdf = PDF::loadView('exports.accounts.cashflow', [
            'company' => $company,
            'from_date' => $from,
            'to_date' => $to,
            'openingCash' => $openingCash,
            'operating' => $operating,
            'investing' => $investing,
            'financing' => $financing,
            'netOperating' => $netOperating,
            'netInvesting' => $netInvesting,
            'netFinancing' => $netFinancing,
            'closingCash' => $closingCash
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("CashFlow-Reports.pdf");
    }

    public function JurnalTransactions()
    {
        try {
            $this->authorize('accountsreport.JurnalTransactions');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/reports/accounts/jurnaltransactions', [
            'branch' => Branch::where('active', 1)->get(),
            'transaction' => Transaction::whereNotIn('trncode', ['STU-', 'MR--', 'QTN-', 'INV-', 'SR--', 'INT-', 'SUP-'])->where('active', 1)->get(),
        ]);
    }

    public function JurnalTransactionsReport(Request $request)
    {

        try {
            $this->authorize('accountsreport.JurnalTransactions');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $jurnalTransactions = Voucherheader::with(['voucherdt.ChartOfAccount'])
            ->whereBetween('voucherdate', [$request->startdate, $request->enddate])
            ->when($request->filled('branch_id'), function ($q) use ($request) {
                $q->where('branch_id', $request->branch_id);
            })
            ->when($request->filled('transaction_id'), function ($q) use ($request) {
                $q->whereRaw(
                    "LEFT(vouchernumber, ?) = ?",
                    [strlen($request->transaction_id), $request->transaction_id]
                );
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->orderBy('voucherdate', 'asc')
            ->get();


        $company = CompanyInfo::firstOrFail();
        $pdf = PDF::loadView('exports.accounts.jurnaltransactions', [
            'company' => $company,
            'jurnalTransactions' => $jurnalTransactions,
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("JurnalTransactions-Reports.pdf");
    }

    public function TrialBalanceConsolidated()
    {
        try {
            $this->authorize('accountsreport.trialbalanceconsolidated');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/reports/accounts/trialbalanceconsolidated', [
            'branch' => Branch::where('active', 1)->get(),
        ]);
    }

    public function TrialBalanceConsolidatedReport(Request $request)
    {
        $company = CompanyInfo::firstOrFail();
        $branch = Branch::where('id', $request->branch_id)->first();
        $trialBalance = VoucherBalance::join(
            'chart_of_accounts',
            'voucher_balances.accountcode',
            '=',
            'chart_of_accounts.accountcode'
        )
            ->whereBetween('voucher_balances.voucherdate', [
                $request->startdate,
                $request->enddate
            ])
            ->where('voucher_balances.status', 'Post')
            ->where('chart_of_accounts.accountusage', 'Ledger')
            ->when($request->filled('branch_id'), function ($q) use ($request) {
                $q->where('voucher_balances.branch_id', $request->branch_id);
            })
            ->select(
                'chart_of_accounts.accountcode',
                'chart_of_accounts.description',
                DB::raw('SUM(voucher_balances.primeamt) as balance')
            )
            ->groupBy(
                'chart_of_accounts.accountcode',
                'chart_of_accounts.description'
            )
            ->orderBy('chart_of_accounts.accountcode', 'asc')
            ->get();

        $pdf = PDF::loadView('exports.accounts.trialbalanceconsolidated', [
            'company' => $company,
            'branch' => $branch,
            'trialBalance' => $trialBalance,
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("TrialBalanceConsolidated-Reports.pdf");
    }

    public function TrialBalance()
    {
        try {
            $this->authorize('accountsreport.trialbalance');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/reports/accounts/trialbalance', [
            'branch' => Branch::where('active', 1)->get(),
        ]);
    }

    public function TrialBalanceReport(Request $request)
    {

        $company = CompanyInfo::firstOrFail();
        $branch = Branch::where('id', $request->branch_id)->first();
        $startdate = $request->startdate;
        $enddate   = $request->enddate;
        $branchId  = $request->branch_id;
        $trialBalance = DB::table('chart_of_accounts as coa')
            ->leftJoin('voucher_balances as vb', 'vb.accountcode', '=', 'coa.accountcode')
            ->leftJoin('voucherheaders as vh', 'vh.vouchernumber', '=', 'vb.vouchernumber')

            ->when($branchId, function ($q) use ($branchId) {
                $q->where('vb.branch_id', $branchId);
            })

            ->where('vb.status', 'Post')
            ->where('vh.status', 'Posted')

            ->select(
                'coa.accountcode',
                'coa.description',

                /* ================= Balance B/F ================= */
                DB::raw("
                SUM(
                    CASE 
                        WHEN vb.voucherdate < '$startdate' 
                        AND vb.primeamt > 0 
                        THEN vb.primeamt ELSE 0 
                    END
                ) AS bf_debit
            "),

                DB::raw("
                SUM(
                    CASE 
                        WHEN vb.voucherdate < '$startdate' 
                        AND vb.primeamt < 0 
                        THEN ABS(vb.primeamt) ELSE 0 
                    END
                ) AS bf_credit
            "),

                /* ================= Current Period ================= */
                DB::raw("
                SUM(
                    CASE 
                        WHEN vb.voucherdate BETWEEN '$startdate' AND '$enddate'
                        AND vb.primeamt > 0
                        THEN vb.primeamt ELSE 0
                    END
                ) AS cur_debit
            "),

                DB::raw("
                SUM(
                    CASE 
                        WHEN vb.voucherdate BETWEEN '$startdate' AND '$enddate'
                        AND vb.primeamt < 0
                        THEN ABS(vb.primeamt) ELSE 0
                    END
                ) AS cur_credit
            "),

                /* ================= Balance C/F ================= */
                DB::raw("
                (
                    SUM(
                        CASE 
                            WHEN vb.voucherdate < '$startdate' 
                            AND vb.primeamt > 0 
                            THEN vb.primeamt ELSE 0 
                        END
                    ) +
                    SUM(
                        CASE 
                            WHEN vb.voucherdate BETWEEN '$startdate' AND '$enddate'
                            AND vb.primeamt > 0
                            THEN vb.primeamt ELSE 0
                        END
                    )
                ) AS cf_debit
            "),

                DB::raw("
                (
                    SUM(
                        CASE 
                            WHEN vb.voucherdate < '$startdate' 
                            AND vb.primeamt < 0 
                            THEN ABS(vb.primeamt) ELSE 0 
                        END
                    ) +
                    SUM(
                        CASE 
                            WHEN vb.voucherdate BETWEEN '$startdate' AND '$enddate'
                            AND vb.primeamt < 0
                            THEN ABS(vb.primeamt) ELSE 0
                        END
                    )
                ) AS cf_credit
            ")
            )

            ->groupBy('coa.accountcode', 'coa.description')
            ->havingRaw('
            bf_debit <> 0 OR bf_credit <> 0 
            OR cur_debit <> 0 OR cur_credit <> 0
            OR cf_debit <> 0 OR cf_credit <> 0
        ')
            ->orderBy('coa.accountcode')
            ->get();
        $pdf = PDF::loadView('exports.accounts.trialbalance', [
            'company' => $company,
            'branch' => $branch,
            'trialBalance' => $trialBalance,
            'startdate' => $request->startdate,
            'enddate' => $request->enddate,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("TrialBalance-Reports.pdf");
    }


    public function BalanceSheet()
    {
        try {
            $this->authorize('accountsreport.balancesheet');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/reports/accounts/balancesheet', [
            'branch' => Branch::where('active', 1)->get(),
            'months' => collect($this->createMonth())
                ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                ->values()
                ->toArray(),
            'years' => collect($this->createYear())
                ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                ->values()
                ->toArray(),
        ]);
    }

    public function BalanceSheetReport(Request $request)
    {
        
        $company = CompanyInfo::firstOrFail();
        $branch = Branch::where('id', $request->branch_id)->first();
        
        if($request->type=='Summary'){
            $sql = DB::select("
                SELECT 
                    b.accounttype,
                    b.grouptwo_name,
                    SUM(a.baseamt) AS balance
                FROM voucher_balances a
                JOIN vw_chartofaccs b ON a.accountcode = b.accountcode
                WHERE b.accounttype IN ('ASSET','LIABILITIES')
                AND a.yearname = ?
                AND a.monthname <= ?
                AND a.status = 'Post'
                " . ($request->filled('branch_id') ? "AND a.branch_id = ?" : "") . "
                GROUP BY b.accounttype, b.grouptwo_name
                ORDER BY b.groupone_code, b.grouptwo_code
            ", $request->filled('branch_id')
                ? [$request->yearname, $request->monthname, $request->branch_id]
                : [$request->yearname, $request->monthname]
            );
        }else{
            $sql = DB::select("
                SELECT 
                    b.accounttype,
                    b.grouptwo_name,
                    b.accountcode,
                    b.ledger_name,
                    IFNULL(SUM(a.baseamt),0) AS balance
                FROM vw_chartofaccs b
                LEFT JOIN voucher_balances a 
                    ON a.accountcode = b.accountcode
                    AND a.yearname = ?
                    AND a.monthname <= ?
                    AND a.status = 'Post'
                    " . ($request->filled('branch_id') ? " AND a.branch_id = ? " : "") . "
                WHERE b.accounttype IN ('ASSET','LIABILITIES')
                GROUP BY 
                    b.accounttype,
                    b.accountcode,
                    b.ledger_name,
                    b.groupone_code,
                    b.grouptwo_name
                    HAVING balance <> 0
                ORDER BY 
                    b.groupone_code,
                    b.grouptwo_code,
                    b.accountcode
            ", array_filter([
                $request->yearname,
                $request->monthname,
                $request->branch_id ?? null
            ]));
        }
        $groupedAssets = collect($sql)->groupBy('accounttype')
                            ->map(function ($items) {
                                return $items->groupBy('grouptwo_name');
                            });
       
        
        $pdf = PDF::loadView('exports.accounts.balancesheet', [
            'company' => $company,
            'branch' => $branch,
            'monthname' => $request->monthname,
            'yearname' => $request->yearname,
            'groupedAssets' => $groupedAssets
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("BalanceSheet-Reports.pdf");
    }

    public function ProfitLoss()
    {
        try {
            $this->authorize('accountsreport.profitloss');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/reports/accounts/profitloss', [
            'branch' => Branch::where('active', 1)->get(),
            'months' => collect($this->createMonth())
                ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                ->values()
                ->toArray(),
            'years' => collect($this->createYear())
                ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                ->values()
                ->toArray(),
        ]);
    }

    public function ProfitLossReport(Request $request)
    {
        
        $company = CompanyInfo::firstOrFail();
        $branch = Branch::where('id', $request->branch_id)->first();
        
        if($request->type=='Summary'){
            $sql = DB::select("
                SELECT 
                    b.accounttype,
                    b.grouptwo_name,
                    SUM(a.baseamt) AS balance
                FROM voucher_balances a
                JOIN vw_chartofaccs b ON a.accountcode = b.accountcode
                WHERE b.accounttype IN ('REVENUES','EXPENDITURES')
                AND a.yearname = ?
                AND a.monthname <= ?
                AND a.status = 'Post'
                " . ($request->filled('branch_id') ? "AND a.branch_id = ?" : "") . "
                GROUP BY b.accounttype, b.grouptwo_name
                ORDER BY b.groupone_code, b.grouptwo_code
            ", $request->filled('branch_id')
                ? [$request->yearname, $request->monthname, $request->branch_id]
                : [$request->yearname, $request->monthname]
            );
        }else{
            $sql = DB::select("
                SELECT 
                    b.accounttype,
                    b.grouptwo_name,
                    b.accountcode,
                    b.ledger_name,
                    IFNULL(SUM(a.baseamt),0) AS balance
                FROM vw_chartofaccs b
                LEFT JOIN voucher_balances a 
                    ON a.accountcode = b.accountcode
                    AND a.yearname = ?
                    AND a.monthname <= ?
                    AND a.status = 'Post'
                    " . ($request->filled('branch_id') ? " AND a.branch_id = ? " : "") . "
                WHERE b.accounttype IN ('REVENUES','EXPENDITURES')
                GROUP BY 
                    b.accounttype,
                    b.accountcode,
                    b.ledger_name,
                    b.groupone_code,
                    b.grouptwo_name
                    HAVING balance <> 0
                ORDER BY 
                    b.groupone_code,
                    b.grouptwo_code,
                    b.accountcode
            ", array_filter([
                $request->yearname,
                $request->monthname,
                $request->branch_id ?? null
            ]));
        }
        $groupedAssets = collect($sql)->groupBy('accounttype')
                            ->map(function ($items) {
                                return $items->groupBy('grouptwo_name');
                            });
       
        
        $pdf = PDF::loadView('exports.accounts.profitloss', [
            'company' => $company,
            'branch' => $branch,
            'monthname' => $request->monthname,
            'yearname' => $request->yearname,
            'groupedAssets' => $groupedAssets
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("BalanceSheet-Reports.pdf");
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
