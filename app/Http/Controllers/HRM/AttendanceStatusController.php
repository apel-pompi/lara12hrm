<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttendanceStatus;
use App\Http\Requests\AttendanceStatus\StoreAttendanceStatusRequest;
use App\Http\Requests\AttendanceStatus\UpdateAttendanceStatusRequest;
use App\Models\HRM\Attendance;
use App\Models\HRM\AttenDeduct;
use App\Models\HRM\AttenSetting;
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
use Illuminate\Support\Facades\Cache;
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
        $branchId  = $request->branch_id;
        $yearname  = $request->yearname;
        $monthname = $request->monthname;
        $authUserId = Auth::id();

        $employees = PersonalInfo::with(['designation', 'department'])
            ->where('branch_id', $branchId)
            ->where('active', 1)
            ->orderBy('id', 'ASC')
            ->get();

        $daysInMonth = date('t', mktime(0, 0, 0, $monthname, 1, $yearname));

        $dates = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dates[] = sprintf('%04d-%02d-%02d', $yearname, $monthname, $i);
        }

        $empidList = $employees->pluck('empid')->toArray();

        // 1. Load all holidays for the month in one query
        $holidays = HolidayDt::whereIn('holidate', $dates)
            ->pluck('holitypes')
            ->toArray();

        // 2. Load all attendance records for these employees in this month (in/out/first-time)
        $attendanceDaily = DB::table('attendances as a')
            ->select(
                'a.user_id as empid',
                DB::raw('DATE(a.record_time) as att_date'),
                DB::raw('MIN(a.record_time) as in_time'),
                DB::raw('MAX(a.record_time) as out_time'),
                DB::raw('TIME(MIN(a.record_time)) as first_time')
            )
            ->whereIn('a.user_id', $empidList)
            ->whereIn(DB::raw('DATE(a.record_time)'), $dates)
            ->groupBy('a.user_id', DB::raw('DATE(a.record_time)'))
            ->get();

        $attendanceIndex = [];
        foreach ($attendanceDaily as $row) {
            $attendanceIndex[$row->empid . '_' . $row->att_date] = $row;
        }

        // 3. Load all approved leaves for these employees in this month
        $leaves = DB::table('leaves')
            ->select('empid', 'fromdate', 'todate')
            ->where('status', 3)
            ->whereIn('empid', $empidList)
            ->get();

        $leaveRangesByEmp = [];
        foreach ($leaves as $leave) {
            $leaveRangesByEmp[$leave->empid][] = [
                'from' => $leave->fromdate,
                'to' => $leave->todate,
            ];
        }

        // 4. Load all active deduction rules for the branch
        $deductRules = AttenDeduct::where('active', 1)
            ->where('branch_id', $branchId)
            ->get()
            ->groupBy('branch_id');

        // 5. Load active attendance settings for the branch
        $attenSetting = AttenSetting::where('active', 1)
            ->where('branch_id', $branchId)
            ->first();

        // 6. Load existing records to preserve payablehour/hrsurplus & avoid duplicate checks
        $existingRecords = AttendanceStatus::where('branch_id', $branchId)
            ->where('yearname', $yearname)
            ->where('monthname', $monthname)
            ->get()
            ->keyBy('empid');

        $newRecords = [];
        $updateRecords = [];

        foreach ($employees as $emp) {
            $presentCount       = 0;
            $lateCount          = 0;
            $absentCount        = 0;
            $leaveCount         = 0;
            $holidayCount       = 0;
            $totalWorkSeconds   = 0;
            $totalDeductSeconds = 0;
            $totalNetSeconds    = 0;

            $empid            = $emp->empid;
            $empLeaveRanges   = $leaveRangesByEmp[$empid] ?? [];
            $empDeductRules   = $deductRules->get($emp->branch_id, collect());
            $settings         = $attenSetting;

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $currentDate = $yearname . '-' . $monthname . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);

                if (isset($holidays[$currentDate])) {
                    $holidayCount++;
                    continue;
                }

                $key     = $empid . '_' . $currentDate;
                $attRow  = $attendanceIndex[$key] ?? null;

                $intime     = $attRow->in_time ?? null;
                $outtime    = $attRow->out_time ?? null;
                $firstTime  = $attRow->first_time ?? null;
                $outtimeRaw = $outtime ? Carbon::parse($outtime) : null;

                $statusname = 'Absent';
                if ($intime && $settings) {
                    if ($firstTime <= $settings->ptime) {
                        $statusname = $settings->pname ?? 'Present';
                    } elseif ($firstTime <= $settings->ltime) {
                        $statusname = $settings->lname ?? 'Late';
                    } else {
                        $statusname = 'Absent';
                    }
                }

                $workHours = '---';
                if ($statusname != 'Absent' && $intime && $outtime) {
                    $diffSeconds = strtotime($outtime) - strtotime($intime);
                    $workHours   = sprintf('%02d:%02d', floor($diffSeconds / 3600), floor(($diffSeconds % 3600) / 60));
                }

                if ($outtimeRaw && $outtimeRaw->lt(Carbon::parse($currentDate . ' 15:00:00'))) {
                    $statusname = 'Absent';
                }

                $deduct = '---';
                if ($intime) {
                    $getintime = date('h:i:s', strtotime($intime));
                    foreach ($empDeductRules as $rule) {
                        if ($getintime >= $rule->starttime && $getintime <= $rule->endtime) {
                            $deduct = $rule->deduct;
                            break;
                        }
                    }
                    if ($deduct == 0) {
                        $deduct = '---';
                    }
                }

                $onLeave = false;
                foreach ($empLeaveRanges as $range) {
                    if ($currentDate >= $range['from'] && $currentDate <= $range['to']) {
                        $onLeave = true;
                        break;
                    }
                }
                if ($onLeave) {
                    $statusname = 'Leave';
                }

                if ($statusname == 'Present')      $presentCount++;
                elseif ($statusname == 'Late')     $lateCount++;
                elseif ($statusname == 'Absent')   $absentCount++;
                elseif ($statusname == 'Leave')    $leaveCount++;

                if ($workHours && preg_match('/^(\d{1,2}):(\d{2})$/', $workHours)) {
                    [$h, $m] = explode(':', $workHours);
                    $workSec = ($h * 3600) + ($m * 60);

                    if (is_numeric($deduct)) {
                        $deductSec = $deduct * 3600;
                    } elseif ($deduct && preg_match('/^(\d{1,2}):(\d{2})$/', $deduct)) {
                        [$dh, $dm] = explode(':', $deduct);
                        $deductSec = ($dh * 3600) + ($dm * 60);
                    } else {
                        $deductSec = 0;
                    }

                    $netSec = max(0, $workSec - $deductSec);
                    $totalWorkSeconds   += $workSec;
                    $totalDeductSeconds += $deductSec;
                    $totalNetSeconds    += $netSec;
                }
            }

            $totalWorkFormatted   = sprintf('%02d:%02d', floor($totalWorkSeconds / 3600),   floor(($totalWorkSeconds % 3600) / 60));
            $totalDeductFormatted = sprintf('%02d:%02d', floor($totalDeductSeconds / 3600), floor(($totalDeductSeconds % 3600) / 60));

            $absentSec       = $absentCount * 8 * 3600;
            $leaveSec        = $leaveCount  * 8 * 3600;
            $finalNetSeconds = max(0, $totalNetSeconds + $leaveSec - $absentSec);
            $netFormatted    = sprintf('%02d:%02d', floor($finalNetSeconds / 3600), floor(($finalNetSeconds % 3600) / 60));

            $workDays        = $daysInMonth - $holidayCount;
            $workhourSec     = $workDays * 8 * 3600;
            $workhourFormatted = sprintf('%02d:%02d', floor($workhourSec / 3600), floor(($workhourSec % 3600) / 60));

            $updates = [
                'workhour'    => $workhourFormatted,
                'totalhour'   => $totalWorkFormatted,
                'deducthour'  => $totalDeductFormatted,
                'nethour'     => $netFormatted,
                'absent'      => $absentCount,
                'leave'       => $leaveCount,
                'active'      => 1,
                'user_id'     => $authUserId,
            ];

            $existing = AttendanceStatus::where('empid', $empid)
                ->where('branch_id', $branchId)
                ->where('yearname', $yearname)
                ->where('monthname', $monthname)
                ->first();

            if ($existing) {
                $existing->update($updates);
            } else {
                AttendanceStatus::create(array_merge($updates, [
                    'empid'       => $empid,
                    'branch_id'   => $branchId,
                    'yearname'    => $yearname,
                    'monthname'   => $monthname,
                    'payablehour' => $netFormatted,
                ]));
            }
        }

        return Inertia::render('allpages/hrm/attendanceStatusview', [
            'alldata' => AttendanceStatus::with(['employee.designation', 'employee.department'])
                ->where('branch_id', $branchId)
                ->where('yearname', $yearname)
                ->where('monthname', $monthname)
                ->get(),
            'monthname' => (int) $monthname,
            'yearname'  => (int) $yearname,
            'branch_id' => (int) $branchId,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAttendanceStatusRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AttendanceStatus $attendanceStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AttendanceStatus $attendanceStatus)
    {
        //
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
                'message' => 'You are not authorized to access this page.'
            ]);
        }

        // Load record
        $sql = AttendanceStatus::find($attendanceStatus->id);
        if (! $sql) {
            return back()->with(['error' => true, 'message' => 'Record not found']);
        }

        // Normalize both values to minutes
        $payableMinutes = $this->toMinutesFlexible($sql->nethour);   // handles "198:37", "198.37", 198.37, "198"
        $inputMinutes   = $this->toMinutesFlexible($request->hrsurplus); // handles "11", "11.12", "10:12", "10.12", etc.

        // Add minutes
        $totalMinutes = $payableMinutes + $inputMinutes;

        // Convert back to HH:MM
        $finalHHMM = $this->minutesToHHMM($totalMinutes);

        // Update DB (you can store hrsurplus raw input or normalized string as needed)
        $attendanceStatus->update([
            'hrsurplus'   => $request->hrsurplus,   // original input (optional)
            'payablehour' => $finalHHMM,
        ]);

        return redirect()->route('attendanceStatus.create', [
            'branch_id' => $request->branch_id,
            'monthname' => $request->monthname,
            'yearname'  => $request->yearname,
        ])->with('success', 'Pay slip successfully updated');
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


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttendanceStatus $attendanceStatus)
    {
        //
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
