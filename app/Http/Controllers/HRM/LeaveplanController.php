<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Leaveplan;
use App\Http\Requests\Leaveplan\StoreLeaveplanRequest;
use App\Http\Requests\Leaveplan\UpdateLeaveplanRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class LeaveplanController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('leaveplan.index');

        return Inertia::render('allpages/leaveplan',[
            'leaveplan' => Leaveplan::orderBy('id', 'desc')->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveplanRequest $request)
    {
        $this->authorize('leaveplan.store');

        Leaveplan::create($request->validated());
        return redirect()->route('leaveplan.index')->with('success', 'Leaveplan Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leaveplan $leaveplan)
    {
        $this->authorize('leaveplan.show');

        if (!$leaveplan) {
            return response()->json(['message' => 'Leaveplan not found'], 404);
        }
        return response()->json($leaveplan);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leaveplan $leaveplan)
    {
        $this->authorize('leaveplan.edit');

        return response()->json([
            'success' => true,
            'data' => $leaveplan,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveplanRequest $request, Leaveplan $leaveplan)
    {
        $this->authorize('leaveplan.update');

        $leaveplan->update($request->validated());
        return redirect()->route('leaveplan.index')->with('success', 'Leaveplan Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leaveplan $leaveplan)
    {
        $this->authorize('leaveplan.destroy');

        try {
            $leaveplan->delete();

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete leaveplan.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
