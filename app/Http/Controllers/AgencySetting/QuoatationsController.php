<?php

namespace App\Http\Controllers\AgencySetting;

use App\Http\Controllers\Controller;

use App\Models\AgencySetting\Quoatations;
use App\Http\Requests\Quoatations\StoreQuoatationsRequest;
use App\Http\Requests\Quoatations\UpdateQuoatationsRequest;
use App\Services\Agency\Setting\QuoatationsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class QuoatationsController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, QuoatationsService $quoatations)
    {
        $this->authorize('quoatations.index');

        return Inertia::render('allpages/Agency/Setting/quoatations',[
            'quoatationsFilter' => Quoatations::orderBy('id', 'desc')->get(),
            'quoatations' => $quoatations->get($request->query()),
            'filters'   => $quoatations->get($request->query()),
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreQuoatationsRequest $request)
    {
        $this->authorize('quoatations.store');

        $validated = $request->validated();
        Quoatations::create([
            'name'    => $validated['name'],
            'adddate' => Date('Y-m-d'),
            'user_id' => Auth::id(), // logged-in user
            'active'  => $validated['active'] ?? 0,
        ]);
        return redirect()->route('quoatations.index')->with('success', 'Quoatations Create successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Quoatations $quoatations)
    {
        $this->authorize('quoatations.destroy');

        try {
            $quoatations->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete transaction name.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $quoatations)
    {
        $this->authorize('quoatations.updateStatus');

        $validated = $request->validate([
            'active' => 'required|boolean' // or 'integer|in:0,1'
        ]);
        $quoatations = Quoatations::findOrFail($quoatations);
        $updated = $quoatations->update(['active' =>  $validated['active']]);
        if ($updated) {
            return $request->inertia()
                ? back()->with('success', 'Status updated successfully')
                : redirect()->route('quoatations.index')->with('success', 'Status updated');
        }
        return back()->with('error', 'Failed to update status');
    }
}
