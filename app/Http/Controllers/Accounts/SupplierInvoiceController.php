<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Http\Requests\Voucherheader\StoreVoucherheaderRequest;
use App\Http\Requests\Voucherheader\UpdateVoucherheaderRequest;
use App\Models\Accounts\ChartOfAccount;
use App\Models\Accounts\CodesParam;
use App\Models\Accounts\Supplier;
use App\Models\Accounts\Voucherdetail;
use App\Models\Accounts\Voucherheader;
use App\Models\Default\Transaction;
use App\Models\HRM\Branch;
use App\Services\Accounts\SupplierInvocieService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SupplierInvoiceController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, SupplierInvocieService $supplier_invocie)
    {
        try {
            $this->authorize('SupplierInvoice.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $perPage = $request->query('per_page', 10);
        return Inertia::render('allpages/accounts/supplier/invoice', [
            'filters'   => $supplier_invocie->get($request->query()),
            'supplier_invoice' => $supplier_invocie->get(array_merge($request->query(), ['per_page' => $perPage])),
            'branch' => Branch::all(),
            'supplier' => Supplier::where('active', 1)->get(),
            'allvoucher' => Voucherheader::whereRaw("LEFT(vouchernumber, 4) = 'AP--'")->get(),
            'accountcode' => ChartOfAccount::where('active', '1')->where('accountusage', '<>', 'Ledger')->get(),
        ]);
    }

    private function GetInvoiceNO()
    {
        $transaction = Transaction::where('name', 'Supplier Invoice')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    public function store(StoreVoucherheaderRequest $request)
    {

        try {
            $this->authorize('SupplierInvoice.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $GetInvoiceNO = $this->GetInvoiceNO();
        $voucherDate = Carbon::parse($request->voucherdate);
        $subcode = CodesParam::where('accdisc', $request->subcode)->where('type', 'Supplier')->first();
        if (!$subcode) {
            return back()->with([
                'error' => true,
                'message' => 'Invalid Supplier selected.'
            ]);
        }
        DB::transaction(function () use ($request, $GetInvoiceNO, $voucherDate, $subcode) {
            Voucherheader::create([
                'vouchernumber' => $GetInvoiceNO,
                'voucherdate'   => $request->voucherdate,
                'referance'     => $request->referance,
                'yearname'      => $voucherDate->year,
                'monthname'     => $voucherDate->month,
                'branch_id'     => $request->branch_id,
                'notes'         => $request->notes,
                'user_id'       => Auth::id(),
            ]);
            Voucherdetail::insert([
                [
                    'vouchernumber' => $GetInvoiceNO,
                    'accountcode'   => $subcode->cracc,
                    'subacccode'    => $request->subcode,
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => -abs($request->invAmt),
                    'baseamt'       => -abs($request->invAmt),
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ],
                [
                    'vouchernumber' => $GetInvoiceNO,
                    'accountcode'   => $subcode->dracc,
                    'subacccode'    => null,
                    'currency'      => 'BDT',
                    'exchagerate'   => '1.000',
                    'primeamt'      => $request->invAmt,
                    'baseamt'       => $request->invAmt,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            ]);
        });
        $numericPart = (int) preg_replace('/\D/', '', $GetInvoiceNO);
        Transaction::where('name', 'Supplier Invoice')
            ->where('active', 1)
            ->update(['lastnumber' => $numericPart]);

        return back()->with([
            'success' => true,
            'message' => 'Supplier Invoice created successfully'
        ]);
    }

    public function edit(Voucherheader $supplier_invocie)
    {

        return Voucherheader::with(['voucherdt.ChartOFAccount', 'voucherdt.subacccode', 'branch'])->findOrFail($supplier_invocie->id);
    }

    public function update(Request $request, $id)
    {
        try {
            $this->authorize('SupplierInvoice.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $voucher = Voucherheader::findOrFail($id);
        $voucherDate = Carbon::parse($request->voucherdate);

        $subcode = CodesParam::where('accdisc', $request->subcode)
            ->where('type', 'Supplier')
            ->first();

        if (!$subcode) {
            return back()->with([
                'error' => true,
                'message' => 'Invalid Supplier selected.'
            ]);
        }

        DB::transaction(function () use ($request, $voucher, $voucherDate, $subcode) {

            /* Update Voucher Header */
            $voucher->update([
                'voucherdate' => $request->voucherdate,
                'referance'   => $request->referance,
                'yearname'    => $voucherDate->year,
                'monthname'   => $voucherDate->month,
                'branch_id'   => $request->branch_id,
                'notes'       => $request->notes,
                'user_id'     => Auth::id(),
            ]);

            /* Delete Old Details */
            Voucherdetail::where('vouchernumber', $voucher->vouchernumber)->forceDelete();

            $amount = abs($request->invAmt);

            /* Insert New Details */
            Voucherdetail::insert([
                [
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $subcode->cracc,
                    'subacccode'    => $request->subcode,
                    'currency'      => 'BDT',
                    'exchagerate'   => 1,
                    'primeamt'      => -$amount,
                    'baseamt'       => -$amount,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ],
                [
                    'vouchernumber' => $voucher->vouchernumber,
                    'accountcode'   => $subcode->dracc,
                    'subacccode'    => null,
                    'currency'      => 'BDT',
                    'exchagerate'   => 1,
                    'primeamt'      => $amount,
                    'baseamt'       => $amount,
                    'branch_id'     => $request->branch_id,
                    'notes'         => $request->notes,
                    'user_id'       => Auth::id(),
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]
            ]);
        });

        return back()->with([
            'success' => true,
            'message' => 'Supplier invoice updated successfully'
        ]);
    }

    public function Confirm(Voucherheader $supplier_invocie)
    {
        try {
            $this->authorize('SupplierInvoice.Confirm');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {

            DB::beginTransaction();

            DB::statement(
                'CALL sp_am_voucherpost(?, ?)',
                [$supplier_invocie->vouchernumber, Auth::id()]
            );

            DB::commit();
            return back()->with([
                'success' => true,
                'message' => 'Supplier invocie posted successfully'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            return back()->with([
                'error' => true,
                'message' => 'Supplier invocie posting failed.',
            ]);
        }
    }

}
