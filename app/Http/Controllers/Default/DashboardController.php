<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Default\ApprovalRequest;
use App\Models\HRM\Attendance;
use App\Models\HRM\HolidayDt;
use App\Models\HRM\Leave;
use App\Models\HRM\PersonalInfo;
use App\Models\Student\Student;
use App\Models\Student\StudentInvoiceHD;
use App\Models\Student\StudentQuotationHD;
use App\Models\Student\StudentUtility;
use App\Models\User;
use App\Services\Agency\Student\AppoinmentsService;
use App\Services\Agency\Student\QuoattionRequestService;
use App\Services\Agency\Student\RefundRequestService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $current = Carbon::now();
        $currentDay = $current->year . '-' . $current->month . '-' . $current->day;
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        if ($roles->contains('superadmin') or $roles->contains('Admin') or $roles->contains('Manager')) {

            // Check if user is actually authenticated
            if (!Auth::check()) {
                return redirect()->login()->with([
                    'error' => true,
                    'message' => 'Please login again.'
                ]);
            }
            $userName = Auth::user()->name;
            // Check if username exists
            if (empty($userName)) {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'User name not found in authentication.'
                ]);
            }
            $sql = PersonalInfo::where('empname', 'LIKE', '%' . $userName . '%')
                ->where('active', 1)
                ->first();
            if (!$sql) {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'Employee record not found for employee: ' . $userName
                ]);
            }

            $query = PersonalInfo::with(['designation', 'department'])->where('branch_id', $sql->branch_id)->where('active', 1)->where(DB::raw("(date_format(joindate,'%Y-%m-%d'))"), '<=', $currentDay)->orderBy('id', 'ASC')->get();

            $reportData = [];

            foreach ($query as $value) {

                $outquery = Attendance::getAttendanceOut($value->empid, $currentDay);
                $outtime = $outquery->record_time ?? null;
                $outtimeRaw = $outtime ? Carbon::parse($outtime) : null;


                $status = Attendance::getAttendanceStatus($value->empid, $currentDay);
                $statusname = $status->TimeName ?? '---';

                if ($outtimeRaw && $outtimeRaw->lt(Carbon::parse($currentDay . '15:00:00'))) {

                    $statusname = 'Absent';
                }
                $leave = Leave::where('empid', $value->id)->where('status', 3)->whereDate('fromdate', '<=', $currentDay)->whereDate('todate', '>=', $currentDay)->exists();
                if ($leave) {
                    $statusname = 'Leave';
                }

                $reportData[] = [
                    'status' => $statusname,
                ];
            }

            $presentCount = 0;
            $lateCount = 0;
            $absentCount = 0;
            $leaveCount = 0;
            foreach ($reportData as $key) {
                if ($key['status'] == 'Present') {
                    $presentCount++;
                } elseif ($key['status'] == 'Late') {
                    $lateCount++;
                } elseif ($key['status'] == 'Absent') {
                    $absentCount++;
                } elseif ($key['status'] == 'Leave') {
                    $leaveCount++;
                }
            }


            return Inertia::render('AdminDashboard', [
                'countAll' => Student::count(),
                'countPending' => Student::where('status', null)->count(),
                'countLead' => Student::where('status', 1)->count(),
                'countProspect' => Student::where('status', 2)->count(),
                'countonBoard' => Student::where('status', 3)->count(),
                'countArchive' => Student::where('status', 4)->count(),
                'countArchiveApproval' => ApprovalRequest::where('remarks', 'Archive')->where('status', null)->count(),
                'countTransferApproval' => ApprovalRequest::where('remarks', 'Transfer')->where('status', null)->count(),
                'countLeave' => Leave::whereYear('fromdate', $current->year)->whereMonth('fromdate', $current->month)->where('status', 2)->count(),
                'countQuotationApproval' => ApprovalRequest::where('remarks', 'quotation')->where('status', null)->count(),
                'countOnBoardApproval' => ApprovalRequest::where('remarks', 'onBoard')->where('status', null)->count(),
                'countOnRefund' => ApprovalRequest::where('remarks', 'Refund')->where('status', null)->count(),
                'sumQuoat' => StudentQuotationHD::sum('totalamount'),
                'sumInvoice' => StudentInvoiceHD::where('status', 'Confirmed')->where('sign', 1)->sum('netamount'),
                'sumMR' => Student::selectRaw("COUNT(b.student_id) AS studentcount,SUM(b.netamount) AS netamount,b.student_id")
                    ->leftJoin('student_invoice_hd as b', 'students.id', '=', 'b.student_id')
                    ->whereNotNull('students.student_id')
                    ->where('b.sign', '-1')
                    ->whereRaw("LEFT(b.insnumber, 4) = 'MR--'")
                    ->groupBy('b.student_id')
                    ->get(),
                'calander' => StudentUtility::with('student')->where('name', 'appoinments')->get(),
                'presentCount' => $presentCount,
                'lateCount' => $lateCount,
                'absentCount' => $absentCount,
                'leaveCount' => $leaveCount,
            ]);
        } else {
            // Check if user is actually authenticated
            if (!Auth::check()) {
                return redirect()->login()->with([
                    'error' => true,
                    'message' => 'Please login again.'
                ]);
            }
            $userName = Auth::user()->name;
            // Check if username exists
            if (empty($userName)) {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'User name not found in authentication.'
                ]);
            }
            $sql = PersonalInfo::where('empname', 'LIKE', '%' . $userName . '%')
                ->where('active', 1)
                ->first();
            if (!$sql) {
                return redirect()->back()->with([
                    'error' => true,
                    'message' => 'Employee record not found for user: ' . $userName
                ]);
            }
            $current = Carbon::now();
            $leave = Leave::where('empid', $sql->id)->whereYear('fromdate', $current->year)->whereMonth('fromdate', $current->month)->where('status', 2)->sum('approveddays');

            $daysInMonth = $current->daysInMonth;
            $reportData = [];
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $values = str_pad($i, 2, '0', STR_PAD_LEFT);
                $currentDate = $current->year . '-' . $current->month . '-' . $values;
                $displayDate = $values . '-' . $current->month . '-' . $current->year;
                $holiday = HolidayDt::select('holitypes')->where('holidate', $currentDate)->first();
                if ($holiday) {
                    $reportData[] = [
                        'datename' => $displayDate,
                        'intime' => '---',
                        'outtime' => '---',
                        'status' => '---',
                        'workhours' => '---',
                        'holiday_type' => $holiday->holitypes,
                        'is_holiday' => true
                    ];
                } else {
                    //In Time
                    $inquery = Attendance::getAttendanceIn($sql->empid, $currentDate);
                    $intime = $inquery->record_time ?? null;
                    $in = $intime ? date('h:i:s A', strtotime($intime)) : '---';
                    //Out Time
                    $outquery = Attendance::getAttendanceOut($sql->empid, $currentDate);
                    $outtime = $outquery->record_time ?? null;

                    $outtimeRaw = $outtime ? Carbon::parse($outtime) : null;


                    $out = $outtime ? date('h:i:s A', strtotime($outtime)) : '---';

                    //Status
                    $status = Attendance::getAttendanceStatus($sql->empid, $currentDate);
                    $statusname = $status->TimeName ?? '---';
                    // Work hours calculation
                    $workHours = '---';
                    if ($statusname != 'Absent' && $intime && $outtime) {
                        $start = strtotime($intime);
                        $end = strtotime($outtime);
                        $diffSeconds = $end - $start;
                        $hours = floor($diffSeconds / 3600);
                        $minutes = floor(($diffSeconds % 3600) / 60);
                        $workHours = sprintf("%02d:%02d", $hours, $minutes); // format HH:MM
                    }
                    if ($outtimeRaw && $outtimeRaw->lt(Carbon::parse($currentDate . '15:00:00'))) {
                        $workHours = '00:00';
                        $statusname = 'Absent';
                    }

                    $reportData[] = [
                        'datename' => $displayDate,
                        'intime' => $in,
                        'outtime' => $out,
                        'status' => $statusname,
                        'workhours' => $workHours,
                        'is_holiday' => false,
                        'holiday_type' => null
                    ];
                }
            }
            // Summary Calculation
            $presentCount = 0;
            $lateCount = 0;
            $absentCount = 0;
            $holidayCount = 0;
            $totalWorkSeconds = 0;
            foreach ($reportData  as $day) {

                if ($day['status'] == 'Present') {
                    $presentCount++;
                } elseif ($day['status'] == 'Late') {
                    $lateCount++;
                } elseif ($day['status'] == 'Absent') {
                    $absentCount++;
                }

                if ($day['is_holiday']) {
                    $holidayCount++;
                    continue;
                }

                if ($day['workhours'] !== '---' && preg_match('/^(\d{2}):(\d{2})$/', $day['workhours'], $m)) {
                    $totalWorkSeconds += ($m[1] * 3600) + ($m[2] * 60);
                }
                $totalHours = floor($totalWorkSeconds / 3600);
                $totalMinutes = floor(($totalWorkSeconds % 3600) / 60);
                $totalWorkHoursFormatted = sprintf("%02d:%02d", $totalHours, $totalMinutes);
            }

            //In Time
            $inquery = Attendance::getAttendanceIn($sql->empid, $current);
            $intime = $inquery->record_time ?? null;
            $in = $intime ? date('h:i:s', strtotime($intime)) : '---';

            //Out Time
            $outquery = Attendance::getAttendanceOut($sql->empid, $current);
            $outtime = $outquery->record_time ?? null;

            $outtimeRaw = $outtime ? Carbon::parse($outtime) : null;

            $out = $outtime ? date('h:i:s', strtotime($outtime)) : '---';
            //Status
            $status = Attendance::getAttendanceStatus($sql->empid, $current);
            $statusname = $status->TimeName ?? '---';

            // Work hours calculation
            $workHours = '---';
            if ($statusname != 'Absent' && $intime && $outtime) {
                $start = strtotime($intime);
                $end = strtotime($outtime);
                $diffSeconds = $end - $start;
                $hours = floor($diffSeconds / 3600);
                $minutes = floor(($diffSeconds % 3600) / 60);
                $workHours = sprintf("%02d:%02d", $hours, $minutes); // format HH:MM
            }
            $cutOffTime = $current->copy()->setTime(15, 0, 0);
            if ($outtimeRaw && $outtimeRaw->lt($cutOffTime)) {
                $workHours = '00:00';
                $statusname = 'Absent';
            }

            return Inertia::render('UserDashboard', [
                'countAll' => Student::where('assain_user', Auth::id())->count(),
                'countLead' => Student::where('assain_user', Auth::id())->where('status', 1)->count(),
                'countPending' => Student::where('assain_user', Auth::id())->where('status', null)->count(),
                'countProspect' => Student::where('assain_user', Auth::id())->where('status', 2)->count(),
                'countonBoard' => Student::where('assain_user', Auth::id())->where('status', 3)->count(),
                'countArchive' => Student::where('assain_user', Auth::id())->where('status', 4)->count(),
                'countArchiveApproval' => ApprovalRequest::where('remarks', 'Archive')->where('user_id', Auth::id())->count(),
                'countQuotationApproval' => ApprovalRequest::where('remarks', 'quotation')->where('user_id', Auth::id())->count(),

                'sumQuoat' => StudentQuotationHD::where('user_id', Auth::id())->sum('totalamount'),
                'sumInvoice' => StudentInvoiceHD::where('user_id', Auth::id())->where('status', 'Confirmed')->where('sign', 1)->sum('netamount'),
                'sumMR' => Student::selectRaw("COUNT(b.student_id) AS studentcount,SUM(b.netamount) AS netamount,b.student_id")
                    ->leftJoin('student_invoice_hd as b', 'students.id', '=', 'b.student_id')
                    ->where('students.assain_user', Auth::id())
                    ->whereNotNull('students.student_id')
                    ->where('b.sign', '-1')
                    ->whereRaw("LEFT(b.insnumber, 4) = 'MR--'")
                    ->groupBy('b.student_id')
                    ->get(),
                'calander' => StudentUtility::with('student')->where('name', 'appoinments')->where('user_id', Auth::id())->get(),
                'intimes'      => $in,
                'outtimes'      => $out,
                'statuses'      => $statusname,
                'workhours'      => $workHours,
                'presentCount' => $presentCount,
                'lateCount' => $lateCount,
                'absentCount' => $absentCount,
                'leaveCount' => $leave,
                'holidayCount' => $holidayCount,
                'totalWork' => $totalWorkHoursFormatted,
                'teamStats' => [
                    'todo' => 12,
                    'inProgress' => 23,
                    'completed' => 64,
                    'completionRate' => 57,
                    'sprintDaysLeft' => 13,
                    'nextMeeting' => 'Thursday'
                ]
            ]);
        }
    }

    public function ArchiveRequest()
    {

        $user = Auth::user();

        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();

        if ($roles->contains('superadmin')  or $roles->contains('Admin') or $roles->contains('Manager')) {

            return Inertia::render('allpages/Agency/Request/archive', [
                'archive' => ApprovalRequest::with(['user', 'student'])->where('remarks', 'Archive')->orderBy('id', 'DESC')->paginate(20),
                'isadmin' => true,

            ]);
        } else {
            return Inertia::render('allpages/Agency/Request/archive', [
                'archive' => ApprovalRequest::with(['user', 'student'])->where('remarks', 'Archive')->where('user_id', Auth::id())->orderBy('id', 'DESC')->paginate(20),
                'isadmin' => false,
            ]);
        }
    }

    public function TransferRequest()
    {

        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        if ($roles->contains('superadmin')  or $roles->contains('Admin') or $roles->contains('Manager')) {

            return Inertia::render('allpages/Agency/Request/transfer', [
                'transfer' => ApprovalRequest::with(['user', 'student'])->where('remarks', 'Transfer')->orderBy('id', 'DESC')->paginate(20),
                'users' => User::get(),
                'isadmin' => true,

            ]);
        } else {
            return Inertia::render('allpages/Agency/Request/transfer', [
                'transfer' => ApprovalRequest::with(['user', 'student'])->where('remarks', 'Transfer')->where('user_id', Auth::id())->orderBy('id', 'DESC')->paginate(20),
                'isadmin' => false,
            ]);
        }
    }

    public function onBoardRequest()
    {

        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        if ($roles->contains('superadmin')  or $roles->contains('Admin') or $roles->contains('Manager')) {

            return Inertia::render('allpages/Agency/Request/onboard', [
                'onBoard' => ApprovalRequest::with(['user', 'student'])->where('remarks', 'onBoard')->orderBy('id', 'DESC')->paginate(20),
                'isadmin' => true,

            ]);
        } else {
            return Inertia::render('allpages/Agency/Request/onboard', [
                'onBoard' => ApprovalRequest::with(['user', 'student'])->where('remarks', 'onBoard')->where('user_id', Auth::id())->orderBy('id', 'DESC')->paginate(20),
                'isadmin' => false,
            ]);
        }
    }

    public function LeaveRequest()
    {

        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        if ($roles->contains('superadmin')  or $roles->contains('Admin') or $roles->contains('Manager')) {

            return Inertia::render('allpages/Agency/Request/leave', [
                'leave' => Leave::with(['employee', 'substituteEmployee', 'leavePlan'])->where('status', 2)->orderBy('fromdate', 'desc')->paginate(20),
                'isadmin' => true,

            ]);
        } else {
            return Inertia::render('allpages/Agency/Request/leave', [
                'leave' => Leave::with(['employee', 'substituteEmployee', 'leavePlan'])->where('status', 2)->orderBy('fromdate', 'desc')->paginate(20),
                'isadmin' => false,
            ]);
        }
    }

    public function QuotationRequest(Request $request, QuoattionRequestService $service)
    {

        $user = Auth::user();

        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $isAdmin = $user->hasAnyRole(['superadmin', 'Admin', 'Manager']);

        $perPage = $request->query('per_page', 10);

        $quotation = $service->get(
            array_merge($request->query(), ['per_page' => $perPage])
        );

        return Inertia::render('allpages/Agency/Request/quotation', [
            'quotation'  => $quotation,
            'isadmin' => $isAdmin,
            'filters' => $request->only(['student_id', 'id']),
        ]);

        
    }

    public function ReturnRequest(Request $request, RefundRequestService $service)
    {
        $user = Auth::user();

        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $isAdmin = $user->hasAnyRole(['superadmin', 'Admin', 'Manager']);

        $perPage = $request->query('per_page', 10);

        $refund = $service->get(
            array_merge($request->query(), ['per_page' => $perPage])
        );

        return Inertia::render('allpages/Agency/Request/invoicereturn', [
            'refund'  => $refund,
            'isadmin' => $isAdmin,
            'filters' => $request->only(['student_id', 'id']),
        ]);
    }


    public function Calender(Request $request, AppoinmentsService $service)
    {
        $user  = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();

        $perPage = $request->query('per_page', 10);

        $isAdmin = $roles->intersect(['superadmin', 'Admin', 'Manager'])->isNotEmpty();

        $appointments = $service->get(
            array_merge($request->query(), ['per_page' => $perPage])
        );

        return Inertia::render('allpages/default/calender', [
            'appoinments' => $appointments,
            'isadmin'      => $isAdmin,
            'filters'      => $request->only(['student_id', 'id']),
        ]);
    }
}
