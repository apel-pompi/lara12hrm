<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Leave;
use App\Http\Requests\Leave\StoreLeaveRequest;
use App\Http\Requests\Leave\UpdateLeaveRequest;
use App\Models\HRM\CompanyInfo;
use App\Models\HRM\Leaveplan;
use App\Models\HRM\PersonalInfo;
use App\Mail\LeaveStatusMail;
use App\Services\LevaveService;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LeaveController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, LevaveService $levaveService)
    {
        try {
            $this->authorize('Leave.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $user = Auth::user();


        $leaveplan = Leaveplan::where('active', 1)->orderBy('id', 'desc')->get();

        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();

        $perPage = $request->query('per_page', 10);

        $leaves = $levaveService->get(
            array_merge($request->query(), ['per_page' => $perPage])
        );
        $employeeQuery = PersonalInfo::where('active', 1)->latest();

        if (! $roles->intersect(['superadmin', 'Admin', 'Manager'])->isNotEmpty()) {
            $employeeQuery->where('empname', $user->name);
        }

        return Inertia::render('allpages/hrm/leave', [
            'leaves' => $leaves,
            'filters'   => $levaveService->get($request->query()),
            'leaveplan' => $leaveplan,
            'employee'  => PersonalInfo::where('active', 1)->get(),
            'substitute'=> PersonalInfo::where('active', 1)->latest()->get(),
        ]);
    }


    public function fetchUserLeave($leaveplan_id, $empid)
    {
        // total allowed leave days from leaveplans
        $plan = Leaveplan::where('id', $leaveplan_id)->first(['leavedays']);
        if (!$plan) {
            return response()->json(['balance' => 0]);
        }

        // total leave already taken
        $taken = Leave::where('empid', $empid)->where('leaveplan_id', $leaveplan_id)->sum('requestdays');

        $balance = $plan->leavedays - $taken;

        return response()->json([
            'balance' => $balance,
            'allow' => $plan->leavedays,
            'taken' => $taken
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLeaveRequest $request)
    {
        try {
            $this->authorize('Leave.store');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        Leave::create($data);
        return redirect()->route('leave.index')->with('success', 'Leave Create successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {

        try {
            $this->authorize('Leave.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        if (!$leave) {
            return response()->json(['message' => 'Leave not found'], 404);
        }
        $data = Leave::with(['leavePlan', 'employee', 'substituteEmployee', 'user'])->where('id', $leave->id)->first();
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Leave $leave)
    {
        try {
            $this->authorize('Leave.edit');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $leave,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLeaveRequest $request, Leave $leave)
    {
        try {
            $this->authorize('Leave.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $data = $request->validated();
        $data['user_id'] = Auth::id();
        $leave->update($data);
        return redirect()->route('leave.index')->with('success', 'Leave Update successfully.');
    }

    public function confirm(Leave $leave)
    {
        try {
            $this->authorize('Leave.confirm');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $leave->update([
            'status' => 2
        ]);

        $leaveData = [
            'empname' => $leave->employee->empname,
            'fromdate' => $leave->fromdate,
            'todate' => $leave->todate,
            'status' => 'Pending Approval',
            'reason' => $leave->reason,
        ];

        Mail::to($leave->employee->email)->send(new LeaveStatusMail($leaveData));
        return back()->with('success', 'Leave confirmed and email sent.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        try {
            $this->authorize('Leave.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $leave->update([
            'status' => 1
        ]);
    }


    // PDF Export
    public function exportPdf(Leave $leave)
    {
        try {
            $this->authorize('Leave.reports');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $leave->load(
            'leavePlan',
            'employee.designation',
            'employee.department',
            'substituteEmployee.designation',
            'substituteEmployee.department'
        );
        $company = CompanyInfo::first();
        $fromdate = Carbon::parse($leave->fromdate)->format('d F, Y');
        $todate = Carbon::parse($leave->todate)->format('d F, Y');

        $empId = $leave->empid;
        $allleave = DB::table('leaveplans as a')
            ->leftJoin('leaves as b', function ($join) use ($empId) {
                $join->on('a.id', '=', 'b.leaveplan_id')
                    ->where('b.empid', '=', $empId);
            })
            ->select(
                'a.leavename',
                'a.leavedays as allow_days',
                DB::raw('SUM(CASE WHEN b.status = 3 THEN b.approveddays ELSE 0 END) as taken'),
                DB::raw('(a.leavedays - SUM(CASE WHEN b.status = 3 THEN b.approveddays ELSE 0 END)) as balance'),
                DB::raw('SUM(CASE WHEN b.status = 2 THEN b.approveddays ELSE 0 END) as nowapply')
            )
            ->where('a.active', 1)
            ->groupBy('a.id', 'a.leavename', 'a.leavedays')
            ->get();
        $pdf = Pdf::loadView('exports.leave', [
            'company' => $company,
            'yearname' => Carbon::parse(date('Y-m-d'))->format('Y'),
            'monthname' => Carbon::createFromDate(date('Y'), date('m'), 1)->format('F'),
            'leave' => $leave,
            'fromdate' => $fromdate,
            'todate' => $todate,
            'allleave' => $allleave
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("LeaveApplication_{$leave->id}.pdf");
    }
}
