<?php

namespace App\Http\Controllers\Accounts;

use App\Http\Controllers\Controller;
use App\Models\Accounts\ChartOfAccount;
use App\Http\Requests\ChartOfAccount\StoreChartOfAccountRequest;
use App\Http\Requests\ChartOfAccount\UpdateChartOfAccountRequest;
use App\Models\Accounts\GroupOne;
use App\Models\Accounts\GroupThree;
use App\Models\Accounts\GroupTwo;
use App\Services\Accounts\ChartOfAccountService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChartOfAccountController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, ChartOfAccountService $chartOfAccountService)
    {
        try {
            $this->authorize('ChartOfAccount.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        $perPage = $request->query('per_page', 10);

        return Inertia::render('allpages/accounts/setting/chartofaccount', [
            'filters'   => $chartOfAccountService->get($request->query()),
            'chartofaccount' => $chartOfAccountService->get(array_merge($request->query(), ['per_page' => $perPage])),
            'groupone' => GroupOne::where('active', 1)->get(),
            'grouptwo' => GroupTwo::where('active', 1)->get(),
            'groupthree' => GroupThree::where('active', 1)->get(),
            'others' => ChartOfAccount::get(),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChartOfAccountRequest $request)
    {
        try {
            $this->authorize('ChartOfAccount.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $store = ChartOfAccount::create($data);
        if ($store) {
            return back()->with([
                'success' => true,
                'message' => 'Chart Of Accounts created successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Chart Of Accounts not created'
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ChartOfAccount $chartOfAccount)
    {
        try {
            $this->authorize('ChartOfAccount.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }
        return response()->json($chartOfAccount);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ChartOfAccount $chartOfAccount)
    {
        try {
            $this->authorize('ChartOfAccount.edit');

            return response()->json([
                'success' => true,
                'data' => $chartOfAccount,
            ]);
        } catch (AuthorizationException $e) {
            return response()->json([
                'success' => false,
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ], 403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChartOfAccountRequest $request, ChartOfAccount $chartOfAccount)
    {
        try {
            $this->authorize('ChartOfAccount.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $chartOfAccount->update($request->validated());

        if ($chartOfAccount) {
            return back()->with([
                'success' => true,
                'message' => 'Chart Of Accounts Update successfully'
            ]);
        } else {
            return back()->with([
                'error' => true,
                'message' => 'Chart Of Accounts not Updateed'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChartOfAccount $chartOfAccount)
    {
        try {
            $this->authorize('ChartOfAccount.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        try {
            $chartOfAccount->delete();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete Chart Of Accounts.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $chartOfAccount)
    {
        try {
            $this->authorize('ChartOfAccount.status');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $chartOfAccount = ChartOfAccount::findOrFail($chartOfAccount);
        $updated = $chartOfAccount->update(['active' =>  $validated['active']]);
        if ($updated) {
            return back()->with([
                'message' => 'Chart Of Accounts status updated successfully.'
            ]);
        }
        return back()->with([
            'message' => 'Failed to update status'
        ]);
    }

    

    public function getGroupTwo($GroupOne)
    {

        $GroupTwo = GroupTwo::where('groupone', $GroupOne)->get();

        return response()->json([
            'success' => true,
            'data' => $GroupTwo,
        ]);
    }

    public function getGroupThree($GroupOne, $GroupTwo)
    {

        $GroupThree = GroupThree::where('groupone', $GroupOne)->where('grouptwo', $GroupTwo)->get();

        return response()->json([
            'success' => true,
            'data' => $GroupThree,
        ]);
    }

    public function generateCode($groupthree)
    {
        $last = ChartOfAccount::where('groupthree', $groupthree)
            ->select(DB::raw("MAX(RIGHT(accountcode, 3)) as last_code"))
            ->first();

        $next = $last->last_code ? intval($last->last_code) + 1 : 1;

        // always 3 digits format
        $nextFormatted = str_pad($next, 3, '0', STR_PAD_LEFT);

        return response()->json([
            'accountcode' => $groupthree . '-' . $nextFormatted
        ]);
    }
}
