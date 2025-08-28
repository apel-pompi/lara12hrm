<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Http\Requests\Leave\StoreLeaveRequest;
use App\Http\Requests\Leave\UpdateLeaveRequest;
use App\Models\Leaveplan;
use App\Models\PersonalInfo;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;

class LeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaves = Leave::with(['employee', 'substituteEmployee', 'leavePlan'])
                       ->orderBy('fromdate', 'desc')
                       ->get();
        $leaveplan = Leaveplan::where('active',1)->orderBy('id', 'desc')->get();
        $employee = PersonalInfo::where('active',1)->orderBy('id', 'desc')->get();
        return Inertia::render('allpages/leave', [
            'leaves' => $leaves,
            'leaveplan' => $leaveplan,
            'employee' => $employee
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveRequest $request)
    {
        Leave::create($request->validated());
        return redirect()->route('leave.index')->with('success', 'Leave Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        if (!$leave) {
            return response()->json(['message' => 'Leave not found'], 404);
        }
        return response()->json($leave);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequest $request, Leave $leave)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        //
    }


    // PDF Export
    public function exportPdf(Leave $leave)
    {
        $pdf = Pdf::loadView('exports.leave', compact('leave'))->setPaper('a4', 'portrait');
        return $pdf->stream('users.pdf');
    }
}
