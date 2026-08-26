<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttendanceStatus;
use App\Http\Requests\AttendanceStatus\StoreAttendanceStatusRequest;
use App\Http\Requests\AttendanceStatus\UpdateAttendanceStatusRequest;
use App\Models\HRM\Attendance;
use App\Models\HRM\AttenDeduct;
use App\Models\HRM\Branch;
use App\Models\HRM\HolidayDt;
use App\Models\HRM\Leave;
use App\Models\HRM\PersonalInfo;
use App\Models\HRM\WorkHourSetup;
use Carbon\Carbon;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class AttendanceStatusController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('attendStatus.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        return Inertia::render('allpages/hrm/attendanceStatus', [
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

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            $this->authorize('attendStatus.index');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.',
            ]);
        }

        $branchId  = (int) $request->branch_id;
        $yearname  = (int) $request->yearname;
        $monthname = (int) $request->monthname;

        /*
    |--------------------------------------------------------------------------
    | Existing Attendance Status
    |--------------------------------------------------------------------------
    | Load existing monthly summary only once.
    |
    */
        $existingData = AttendanceStatus::where('branch_id', $branchId)
            ->where('yearname', $yearname)
            ->where('monthname', $monthname)
            ->get()
            ->keyBy('empid');

        $hasExistingData = $existingData->isNotEmpty();

        /*
    |--------------------------------------------------------------------------
    | Employees
    |--------------------------------------------------------------------------
    */
        $employees = PersonalInfo::with([
            'designation:id,desname',
            'department:id,deptname',
        ])
            ->where('branch_id', $branchId)
            ->where('active', 1)
            ->get([
                'id',
                'empid',
                'empname',
                'branch_id',
                'des_id',
                'dept_id',
            ]);

        /*
    |--------------------------------------------------------------------------
    | Month Date Range
    |--------------------------------------------------------------------------
    */
        $startDate = Carbon::create($yearname, $monthname, 1)->startOfMonth();
        $endDate   = $startDate->copy()->endOfMonth();

        $daysInMonth = $startDate->daysInMonth;

        /*
    |--------------------------------------------------------------------------
    | Holidays
    |--------------------------------------------------------------------------
    */
        $holidays = HolidayDt::whereBetween('holidate', [
            $startDate->toDateString(),
            $endDate->toDateString(),
        ])
            ->pluck('holitypes', 'holidate');

        /*
    |--------------------------------------------------------------------------
    | Working Hour Setup
    |--------------------------------------------------------------------------
    */
        $working_hour = WorkHourSetup::where('branch_id', $branchId)
            ->where('yearname', $yearname)
            ->where('monthname', $monthname)
            ->where('active', 1)
            ->first();

        if (!$working_hour) {
            return back()->with([
                'error' => true,
                'message' => 'No working hour found for this branch and month.',
            ]);
        }
        /*
    |--------------------------------------------------------------------------
    | Load Entire Month Attendance ONCE
    |--------------------------------------------------------------------------
    */
        $employeeEmpIds = $employees
            ->pluck('empid')
            ->filter()
            ->values();
        $attendanceData = DB::table('attendances as a')
            ->leftJoin(
                'personal_infos as pi',
                'a.user_id',
                '=',
                'pi.empid'
            )
            ->leftJoin(
                'atten_settings as ats',
                'pi.branch_id',
                '=',
                'ats.branch_id'
            )
            ->whereIn('a.user_id', $employeeEmpIds)
            ->whereBetween('a.record_time', [
                $startDate->copy()->startOfDay(),
                $endDate->copy()->endOfDay(),
            ])
            ->orderBy('a.user_id')
            ->orderBy('a.record_time')
            ->get([
                'a.user_id',
                'a.record_time',
                'ats.ptime',
                'ats.pname',
                'ats.ltime',
                'ats.lname',
            ]);
        /*
|--------------------------------------------------------------------------
| Create Fast Attendance Lookup
|--------------------------------------------------------------------------
*/
        $attendanceMap = [];

        foreach ($attendanceData as $attendance) {

            $date = Carbon::parse($attendance->record_time)
                ->toDateString();

            $key = $attendance->user_id . '|' . $date;

            $attendanceMap[$key][] = $attendance;
        }

        /*
    |--------------------------------------------------------------------------
    | Leaves
    |--------------------------------------------------------------------------
    |
    | Use employee IDs because Leave.empid is assumed to reference
    | PersonalInfo.id based on your existing code.
    |
    */
        $employeeIds = $employees->pluck('id');

        $allLeaves = Leave::whereIn('empid', $employeeIds)
            ->where('status', 3)
            ->whereDate('fromdate', '<=', $endDate)
            ->whereDate('todate', '>=', $startDate)
            ->get([
                'empid',
                'fromdate',
                'todate',
            ])
            ->groupBy('empid');

        /*
    |--------------------------------------------------------------------------
    | Local Caches
    |--------------------------------------------------------------------------
    |
    | Prevent duplicate method calls during this request.
    |
    */
        $allSummaryData = [];

        /*
    |--------------------------------------------------------------------------
    | Calculate Current Summary
    |--------------------------------------------------------------------------
    */
        foreach ($employees as $employee) {

            $presentCount = 0;
            $lateCount = 0;
            $absentCount = 0;
            $leaveCount = 0;

            $totalWorkSeconds = 0;
            $totalDeductSeconds = 0;
            $totalNetSeconds = 0;

            $employeeLeaves = $allLeaves->get(
                $employee->id,
                collect()
            );

            for ($i = 1; $i <= $daysInMonth; $i++) {

                $currentDate = sprintf(
                    '%04d-%02d-%02d',
                    $yearname,
                    $monthname,
                    $i
                );

                /*
        |--------------------------------------------------------------------------
        | Holiday
        |--------------------------------------------------------------------------
        */

                if ($holidays->has($currentDate)) {
                    continue;
                }


                /*
        |--------------------------------------------------------------------------
        | Get Attendance From Memory
        |--------------------------------------------------------------------------
        */

                $key = $employee->empid . '|' . $currentDate;

                $records = $attendanceMap[$key] ?? [];

                $intime = null;
                $outtime = null;
                $statusname = '---';

                if (!empty($records)) {

                    /*
            | First attendance = IN
            */
                    $firstRecord = $records[0];

                    /*
            | Last attendance = OUT
            */
                    $lastRecord = $records[count($records) - 1];

                    $intime = $firstRecord->record_time;
                    $outtime = $lastRecord->record_time;


                    /*
            |--------------------------------------------------------------------------
            | Attendance Status
            |--------------------------------------------------------------------------
            */

                    $time = Carbon::parse($intime)->format('H:i:s');

                    if (
                        $firstRecord->ptime &&
                        $time <= $firstRecord->ptime
                    ) {

                        $statusname = $firstRecord->pname;
                    } elseif (
                        $firstRecord->ltime &&
                        $time <= $firstRecord->ltime
                    ) {

                        $statusname = $firstRecord->lname;
                    } else {

                        $statusname = 'Absent';
                    }
                }


                /*
        |--------------------------------------------------------------------------
        | Work Hours
        |--------------------------------------------------------------------------
        */

                $workSeconds = 0;

                if (
                    $statusname !== 'Absent' &&
                    $intime &&
                    $outtime
                ) {

                    $workSeconds = max(
                        0,
                        strtotime($outtime) - strtotime($intime)
                    );

                    $totalWorkSeconds += $workSeconds;
                }


                /*
        |--------------------------------------------------------------------------
        | Out Before 3 PM = Absent
        |--------------------------------------------------------------------------
        */

                if (
                    $outtime &&
                    Carbon::parse($outtime)->lt(
                        Carbon::parse(
                            $currentDate . ' 15:00:00'
                        )
                    )
                ) {
                    $statusname = 'Absent';
                }


                /*
        |--------------------------------------------------------------------------
        | Leave
        |--------------------------------------------------------------------------
        */

                $isLeave = $employeeLeaves->contains(
                    function ($leave) use ($currentDate) {

                        return $currentDate >= $leave->fromdate
                            && $currentDate <= $leave->todate;
                    }
                );

                if ($isLeave) {
                    $statusname = 'Leave';
                }


                /*
        |--------------------------------------------------------------------------
        | Status Count
        |--------------------------------------------------------------------------
        */

                match ($statusname) {

                    'Present' => $presentCount++,

                    'Late' => $lateCount++,

                    'Absent' => $absentCount++,

                    'Leave' => $leaveCount++,

                    default => null,
                };


                /*
        |--------------------------------------------------------------------------
        | Deduct
        |--------------------------------------------------------------------------
        */

                $getintime = $intime
                    ? date('H:i:s', strtotime($intime))
                    : '---';

                $deduct = AttenDeduct::getDeductHour(
                    $employee->branch_id,
                    $getintime
                ) ?: 0;


                if (is_numeric($deduct)) {

                    $deductSeconds = (float) $deduct * 3600;
                } elseif (
                    is_string($deduct) &&
                    preg_match(
                        '/^(\d{1,2}):(\d{2})$/',
                        $deduct,
                        $matches
                    )
                ) {

                    $deductSeconds =
                        ($matches[1] * 3600)
                        + ($matches[2] * 60);
                } else {

                    $deductSeconds = 0;
                }


                $totalDeductSeconds += $deductSeconds;


                /*
        |--------------------------------------------------------------------------
        | Net Work
        |--------------------------------------------------------------------------
        */

                if ($workSeconds > 0) {

                    $totalNetSeconds += max(
                        0,
                        $workSeconds - $deductSeconds
                    );
                }
            }

            /*
        |--------------------------------------------------------------------------
        | Final Net Calculation
        |--------------------------------------------------------------------------
        */
            $finalNetSeconds = max(
                0,
                $totalNetSeconds
                    + ($leaveCount * 8 * 3600)
                    - ($absentCount * 8 * 3600)
            );

            /*
        |--------------------------------------------------------------------------
        | New Summary
        |--------------------------------------------------------------------------
        */
            $allSummaryData[$employee->empid] = [
                'empid'       => $employee->empid,
                'branch_id'   => $branchId,
                'yearname'    => $yearname,
                'monthname'   => $monthname,

                'workhour'    => $working_hour->workhour,

                'absent'      => $absentCount,
                'leave'       => $leaveCount,

                'totalhour'   => sprintf(
                    '%02d:%02d',
                    floor($totalWorkSeconds / 3600),
                    floor(($totalWorkSeconds % 3600) / 60)
                ),

                'deducthour'  => sprintf(
                    '%02d:%02d',
                    floor($totalDeductSeconds / 3600),
                    floor(($totalDeductSeconds % 3600) / 60)
                ),

                'nethour'     => sprintf(
                    '%02d:%02d',
                    floor($finalNetSeconds / 3600),
                    floor(($finalNetSeconds % 3600) / 60)
                ),

                'payablehour' => sprintf(
                    '%02d:%02d',
                    floor($finalNetSeconds / 3600),
                    floor(($finalNetSeconds % 3600) / 60)
                ),

                'active'      => '0',
                'user_id'     => Auth::id(),
                'updated_at'  => now(),
            ];
        }

        /*
    |--------------------------------------------------------------------------
    | Existing Data Comparison
    |--------------------------------------------------------------------------
    |
    | Only compare these calculated fields.
    |
    */
        $hasMismatch = false;
        $mismatchEmployees = [];

        $compareFields = [
            'workhour',
            'totalhour',
            'deducthour',
            'nethour',
            'absent',
            'leave',
        ];

        /*
    |--------------------------------------------------------------------------
    | Check New Employees / Changed Employees
    |--------------------------------------------------------------------------
    */
        foreach ($allSummaryData as $empid => $newData) {

            $oldData = $existingData->get($empid);

            /*
        | Existing database no record found
        */
            if (!$oldData) {

                $hasMismatch = true;

                $mismatchEmployees[] = $empid;

                continue;
            }

            /*
        | Compare calculated fields
        */
            foreach ($compareFields as $field) {

                $oldValue = (string) ($oldData->{$field} ?? '');
                $newValue = (string) ($newData[$field] ?? '');

                if ($oldValue !== $newValue) {

                    $hasMismatch = true;

                    $mismatchEmployees[] = $empid;

                    break;
                }
            }
        }

        /*
    |--------------------------------------------------------------------------
    | Check Old Employees Missing From Current Employees
    |--------------------------------------------------------------------------
    |
    | Example:
    | Old summary has EMP001
    | But current active employee list doesn't have EMP001.
    |
    */
        foreach ($existingData->keys() as $oldEmpId) {

            if (!isset($allSummaryData[$oldEmpId])) {

                $hasMismatch = true;

                $mismatchEmployees[] = $oldEmpId;
            }
        }

        $mismatchEmployees = array_values(
            array_unique($mismatchEmployees)
        );

        /*
    |--------------------------------------------------------------------------
    | EXISTING DATA + MISMATCH
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Do NOT update database.
    |
    */
        if ($hasExistingData && $hasMismatch) {

            return Inertia::render(
                'allpages/hrm/attendanceStatusview',
                [
                    'alldata' => AttendanceStatus::with([
                        'employee.designation',
                        'employee.department',
                    ])
                        ->where('branch_id', $branchId)
                        ->where('yearname', $yearname)
                        ->where('monthname', $monthname)
                        ->get(),

                    'monthname' => $monthname,
                    'yearname'   => $yearname,
                    'branch_id'  => $branchId,

                    'hasExistingData' => true,
                    'dataMismatch'    => true,

                    'mismatchEmployees' => $mismatchEmployees,

                    'error' => true,

                    'message' =>
                    'Attendance Status for this month has already been generated, ' .
                        'but some information does not match the current Attendance Data.' .
                        'Please delete the existing data and generate it again.',
                ]
            );
        }

        /*
    |--------------------------------------------------------------------------
    | EXISTING DATA + EVERYTHING SAME
    |--------------------------------------------------------------------------
    |
    | No need to update database.
    |
    */
        if ($hasExistingData) {

            return Inertia::render(
                'allpages/hrm/attendanceStatusview',
                [
                    'alldata' => AttendanceStatus::with([
                        'employee.designation',
                        'employee.department',
                    ])
                        ->where('branch_id', $branchId)
                        ->where('yearname', $yearname)
                        ->where('monthname', $monthname)
                        ->get(),

                    'monthname' => $monthname,
                    'yearname'   => $yearname,
                    'branch_id'  => $branchId,

                    'hasExistingData' => true,
                    'dataMismatch'    => false,

                    'error' => false,
                ]
            );
        }

        /*
    |--------------------------------------------------------------------------
    | NO EXISTING DATA
    |--------------------------------------------------------------------------
    |
    | First time generation.
    |
    */
        AttendanceStatus::upsert(
            array_values($allSummaryData),
            [
                'empid',
                'branch_id',
                'yearname',
                'monthname',
            ],
            [
                'workhour',
                'totalhour',
                'deducthour',
                'nethour',
                'payablehour',
                'absent',
                'leave',
                'active',
                'user_id',
                'updated_at',
            ]
        );

        /*
    |--------------------------------------------------------------------------
    | Final Response
    |--------------------------------------------------------------------------
    */
        return Inertia::render(
            'allpages/hrm/attendanceStatusview',
            [
                'alldata' => AttendanceStatus::with([
                    'employee.designation',
                    'employee.department',
                ])
                    ->where('branch_id', $branchId)
                    ->where('yearname', $yearname)
                    ->where('monthname', $monthname)
                    ->get(),

                'monthname' => $monthname,
                'yearname'   => $yearname,
                'branch_id'  => $branchId,

                'hasExistingData' => false,
                'dataMismatch'    => false,

                'error' => false,
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttendanceStatus $attendanceStatus)
    {
        try {
            $this->authorize('attendStatus.update');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to access this page.',
            ]);
        }

        if (!$attendanceStatus->exists) {
            return back()->with([
                'error' => true,
                'message' => 'Attendance record not found.',
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Calculate Payable Hour
    |--------------------------------------------------------------------------
    */
        $payableMinutes = $this->toMinutesFlexible(
            $attendanceStatus->nethour
        );

        $inputMinutes = $this->toMinutesFlexible(
            $request->hrsurplus
        );

        $totalMinutes = $payableMinutes + $inputMinutes;

        $finalHHMM = $this->minutesToHHMM($totalMinutes);

        /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */
        $updated = $attendanceStatus->update([
            'hrsurplus'   => $request->hrsurplus,
            'payablehour' => $finalHHMM,
            'active' => 1,
            'updated_at'  => now(),
        ]);

        if (!$updated) {
            return back()->with([
                'error' => true,
                'message' => 'Failed to update attendance record.',
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | Redirect back to Attendance Status page
    |--------------------------------------------------------------------------
    */
        return back()->with([
            'success' => true,
            'message' => 'Attendance record updated successfully.',
            'updated_record' => [
                'id' => $attendanceStatus->id,
                'hrsurplus' => $attendanceStatus->hrsurplus,
                'payablehour' => $attendanceStatus->payablehour,
            ],
        ]);
    }


    private function toMinutesFlexible($value)
    {
        if (is_null($value) || $value === '') return 0;

        // If already numeric type (int/float)
        if (is_numeric($value)) {
            // as string to inspect decimal part
            $s = (string)$value;
            if (strpos($s, '.') === false) {
                // integer -> hours
                return intval($s) * 60;
            }

            // has dot
            $parts = explode('.', $s);
            $intPart = intval($parts[0]);
            $fracPartRaw = $parts[1] ?? '0';

            // if fractional part length == 2 (like .37) and numeric and <=59 → likely HH.MM where .MM are minutes
            if (ctype_digit($fracPartRaw) && strlen($fracPartRaw) <= 2) {
                $fracInt = intval(str_pad($fracPartRaw, 2, '0', STR_PAD_RIGHT)); // e.g. .3 -> 30
                if ($fracInt >= 0 && $fracInt <= 59) {
                    return $intPart * 60 + $fracInt;
                }
            }

            // else treat as decimal hours: 11.12 -> 11 hours + 0.12*60 minutes
            $decimal = floatval($value);
            $hours = floor($decimal);
            $minutes = round(($decimal - $hours) * 60);
            return $hours * 60 + $minutes;
        }

        // If it's a string
        $str = trim($value);

        // If format HH:MM
        if (strpos($str, ':') !== false) {
            [$h, $m] = array_map('intval', array_pad(explode(':', $str), 2, 0));
            return ($h * 60) + $m;
        }

        // If contains dot but not numeric (rare)
        if (strpos($str, '.') !== false) {
            // try numeric parse fallback
            if (is_numeric($str)) {
                return $this->toMinutesFlexible((float)$str);
            }
            // else try split
            [$h, $m] = array_map('intval', array_pad(explode('.', $str), 2, 0));
            if ($m >= 0 && $m <= 59) {
                return ($h * 60) + $m;
            }
            // fallback numeric cast
            return $this->toMinutesFlexible(floatval($str));
        }

        // If plain integer string
        if (ctype_digit($str)) {
            return intval($str) * 60;
        }

        // final fallback: try float cast
        if (is_numeric($str)) {
            return $this->toMinutesFlexible(floatval($str));
        }

        // unknown format
        return 0;
    }

    /**
     * Convert total minutes to "H:MM" or "HH:MM"
     */
    private function minutesToHHMM($minutes)
    {
        $minutes = max(0, intval(round($minutes)));
        $h = floor($minutes / 60);
        $m = $minutes % 60;
        return sprintf('%d:%02d', $h, $m); // use %d for hours (can be >99)
    }


    public function destroy(Request $request)
    {
        try {
            $this->authorize('attendStatus.destroy');
        } catch (AuthorizationException $e) {
            return back()->with([
                'error' => true,
                'message' => 'You are not authorized to delete attendance status.',
            ]);
        }

        $branchId  = (int) $request->branch_id;
        $yearname  = (int) $request->yearname;
        $monthname = (int) $request->monthname;

        $deleted = AttendanceStatus::where('branch_id', $branchId)
            ->where('yearname', $yearname)
            ->where('monthname', $monthname)
            ->delete();

        return back()->with([
            'error' => false,
            'message' => $deleted
                ? 'Attendance Status data deleted successfully. You can generate it again.'
                : 'No Attendance Status data found to delete.',
        ]);
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
