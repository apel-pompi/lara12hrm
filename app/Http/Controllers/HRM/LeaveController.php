<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Leave;
use App\Http\Requests\Leave\StoreLeaveRequest;
use App\Http\Requests\Leave\UpdateLeaveRequest;
use App\Models\HRM\Leaveplan;
use App\Models\HRM\PersonalInfo;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class LeaveController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('Leave.index');

        $leaves = Leave::with(['employee', 'substituteEmployee', 'leavePlan'])
                       ->orderBy('fromdate', 'desc')
                       ->get();
        $leaveplan = Leaveplan::where('active',1)->orderBy('id', 'desc')->get();
        $employee = PersonalInfo::where('active',1)->orderBy('id', 'desc')->get();
        return Inertia::render('allpages/hrm/leave', [
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
        $this->authorize('Leave.store');

        Leave::create($request->validated());
        return redirect()->route('leave.index')->with('success', 'Leave Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        $this->authorize('Leave.show');

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
        $this->authorize('Leave.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequest $request, Leave $leave)
    {
        $this->authorize('Leave.update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $this->authorize('Leave.destroy');
    }


    // PDF Export
    public function exportPdf(Leave $leave)
    {
        $this->authorize('Leave.reports');

        $pdf = Pdf::loadView('exports.leave', compact('leave'))->setPaper('a4', 'portrait');
        return $pdf->stream('users.pdf');
    }
}
