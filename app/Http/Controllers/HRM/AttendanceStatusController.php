<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\AttendanceStatus;
use App\Http\Requests\AttendanceStatus\StoreAttendanceStatusRequest;
use App\Http\Requests\AttendanceStatus\UpdateAttendanceStatusRequest;
use App\Models\HRM\Attendance;
use App\Models\HRM\AttenDeduct;
use App\Models\HRM\Branch;
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
        $yearmonth = $request->yearname . '-' . $request->monthname;
        $sql = PersonalInfo::with(['designation', 'department'])->where('branch_id', $request->branch_id)->where('active', 1)->where(DB::raw("(date_format(joindate,'%Y-%m'))"), '<=', $yearmonth)->orderBy('id', 'ASC')->get();

        $daysInMonth = date('t', mktime(0, 0, 0, $request->monthname, 1, $request->yearname));
        $allSummary = [];
        foreach ($sql as $key) {
            $reportData = [];
            $working = WorkHourSetup::select('workhour')->where('branch_id', $request->branch_id)->where('yearname', $request->yearname)->where('monthname', $request->monthname)->where('active', 1)->first();
            for ($i = 1; $i <= $daysInMonth; $i++) {
                $values = str_pad($i, 2, '0', STR_PAD_LEFT);
                $currentDate = $request->yearname . '-' . $request->monthname . '-' . $values;
                //In Time
                $inquery = Attendance::getAttendanceIn($key->empid, $currentDate);
                $intime = $inquery->record_time ?? null;
                $in = $intime ? date('h:i:s A', strtotime($intime)) : '---';
                //Out Time
                $outquery = Attendance::getAttendanceOut($key->empid, $currentDate);
                $outtime = $outquery->record_time ?? null;

                $outtimeRaw = $outtime ? Carbon::parse($outtime) : null;


                $out = $outtime ? date('h:i:s A', strtotime($outtime)) : '---';

                //Status
                $status = Attendance::getAttendanceStatus($key->empid, $currentDate);
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
                $deduct = AttenDeduct::getDeductHour($request->branch_id, $getintime);

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


                $leave = Leave::where('empid', $key->id)->where('status', 3)->whereDate('fromdate', '<=', $currentDate)->whereDate('todate', '>=', $currentDate)->exists();
                if ($leave) {
                    $statusname = 'Leave';
                }


                $reportData[] = [
                    'empname' => $key->name,
                    'designation' => $key->designation->desname,
                    'department' => $key->department->deptname,
                    'intime' => $in,
                    'outtime' => $out,
                    'working' => $working->workhour ?? '---',
                    'deduct' => $deduct,
                    'nethour' => $nethour,
                    'status' => $statusname,
                    'workhours' => $workHours,
                ];
            }

            // Summary Calculation
            $presentCount = 0;
            $lateCount = 0;
            $absentCount = 0;
            $leaveCount = 0;
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

            $finalNetSeconds = $totalNetSeconds - $totalDeductSeconds + $leaveSeconds - $absentSeconds;

            if ($finalNetSeconds < 0) {
                $finalNetSeconds = 0;
            }
            $fnH = floor($finalNetSeconds / 3600);
            $fnM = floor(($finalNetSeconds % 3600) / 60);

            $totalNetHoursFormatted = sprintf("%02d:%02d", $fnH, $fnM);


            $allSummary[] = [
                'empid'       => $key->empid,
                'name'        => $key->empname,
                'department'  => $key->department->deptname,
                'designation'  => $key->designation->desname,
                'present'     => $presentCount,
                'late'        => $lateCount,
                'absent'      => $absentCount,
                'leave'       => $leaveCount,
                'working'     => $working,
                'total_work'  => $totalWorkHoursFormatted,
                'total_deduct' => $totalDeductFormatted,
                'total_net'   => $totalNetHoursFormatted,
            ];
        }
        foreach ($allSummary as $value) {
            $totalhour = $value['total_work'];
            $deducthour = $value['total_deduct'];
            $nethour = $value['total_net'];
            $payablehour = $nethour;

            $chk = AttendanceStatus::where('empid', $value['empid'])->where('branch_id', $request->branch_id)->where('yearname', $request->yearname)->where('monthname', $request->monthname)->exists();

            if (!$chk) {
                AttendanceStatus::create([
                    'empid' => $value['empid'],
                    'branch_id' => $request->branch_id,
                    'yearname' => $request->yearname,
                    'monthname' => $request->monthname,
                    'workhour' => $value['working']['workhour'] ?? '---',
                    'totalhour' => $totalhour,
                    'deducthour' => $deducthour,
                    'absent' => $value['absent'],
                    'leave' => $value['leave'],
                    'nethour' => $nethour,
                    'hrsurplus' => null,
                    'payablehour' => $payablehour,
                    'salary' => null,
                    'payment' => null,
                    'active' => 0,
                    'user_id' => Auth::id()
                ]);
            }
        }


        return Inertia::render('allpages/hrm/attendanceStatusview', [
            'alldata' => AttendanceStatus::with(['employee.designation', 'employee.department'])->where('branch_id', $request->branch_id)->where('yearname', $request->yearname)->where('monthname', $request->monthname)->get()
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
        $sql = AttendanceStatus::find($request->id);
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
