<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\CodesParam;
use App\Http\Requests\CodesParam\StoreCodesParamRequest;
use App\Http\Requests\CodesParam\UpdateCodesParamRequest;
use App\Models\Accounts\ChartOfAccount;
use App\Models\Accounts\Supplier;
use App\Models\HRM\Branch;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CodesParamController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('ACToGL.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/accounts/setting/actoglsetup', [

            'actogl' => CodesParam::with('branch', 'craccount.GroupFour', 'draccount', 'taxaccount')->get(),
            'supplier' => Supplier::where('active', 1)->get(),
            'craccountcode' => ChartOfAccount::with('GroupFour')->where('active', 1)->where('accounttype', 'LIABILITIES')->whereIn('accountusage', ['Ledger', 'AP'])->get(),

            'draccountcode' => ChartOfAccount::where('active', 1)->whereIn('accounttype', ['ASSETS', 'EXPENDITURES'])->whereIn('accountusage', ['Ledger', 'AR'])->where('analyticalcode', 'Cash')->get(),

            'branch' => Branch::all(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCodesParamRequest $request)
    {

        try {
            $this->authorize('ACToGL.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();

        $data['user_id'] = Auth::id();
        $store = CodesParam::create($data);
        if ($store) {

            return back()->with([
                'success' => true,
                'message' => 'CodeParam created successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'CodeParam not created'
            ]);
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CodesParam $codesParam)
    {
        try {
            $this->authorize('ACToGL.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $codesParam,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCodesParamRequest $request, CodesParam $codesParam)
    {
        try {
            $this->authorize('ACToGL.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $codesParam->update($request->validated());

        return back()->with([
            'success' => true,
            'message' => 'Code Pharam updated successfully',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CodesParam $codesParam)
    {
        try {
            $this->authorize('ACToGL.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $codesParam->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete code pharams.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $codesParam)
    {
        try {
            $this->authorize('ACToGL.status');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $codesParam = CodesParam::findOrFail($codesParam);
        $updated = $codesParam->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('ACToGL.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }
}
