<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccount;
use App\Models\Accounts\CodesParam;
use App\Models\Accounts\Supplier;
use App\Models\Accounts\VoucherApalc;
use App\Models\Accounts\VoucherBalance;
use App\Models\Accounts\Voucherdetail;
use App\Models\Accounts\Voucherheader;
use App\Models\Accounts\VwPayInvc;
use App\Models\Default\Transaction;
use App\Models\HRM\Branch;
use App\Services\Accounts\SupplierManagePayamentService;
use App\Services\Accounts\SupplierPayableService;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SupplierPayaleController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, SupplierPayableService $payable_service)
    {
        try {
            $this->authorize('SupplierPayable.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/accounts/supplier/payable', [
            'filters'   => $payable_service->get($request->query()),
            'payables' => $payable_service->get(array_merge($request->query(), ['per_page' => 15])),
            'branch' => Branch::all(),
            'accounts' => ChartOfAccount::where('active', '1')->where('accounttype', 'ASSET')->where('accountusage', '=', 'Ledger')->where('analyticalcode','Cash')->get(),
        ]);
    }

    public function FetchPayment($supplier_payment, $supplier_name, $group_three_description, $branch_id)
    {
        return [
            'supplier_payment' => VwPayInvc::select(
                'suppliercode',
                'invicenumber',
                DB::raw('MIN(date) as date'),
                'branch_id',
                'currency',
                'exchagerate',
                DB::raw('SUM(primeamt) as primeamt')
            )
                ->where('suppliercode', $supplier_payment)
                ->groupBy('suppliercode', 'invicenumber', 'branch_id', 'currency', 'exchagerate')
                ->havingRaw('SUM(primeamt) < 0')
                ->get(),
            'account_code' => CodesParam::where('accdisc', $supplier_payment)->first(['accdisc', 'cracc', 'dracc']),
            'supplier_name' => $supplier_name,
            'group_three_description' => $group_three_description,
            'branch_id' => $branch_id,
        ];
    }

    private function GetInvoiceNO()
    {
        $transaction = Transaction::where('name', 'Supplier Payment')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function store(Request $request)
    {

        try {
            $this->authorize('SupplierPayable.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to perform this action.'
            ]);
        }

        $GetInvoiceNO = $this->GetInvoiceNO();
        $voucherDate = Carbon::parse($request->voucherdate);
        DB::transaction(function () use ($request, $GetInvoiceNO, $voucherDate) {
            Voucherheader::create([
                'vouchernumber' => $GetInvoiceNO,
                'voucherdate'   => $request->voucherdate,
                'referance'     => $request->notes,
                'yearname'      => $voucherDate->year,
                'monthname'     => $voucherDate->month,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);
            Voucherdetail::insert([
                [
                    'vouchernumber' => $GetInvoiceNO,
                    'accountcode'   => $request->account,
                    'subacccode'    => null,
                    'currency'      => null,
                    'exchagerate'   => null,
                    'primeamt'      => -abs($request->amountPaid),
                    'baseamt'       => -abs($request->amountPaid),
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ],
                [
                    'vouchernumber' => $GetInvoiceNO,
                    'accountcode'   => $request->craccount,
                    'subacccode'    => $request->acc_code,
                    'currency'      => null,
                    'exchagerate'   => null,
                    'primeamt'      => $request->amountPaid,
                    'baseamt'       => $request->amountPaid,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            ]);
        });
        foreach ($request->selectedPay as $value) {
            VoucherApalc::create([
                'vouchernumber' => $GetInvoiceNO,
                'invnumber'     => $value['invicenumber'],
                'voucherdate'   => $request->voucherdate,
                'branch_id'     => $request->branch_id,
                'currency'      => $value['currency'],
                'exchagerate'   => $value['exchagerate'],
                'primeamt'      => $value['primeamt'],
                'baseamt'       => $value['primeamt'],
                'user_id'       => Auth::id(),
            ]);
        }
        $numericPart = (int) preg_replace('/\D/', '', $GetInvoiceNO);
        Transaction::where('name', 'Supplier Payment')
            ->where('active', 1)
            ->update(['lastnumber' => $numericPart]);

        try {

            DB::beginTransaction();

            DB::statement(
                'CALL sp_am_voucherpost(?, ?)',
                [$GetInvoiceNO, Auth::id()]
            );

            DB::commit();
            return back()->with([
                'success' => true,
                'message' => 'Supplier Payment created successfully'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with([
                'error' => true,
                'message' => 'Supplier Payment failed',
            ]);
        }
    }

    public function managePayable(Request $request, SupplierManagePayamentService $payment_service)
    {
        try {
            $this->authorize('SupplierPayable.managePayable');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/accounts/supplier/managePayable', [
            'filters'   => $payment_service->get($request->query()),
            'payment' => $payment_service->get(array_merge($request->query(), ['per_page' => 15])),
            'branch' => Branch::all(),
            'supplier' => Supplier::all(),
        ]);
    }
}
