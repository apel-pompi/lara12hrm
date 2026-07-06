<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Attendance;
use App\Models\HRM\AttendanceStatus;
use App\Models\HRM\AttenDeduct;
use App\Models\HRM\Branch;
use App\Models\HRM\CompanyInfo;
use App\Models\HRM\HolidayDt;
use App\Models\HRM\Leave;
use App\Models\HRM\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class HRreportsController extends Controller
{
    use AuthorizesRequests;

    protected function getAuthenticatedEmployee(): ?PersonalInfo
    {
        $authUser = Auth::user();

        if (! $authUser) {
            return null;
        }

        return PersonalInfo::where('empname', 'LIKE', '%' . $authUser->name . '%')
            ->where('active', 1)
            ->first();
    }

    public function index()
    {
        try {
            $this->authorize('hrReports.personal-info');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $authUser = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $authUser */
        $isSuperadmin = $authUser?->hasRole('superadmin') ?? false;
        $currentEmployee = $this->getAuthenticatedEmployee();

        $employeeQuery = PersonalInfo::where('active', 1);

        if (! $isSuperadmin) {
            if ($currentEmployee) {
                $employeeQuery->where('id', $currentEmployee->id);
            } else {
                $employeeQuery->whereRaw('1 = 0');
            }
        }

        return Inertia::render('allpages/reports/hrreports/EmpInfoReport', [
            'employee' => $employeeQuery->get(),
            'currentEmployee' => $currentEmployee,
            'isSuperadmin' => $isSuperadmin,
        ]);
    }

    public function EmpInfoReport(Request $request)
    {
        try {
            $this->authorize('hrReports.personal-info-reports');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $authUser = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $authUser */
        $isSuperadmin = $authUser?->hasRole('superadmin') ?? false;
        $currentEmployee = $this->getAuthenticatedEmployee();

        $requestedEmployeeId = $request->empid;

        if (! $isSuperadmin) {
            abort_unless($currentEmployee, 403, 'Employee record not found for the current user.');
            $requestedEmployeeId = $currentEmployee->id;
        }

        $company = CompanyInfo::first();
        $sql = PersonalInfo::with(['branch', 'designation', 'department'])
            ->where('id', $requestedEmployeeId)
            ->where('active', 1)
            ->firstOrFail();

        $pdf = Pdf::loadView('exports.hrreports.personalinfo', [
            'employees' => $sql,
            'company' => $company
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("PersonalInfo{$sql->empname}.pdf");
    }

    public function EmployeeAttendance()
    {
        try {
            $this->authorize('hrReports.employee-attendance');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $authUser = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $authUser */
        $isSuperadmin = $authUser?->hasRole('superadmin') ?? false;
        $currentEmployee = $this->getAuthenticatedEmployee();

        $employeeQuery = PersonalInfo::where('active', 1);

        if (! $isSuperadmin) {
            if ($currentEmployee) {
                $employeeQuery->where('id', $currentEmployee->id);
            } else {
                $employeeQuery->whereRaw('1 = 0');
            }
        }

        return Inertia::render('allpages/reports/hrreports/employeeattendance', [
            'employee' => $employeeQuery->get(),
            'currentEmployee' => $currentEmployee,
            'isSuperadmin' => $isSuperadmin,
            'months' => collect($this->createMonth())
                ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                ->values()
                ->toArray(),
            'years' => collect($this->createYear())
                ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                ->values()
                ->toArray(),
        ]);
    }

    public function EmployeeAttendanceReport(Request $request)
    {
        try {
            $this->authorize('hrReports.employee-attendance-reports');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }


        $authUser = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $authUser */
        $isSuperadmin = $authUser?->hasRole('superadmin') ?? false;
        $currentEmployee = $this->getAuthenticatedEmployee();

        $requestedEmpId = $request->empid;

        if (! $isSuperadmin) {
            abort_unless($currentEmployee, 403, 'Employee record not found for the current user.');
            $requestedEmpId = $currentEmployee->empid;
        }

        $sql = PersonalInfo::with(['designation', 'department'])
            ->where('empid', $requestedEmpId)
            ->where('active', 1)
            ->firstOrFail();
        $daysInMonth = date('t', mktime(0, 0, 0, $request->monthname, 1, $request->yearname));

        $reportData = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $values = str_pad($i, 2, '0', STR_PAD_LEFT);
            $currentDate = $request->yearname . '-' . $request->monthname . '-' . $values;
            $displayDate = $values . '-' . $request->monthname . '-' . $request->yearname;
            $holiday = HolidayDt::select('holitypes')->where('holidate', $currentDate)->first();
            if ($holiday) {
                $reportData[] = [
                    'datename' => $displayDate,
                    'intime' => '---',
                    'outtime' => '---',
                    'deduct' => '---',
                    'nethour' => '---',
                    'status' => '---',
                    'workhours' => '---',
                    'holiday_type' => $holiday->holitypes,
                    'is_holiday' => true
                ];
            } else {
                //In Time
                $inquery = Attendance::getAttendanceIn($requestedEmpId, $currentDate);
                $intime = $inquery->record_time ?? null;
                $in = $intime ? date('h:i:s A', strtotime($intime)) : '---';
                //Out Time
                $outquery = Attendance::getAttendanceOut($requestedEmpId, $currentDate);
                $outtime = $outquery->record_time ?? null;

                $outtimeRaw = $outtime ? Carbon::parse($outtime) : null;


                $out = $outtime ? date('h:i:s A', strtotime($outtime)) : '---';

                //Status
                $status = Attendance::getAttendanceStatus($requestedEmpId, $currentDate);
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
                    //$workHours = '00:00';
                    $statusname = 'Absent';
                }
                $getintime = $intime ? date('h:i:s', strtotime($intime)) : '---';
                $deduct = AttenDeduct::getDeductHour($sql->branch_id, $getintime);
                if ($deduct == 0) {
                    $deduct = '---';
                }

                if ($workHours && preg_match('/^(\d{1,2}):(\d{2})$/', $workHours)) {

                    list($h, $m) = explode(':', $workHours);
                    $workHoursInSeconds = ($h * 3600) + ($m * 60);

                    // Deduct hour format convert
                    if ($deduct && preg_match('/^(\d{1,2}):(\d{2})$/', $deduct)) {
                        list($dh, $dm) = explode(':', $deduct);
                        $deductSeconds = ($dh * 3600) + ($dm * 60);
                    } else {
                        $deductSeconds = 0;  // fallback if deduct is empty
                    }

                    // net seconds
                    $netSeconds = max(0, $workHoursInSeconds - $deductSeconds);

                    // convert to HH:MM
                    $nh = floor($netSeconds / 3600);
                    $nm = floor(($netSeconds % 3600) / 60);

                    $nethour = sprintf("%02d:%02d", $nh, $nm);
                } else {

                    $nethour = '---';
                }


                $leave = Leave::where('empid', $sql->id)->where('status', 3)->whereDate('fromdate', '<=', $currentDate)->whereDate('todate', '>=', $currentDate)->exists();
                if ($leave) {
                    $statusname = 'Leave';
                }

                if ($workHours && preg_match('/^(\d{1,2}):(\d{2})$/', $workHours)) {

                    // workhours HH:MM → seconds
                    list($h, $m) = explode(':', $workHours);
                    $workHoursInSeconds = ($h * 3600) + ($m * 60);

                    // deduct is integer hour (1, 2, 3...)
                    if (is_numeric($deduct)) {
                        $deductSeconds = $deduct * 3600;
                    } else {
                        $deductSeconds = 0;
                    }

                    // calculate net seconds
                    $netSeconds = max(0, $workHoursInSeconds - $deductSeconds);

                    // convert back to HH:MM
                    $nh = floor($netSeconds / 3600);
                    $nm = floor(($netSeconds % 3600) / 60);

                    $nethour = sprintf("%02d:%02d", $nh, $nm);
                } else {

                    $nethour = '---';
                }
                $reportData[] = [
                    'datename' => $displayDate,
                    'intime' => $in,
                    'outtime' => $out,
                    'deduct' => $deduct,
                    'nethour' => $nethour,
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
        $leaveCount = 0;
        $holidayCount = 0;
        $totalWorkSeconds = 0;
        $totalDeductSeconds = 0;
        $totalNetSeconds = 0;
        foreach ($reportData  as $day) {

            if ($day['status'] == 'Present') {
                $presentCount++;
            } elseif ($day['status'] == 'Late') {
                $lateCount++;
            } elseif ($day['status'] == 'Absent') {
                $absentCount++;
            } elseif ($day['status'] == 'Leave') {
                $leaveCount++;
            }

            if ($day['is_holiday']) {
                $holidayCount++;
                continue;
            }
            // Workhours sum
            if ($day['workhours'] !== '---' && preg_match('/^(\d{2}):(\d{2})$/', $day['workhours'], $m)) {
                $totalWorkSeconds += ($m[1] * 3600) + ($m[2] * 60);
            }
            // deduct sum
            if (!empty($day['deduct']) && is_numeric($day['deduct'])) {
                $totalDeductSeconds += $day['deduct'] * 3600;
            }
            // Net work sum
            if ($day['nethour'] !== '---' && preg_match('/^(\d{2}):(\d{2})$/', $day['nethour'], $n)) {
                $totalNetSeconds += ($n[1] * 3600) + ($n[2] * 60);
            }
        }
        // Total Work Hours output
        $totalHours = floor($totalWorkSeconds / 3600);
        $totalMinutes = floor(($totalWorkSeconds % 3600) / 60);
        $totalWorkHoursFormatted = sprintf("%02d:%02d", $totalHours, $totalMinutes);
        // Total deduct Hours output
        $dh = floor($totalDeductSeconds / 3600);
        $dm = floor(($totalDeductSeconds % 3600) / 60);
        $totalDeductFormatted = sprintf("%02d:%02d", $dh, $dm);
        // Total Net Hours output
        $absentSeconds = $absentCount * 8 * 3600;

        $leaveSeconds = $leaveCount * 8 * 3600;

        $finalNetSeconds = $totalNetSeconds  + $leaveSeconds - $absentSeconds;

        if ($finalNetSeconds < 0) {
            $finalNetSeconds = 0;
        }
        $fnH = floor($finalNetSeconds / 3600);
        $fnM = floor(($finalNetSeconds % 3600) / 60);

        $totalNetHoursFormatted = sprintf("%02d:%02d", $fnH, $fnM);

        $pdf = Pdf::loadView('exports.hrreports.employeeAttendance', [
            'employees' => $sql,
            'yearname' => Carbon::parse($request->yearname)->format('Y'),
            'monthname' => Carbon::createFromDate($request->yearname, $request->monthname, 1)->format('F'),
            'data'      => $reportData,
            'presentCount' => $presentCount,
            'lateCount' => $lateCount,
            'absentCount' => $absentCount,
            'leaveCount' => $leaveCount,
            'holidayCount' => $holidayCount,
            'totalWork' => $totalWorkHoursFormatted,
            'totalDeduct' => $totalDeductFormatted,
            'totalnetWork' => $totalNetHoursFormatted,
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 2,
                'margin-right'  => 5,
                'margin-bottom' => 2,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("EmployeeAttendance{$sql->name}.pdf");
    }


    public function DailyAttendance()
    {
        try {
            $this->authorize('hrReports.daily-attendance');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $authUser = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $authUser */
        $isSuperadmin = $authUser?->hasRole('superadmin') ?? false;
        $currentEmployee = $this->getAuthenticatedEmployee();

        $employeeQuery = PersonalInfo::where('active', 1);
        $branchQuery = Branch::where('active', 1);

        if (! $isSuperadmin) {
            if ($currentEmployee) {
                $employeeQuery->where('id', $currentEmployee->id);
                $branchQuery->where('id', $currentEmployee->branch_id);
            } else {
                $employeeQuery->whereRaw('1 = 0');
                $branchQuery->whereRaw('1 = 0');
            }
        }

        return Inertia::render('allpages/reports/hrreports/dailyattendance', [
            'employee' => $employeeQuery->get(),
            'currentEmployee' => $currentEmployee,
            'isSuperadmin' => $isSuperadmin,
            'branch' => $branchQuery->get(),
        ]);
    }

    public function DailyAttendanceReport(Request $request)
    {
        try {
            $this->authorize('hrReports.daily-attendance-reports');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $authUser = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $authUser */
        $isSuperadmin = $authUser?->hasRole('superadmin') ?? false;
        $currentEmployee = $this->getAuthenticatedEmployee();

        $requestedBranchId = $request->branch_id;
        $requestedEmployeeId = $request->empid;

        if (! $isSuperadmin) {
            abort_unless($currentEmployee, 403, 'Employee record not found for the current user.');
            $requestedBranchId = $currentEmployee->branch_id;
            $requestedEmployeeId = $currentEmployee->id;
        }

        $sql = '';
        if ($requestedEmployeeId) {
            $sql = PersonalInfo::with(['designation', 'department'])
                ->where('branch_id', $requestedBranchId)
                ->where('id', $requestedEmployeeId)
                ->where('active', 1)
                ->where(DB::raw("(date_format(joindate,'%Y-%m-%d'))"), '<=', $request->datename)
                ->orderBy('id', 'ASC')
                ->get();
        } else {
            $sql = PersonalInfo::with(['designation', 'department'])
                ->where('branch_id', $requestedBranchId)
                ->where('active', 1)
                ->where(DB::raw("(date_format(joindate,'%Y-%m-%d'))"), '<=', $request->datename)
                ->orderBy('id', 'ASC')
                ->get();
        }
        $branch = Branch::where('id', $requestedBranchId)->where('active', 1)->first();
        $reportData = [];

        foreach ($sql as  $value) {
            //In Time
            $inquery = Attendance::getAttendanceIn($value->empid, $request->datename);
            $intime = $inquery->record_time ?? null;
            $in = $intime ? date('h:i:s A', strtotime($intime)) : '---';
            //Out Time
            $outquery = Attendance::getAttendanceOut($value->empid, $request->datename);
            $outtime = $outquery->record_time ?? null;

            $outtimeRaw = $outtime ? Carbon::parse($outtime) : null;

            $out = $outtime ? date('h:i:s A', strtotime($outtime)) : '---';
            //Status
            $status = Attendance::getAttendanceStatus($value->empid, $request->datename);
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

            if ($outtimeRaw && $outtimeRaw->lt(Carbon::parse($request->datename . '15:00:00'))) {

                $statusname = 'Absent';
            }

            $reportData[] = [
                'empid' => $value->empid,
                'name' => $value->empname ?? '',
                'deptname' => $value->department->deptname ?? '',
                'desname' => $value->designation->desname ?? '',
                'intime' => $in,
                'outtime' => $out,
                'status' => $statusname,
                'workhours' => $workHours,
            ];
        }

        $pdf = Pdf::loadView('exports.hrreports.dailyAttendance', [
            'branch' => $branch,
            'date' => $request->datename,
            'outtime' => $outtime,
            'employees' => $reportData,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("DailyAttendance{$request->datename}.pdf");
    }


    public function MonthlyAttendance()
    {
        try {
            $this->authorize('hrReports.monthly-attendance');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/reports/hrreports/monthlyattendace', [
            'branch' => Branch::where('active', 1)->get(),
            'months' => collect($this->createMonth())
                ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                ->values()
                ->toArray(),
            'years' => collect($this->createYear())
                ->map(fn($name, $id) => ['id' => $id, 'name' => $name])
                ->values()
                ->toArray(),
        ]);
    }

    public function MonthlyAttendanceReport(Request $request)
    {
        try {
            $this->authorize('hrReports.monthly-attendance-reports');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        $branch = Branch::where('id', $request->branch_id)->where('active', 1)->first();
        $yearMonth = $request->yearname . '-' . str_pad($request->monthname, 2, '0', STR_PAD_LEFT);
        $sql = AttendanceStatus::with('employee.designation', 'employee.department')->where('branch_id', $request->branch_id)->where('yearname', $request->yearname)->where('monthname', $request->monthname)->get();

        //dd($sql);
        $pdf = Pdf::loadView('exports.hrreports.monthlyAttendance', [
            'branch' => $branch,
            'yearname' => Carbon::parse($request->yearname)->format('Y'),
            'monthname' => Carbon::createFromDate($request->yearname, $request->monthname, 1)->format('F'),
            'employees' => $sql,
        ])
            ->setPaper('a4', 'landscape')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("MonthlyAttendance{$yearMonth}.pdf");
    }

    public function createMonth()
    {
        $a = array();
        for ($i = 1; $i <= 12; $i++) {
            $a[$i] = date("F", mktime(0, 0, 0, $i, $i));
        }
        return $a;
    }

    public function createYear()
    {
        $a = array();
        for ($i = date('Y'); $i >= date('Y') - 5; $i--) {
            $a[$i] = $i;
        }
        return $a;
    }
}
