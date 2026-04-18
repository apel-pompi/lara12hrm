<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Attendance;
use App\Services\AttendanceService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendanceController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request, AttendanceService $attendanceService)
    {
        try {
            $this->authorize('attendance.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $perPage = $request->query('per_page', 10);

        return Inertia::render('allpages/hrm/attendance', [
            'attendance' => $attendanceService->get(array_merge($request->query(), ['per_page' => $perPage])),
            'filters'   => $attendanceService->get($request->query()),
        ]);
    }

    public function show($id, $date)
    {

        try {
            $this->authorize('attendance.show');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $attendance = Attendance::with('employee:empid,empname,phoneoffice,email,blood')->with('employee.department:id,deptname')->with('employee.designation:id,desname')->with('employee.branch:id,branchname')->where('user_id', $id)->whereDate('record_time', $date)->get();

        return response()->json([
            'attendance' => $attendance,
        ]);
    }
}
