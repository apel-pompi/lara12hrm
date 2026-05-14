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
        $branchId  = $request->branch_id;
        $yearname  = $request->yearname;
        $monthname = $request->monthname;
        $yearmonth = $yearname . '-' . $monthname;
        $monthPad  = str_pad($monthname, 2, '0', STR_PAD_LEFT);
        $startDate = "{$yearname}-{$monthPad}-01";
        $daysInMonth = (int) date('t', mktime(0, 0, 0, $monthname, 1, $yearname));
        $endDate   = "{$yearname}-{$monthPad}-" . str_pad($daysInMonth, 2, '0', STR_PAD_LEFT);

        // ── 1. Employees ────────────────────────────────────────────────────
        $employees = PersonalInfo::with(['designation', 'department'])
            ->where('branch_id', $branchId)
            ->where('active', 1)
            ->where(DB::raw("(date_format(joindate,'%Y-%m'))"), '<=', $yearmonth)
            ->orderBy('id', 'ASC')
            ->get();

        $empIds   = $employees->pluck('empid')->toArray(); // device/attendance user_id
        $empPkIds = $employees->pluck('id')->toArray();    // PK used in Leave.empid

        // ── 2. Work hour setup (ONCE) ────────────────────────────────────────
        $working = WorkHourSetup::select('workhour')
            ->where('branch_id', $branchId)
            ->where('yearname', $yearname)
            ->where('monthname', $monthname)
            ->where('active', 1)
            ->first();

        // ── 3. Attendance setting (ONCE) ─────────────────────────────────────
        $attenSetting = DB::table('atten_settings')
            ->where('branch_id', $branchId)
            ->first();

        // ── 4. Deduct rules (ONCE) ───────────────────────────────────────────
        $deductRules = AttenDeduct::where('active', 1)
            ->where('branch_id', $branchId)
            ->get();

        // ── 5. All attendance for the month (ONCE) ───────────────────────────
        // Group by: empid_date  →  collection of records
        $allAttendances = DB::table('attendances')
            ->whereIn('user_id', $empIds)
            ->whereDate('record_time', '>=', $startDate)
            ->whereDate('record_time', '<=', $endDate)
            ->get()
            ->groupBy(fn($r) => $r->user_id . '_' . date('Y-m-d', strtotime($r->record_time)));

        // ── 6. All approved leaves for the month (ONCE) ──────────────────────
        $allLeaves = Leave::where('status', 3)
            ->whereIn('empid', $empPkIds)
            ->where('fromdate', '<=', $endDate)
            ->where('todate',   '>=', $startDate)
            ->get();

        // ── Helpers ──────────────────────────────────────────────────────────
        $getStatus = function (?string $intime) use ($attenSetting): string {
            if (!$attenSetting || !$intime) return 'Absent';
            $t = date('H:i:s', strtotime($intime));
            if ($t <= $attenSetting->ptime) return $attenSetting->pname;
            if ($t <= $attenSetting->ltime) return $attenSetting->lname;
            return 'Absent';
        };

        $getDeductSeconds = function (?string $intime) use ($deductRules): int {
            if (!$intime) return 0;
            $t = date('h:i:s', strtotime($intime));
            $rule = $deductRules->first(
                fn($r) => $r->starttime <= $t && $r->endtime >= $t
            );
            if (!$rule || !$rule->deduct) return 0;
            if (preg_match('/^(\d{1,2}):(\d{2})$/', $rule->deduct, $m)) {
                return ($m[1] * 3600) + ($m[2] * 60);
            }
            return is_numeric($rule->deduct) ? (int)($rule->deduct * 3600) : 0;
        };

        $hasLeave = function (int $empPkId, string $date) use ($allLeaves): bool {
            return $allLeaves->contains(
                fn($l) => $l->empid == $empPkId && $l->fromdate <= $date && $l->todate >= $date
            );
        };

        // ── Process each employee ─────────────────────────────────────────────
        $allSummary = [];

        foreach ($employees as $key) {
            $presentCount = $lateCount = $absentCount = $leaveCount = 0;
            $totalWorkSec = $totalDeductSec = $totalNetSec = 0;

            for ($i = 1; $i <= $daysInMonth; $i++) {
                $dayPad      = str_pad($i, 2, '0', STR_PAD_LEFT);
                $currentDate = "{$yearname}-{$monthPad}-{$dayPad}";
                $mapKey      = $key->empid . '_' . $currentDate;

                $dayRecords = $allAttendances->get($mapKey, collect());
                $intime     = optional($dayRecords->sortBy('record_time')->first())->record_time;
                $outtime    = optional($dayRecords->sortByDesc('record_time')->first())->record_time;

                $statusname = $intime ? $getStatus($intime) : 'Absent';

                // Out before 15:00 → Absent
                if ($outtime && Carbon::parse($outtime)->lt(Carbon::parse($currentDate . ' 15:00:00'))) {
                    $statusname = 'Absent';
                }

                // Leave override
                if ($hasLeave($key->id, $currentDate)) {
                    $statusname = 'Leave';
                }

                // Work seconds
                $workSec = 0;
                if ($statusname !== 'Absent' && $intime && $outtime) {
                    $workSec = max(0, strtotime($outtime) - strtotime($intime));
                }

                // Deduct seconds
                $deductSec = $getDeductSeconds($intime);

                // Net seconds
                $netSec = max(0, $workSec - $deductSec);

                // Counts
                match ($statusname) {
                    'Present' => $presentCount++,
                    'Late'    => $lateCount++,
                    'Absent'  => $absentCount++,
                    'Leave'   => $leaveCount++,
                    default   => null,
                };

                $totalWorkSec   += $workSec;
                $totalDeductSec += $deductSec;
                $totalNetSec    += $netSec;
            }

            // Format totals
            $fmt = fn(int $s) => sprintf('%02d:%02d', floor($s / 3600), floor(($s % 3600) / 60));

            $absentSec      = $absentCount * 8 * 3600;
            $leaveSec       = $leaveCount  * 8 * 3600;
            $finalNetSec    = max(0, $totalNetSec - $totalDeductSec + $leaveSec - $absentSec);

            $allSummary[] = [
                'empid'        => $key->empid,
                'absent'       => $absentCount,
                'leave'        => $leaveCount,
                'working'      => $working,
                'total_work'   => $fmt($totalWorkSec),
                'total_deduct' => $fmt($totalDeductSec),
                'total_net'    => $fmt($finalNetSec),
            ];
        }

        // ── Bulk insert new records ───────────────────────────────────────────
        $existingEmpIds = AttendanceStatus::where('branch_id', $branchId)
            ->where('yearname', $yearname)
            ->where('monthname', $monthname)
            ->pluck('empid')
            ->toArray();

        $toInsert = [];
        foreach ($allSummary as $value) {
            if (!in_array($value['empid'], $existingEmpIds)) {
                $toInsert[] = [
                    'empid'       => $value['empid'],
                    'branch_id'   => $branchId,
                    'yearname'    => $yearname,
                    'monthname'   => $monthname,
                    'workhour'    => $value['working']->workhour ?? '---',
                    'totalhour'   => $value['total_work'],
                    'deducthour'  => $value['total_deduct'],
                    'absent'      => $value['absent'],
                    'leave'       => $value['leave'],
                    'nethour'     => $value['total_net'],
                    'hrsurplus'   => null,
                    'payablehour' => $value['total_net'],
                    'salary'      => null,
                    'payment'     => null,
                    'active'      => 0,
                    'user_id'     => Auth::id(),
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ];
            }
        }

        if (!empty($toInsert)) {
            AttendanceStatus::insert($toInsert);
        }

        return Inertia::render('allpages/hrm/attendanceStatusview', [
            'alldata' => AttendanceStatus::with(['employee.designation', 'employee.department'])
                ->where('branch_id', $branchId)
                ->where('yearname', $yearname)
                ->where('monthname', $monthname)
                ->get(),
            'monthname' => $monthname,
            'yearname'  => $yearname,
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
