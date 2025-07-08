<?php

namespace App\Http\Controllers;

use App\Models\Leaveplan;
use App\Http\Requests\Leaveplan\StoreLeaveplanRequest;
use App\Http\Requests\Leaveplan\UpdateLeaveplanRequest;
use Inertia\Inertia;

class LeaveplanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('allpages/leaveplan',[
            'leaveplan' => Leaveplan::orderBy('id', 'desc')->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveplanRequest $request)
    {
        Leaveplan::create($request->validated());
        return redirect()->route('leaveplan.index')->with('success', 'Leaveplan Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leaveplan $leaveplan)
    {
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
        $leaveplan->update($request->validated());
        return redirect()->route('leaveplan.index')->with('success', 'Leaveplan Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leaveplan $leaveplan)
    {
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
