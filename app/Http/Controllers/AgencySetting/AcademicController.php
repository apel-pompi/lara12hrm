<?php

namespace App\Http\Controllers\AgencySetting;

use App\Http\Controllers\Controller;
use App\Models\AgencySetting\Academic;
use App\Http\Requests\Academic\StoreAcademicRequest;
use App\Http\Requests\Academic\UpdateAcademicRequest;
use App\Services\Agency\Setting\AcademicService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AcademicController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, AcademicService $academicService)
    {
        $this->authorize('Academic.index');

        return Inertia::render('allpages/Agency/Setting/academic',[
            'academicFilter' => Academic::with('user')->orderBy('id', 'desc')->get(),
            'academic' => $academicService->get($request->query()),
            'filters'   => $academicService->get($request->query()),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAcademicRequest $request)
    {
        $this->authorize('Academic.store');

        $validated = $request->validated();
        Academic::create([
            'name'    => $validated['name'],
            'adddate' => Date('Y-m-d'),
            'user_id' => Auth::id(), // logged-in user
            'active'  => $validated['active'] ?? 0,
        ]);
        return redirect()->route('academic.index')->with('success', 'Academic Create successfully.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Academic $academic)
    {
        $this->authorize('Academic.edit');

        return response()->json([
            'success' => true,
            'data' => $academic,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAcademicRequest $request, Academic $academic)
    {
        $this->authorize('Academic.update');

        $academic->update($request->validated());
        return redirect()->route('academic.index')->with('success', 'Academic Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Academic $academic)
    {
        $this->authorize('Academic.destroy');

        try {
            $academic->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete academic.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $academic)
    {
        $this->authorize('Academic.updateStatus');

        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $academic = Academic::findOrFail($academic);
        $updated = $academic->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Academic updated successfully')
                : redirect()->route('academic.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }
}
