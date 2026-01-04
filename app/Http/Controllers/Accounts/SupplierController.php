<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\Supplier;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Models\Default\Transaction;
use App\Services\Accounts\SupplierService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, SupplierService $supplier)
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
        return Inertia::render('allpages/accounts/supplier/index', [
            'filters'   => $supplier->get($request->query()),
            'supplier' => $supplier->get(array_merge($request->query(), ['per_page' => $perPage])),
        ]);
    }

    private function GetSupplierCode()
    {
        $transaction = Transaction::where('name', 'Supplier No')
            ->where('active', 1)
            ->first(['trncode', 'lastnumber']);
        $currentCode = $transaction->lastnumber;

        $nextCode = $currentCode + 1;
        $invoiceNo = $transaction->trncode . str_pad($nextCode, 9, '0', STR_PAD_LEFT);

        return $invoiceNo;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        try {
            $this->authorize('Supplier.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $subcode = $this->GetSupplierCode();
        $data['subcode'] = $subcode;
        $data['user_id'] = Auth::id();
        $store = Supplier::create($data);
        if ($store) {
            $numericPart = (int) preg_replace('/\D/', '', $subcode);
            Transaction::where('name', 'Supplier No')
                ->where('active', 1)
                ->update(['lastnumber' => $numericPart]);
            return back()->with([
                'success' => true,
                'message' => 'Supplier created successfully'
            ]);
            
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Supplier not created'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        try {
            $this->authorize('Supplier.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        if (!$supplier) {
            return response()->json(['message' => 'Supplier not found'], 404);
        }
        return response()->json($supplier);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        try {
            $this->authorize('Supplier.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $supplier,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        try {
            $this->authorize('Supplier.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $supplier->update($request->validated());

        return back()->with([
            'success' => true,
            'message' => 'Supplier updated successfully',
        ]);
    }

    public function updateStatus(Request $request, $supplier)
    {
        try {
            $this->authorize('Supplier.status');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $supplier = Supplier::findOrFail($supplier);
        $updated = $supplier->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('suppliers.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            $this->authorize('Supplier.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $supplier->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete supplier.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
