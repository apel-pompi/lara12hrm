<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Leaveplan;
use App\Http\Requests\Leaveplan\StoreLeaveplanRequest;
use App\Http\Requests\Leaveplan\UpdateLeaveplanRequest;
use Illuminate\Auth\Access\AuthorizationException;
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
        try {
            $this->authorize('leaveplan.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/hrm/leaveplan',[
            'leaveplan' => Leaveplan::orderBy('id', 'desc')->get()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveplanRequest $request)
    {
        try {
            $this->authorize('leaveplan.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        Leaveplan::create($request->validated());
        return redirect()->route('leaveplan.index')->with('success', 'Leaveplan Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leaveplan $leaveplan)
    {
        try {
            $this->authorize('leaveplan.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('leaveplan.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
        try {
            $this->authorize('leaveplan.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $leaveplan->update($request->validated());
        return redirect()->route('leaveplan.index')->with('success', 'Leaveplan Update successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leaveplan $leaveplan)
    {
        try {
            $this->authorize('leaveplan.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


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
