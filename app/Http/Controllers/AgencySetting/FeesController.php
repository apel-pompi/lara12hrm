<?php

namespace App\Http\Controllers\AgencySetting;

use App\Http\Controllers\Controller;
use App\Models\AgencySetting\Fees;
use App\Http\Requests\Fees\StoreFeesRequest;
use App\Http\Requests\Fees\UpdateFeesRequest;
use App\Services\Agency\Setting\FeesService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FeesController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, FeesService $feesService)
    {
        try {
            $this->authorize('Fees.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/Agency/Setting/fees',[
            'feesFilter' => Fees::with('user')->orderBy('id', 'desc')->get(),
            'fees' => $feesService->get($request->query()),
            'filters'   => $feesService->get($request->query()),
        ]);
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFeesRequest $request)
    {
        try {
            $this->authorize('Fees.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validated();
        Fees::create([
            'name'    => $validated['name'],
            'adddate' => Date('Y-m-d'),
            'user_id' => Auth::id(), // logged-in user
            'active'  => $validated['active'] ?? 0,
        ]);
        return redirect()->route('fees.index')->with('success', 'Fees Create successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fees $fees)
    {
        try {
            $this->authorize('Fees.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        return response()->json([
            'success' => true,
            'data' => $fees,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFeesRequest $request, Fees $fees)
    {
        try {
            $this->authorize('Fees.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $fees->update($request->validated());
        return redirect()->route('fees.index')->with('success', 'Fees Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fees $fees)
    {
        try {
            $this->authorize('Fees.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        try {
            $fees->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete fees.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $fees)
    {
        try {
            $this->authorize('Fees.updateStatus');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $fees = Fees::findOrFail($fees);
        $updated = $fees->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('fees.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }
}
