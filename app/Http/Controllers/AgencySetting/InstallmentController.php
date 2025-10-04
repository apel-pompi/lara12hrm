<?php

namespace App\Http\Controllers\AgencySetting;

use App\Http\Controllers\Controller;

use App\Models\AgencySetting\Installment;
use App\Http\Requests\Installment\StoreInstallmentRequest;
use App\Http\Requests\Installment\UpdateInstallmentRequest;
use App\Services\Agency\Setting\InstallmentService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class InstallmentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, InstallmentService $installmentService)
    {
        $this->authorize('Installment.index');

        return Inertia::render('allpages/Agency/Setting/installment',[
            'installmentFilter' => Installment::with('user')->orderBy('id', 'desc')->get(),
            'installment' => $installmentService->get($request->query()),
            'filters'   => $installmentService->get($request->query()),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInstallmentRequest $request)
    {
        $this->authorize('Installment.store');

        $validated = $request->validated();
        Installment::create([
            'name'    => $validated['name'],
            'adddate' => Date('Y-m-d'),
            'user_id' => Auth::id(), // logged-in user
            'active'  => $validated['active'] ?? 0,
        ]);
        return redirect()->route('installment.index')->with('success', 'Installment Create successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Installment $installment)
    {
        $this->authorize('Installment.edit');

        return response()->json([
            'success' => true,
            'data' => $installment,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInstallmentRequest $request, Installment $installment)
    {
        $this->authorize('Installment.update');

        $installment->update($request->validated());
        return redirect()->route('installment.index')->with('success', 'Installment Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Installment $installment)
    {
        $this->authorize('Installment.destroy');

        try {
            $installment->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete installment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $installment)
    {
        $this->authorize('Installment.updateStatus');

        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $installment = Installment::findOrFail($installment);
        $updated = $installment->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Installment updated successfully')
                : redirect()->route('installment.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }
}
