<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\HRM\Attendance;
use App\Models\HRM\Branch;
use App\Models\HRM\HolidayDt;
use App\Models\HRM\PersonalInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;
use Carbon\Carbon;

class HRreportsController extends Controller
{

    public function index()
    {

        return Inertia::render('allpages/reports/hrreports/index', [
            'employee' => PersonalInfo::where('active', 1)->get()
        ]);
    }

    public function EmpInfoReport(Request $request) {}
    public function EmployeeAttendance()
    {

        return Inertia::render('allpages/reports/hrreports/employeeattendance', [
            'employee' => PersonalInfo::where('active', 1)->get(),
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
        $sql = PersonalInfo::with(['designation', 'department'])->where('empid', $request->empid)->where('active', 1)->first();

        $reportData = [];
        for ($i = 1; $i <= cal_days_in_month(CAL_GREGORIAN, $request->monthname, $request->yearname); $i++) {
            $values = str_pad($i, 2, '0', STR_PAD_LEFT);
            $currentDate = $request->yearname . '-' . $request->monthname . '-' . $values;
            $displayDate = $values . '-' . $request->monthname . '-' . $request->yearname;
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
                $inquery = Attendance::getAttendanceIn($request->empid, $currentDate);
                $intime = $inquery->record_time ?? null;
                $in = $intime ? date('h:i:s A', strtotime($intime)) : '---';
                //Out Time
                $outquery = Attendance::getAttendanceOut($request->empid, $currentDate);
                $outtime = $outquery->record_time ?? null;
                $out = $outtime ? date('h:i:s A', strtotime($outtime)) : '---';
                //Status
                $status = Attendance::getAttendanceStatus($request->empid, $currentDate);
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


        $pdf = Pdf::loadView('exports.hrreports.employeeAttendance', [
            'employees' => $sql,
            'yearname' => Carbon::parse($request->yearname)->format('Y'),
            'monthname' => Carbon::createFromDate($request->yearname, $request->monthname, 1)->format('F'),
            'data'      => $reportData
        ])
            ->setPaper('a4', 'portrait')
            ->setOption([
                'margin-top'    => 5,
                'margin-right'  => 5,
                'margin-bottom' => 5,
                'margin-left'   => 5,
            ]);;

        return $pdf->stream("EmployeeAttendance{$sql->name}.pdf");
    }


    public function DailyAttendance()
    {

        return Inertia::render('allpages/reports/hrreports/dailyattendance', [
            'employee' => PersonalInfo::where('active', 1)->get(),
            'branch' => Branch::where('active', 1)->get(),
        ]);
    }

    public function DailyAttendanceReport(Request $request)
    {

        $sql = '';
        if ($request->empid) {
            $sql = PersonalInfo::with(['designation', 'department'])->where('branch_id', $request->branch_id)->where('id', $request->empid)->where('active', 1)->where(DB::raw("(date_format(joindate,'%Y-%m-%d'))"), '<=', $request->datename)->orderBy('id', 'ASC')->get();
        } else {
            $sql = PersonalInfo::with(['designation', 'department'])->where('branch_id', $request->branch_id)->where('active', 1)->where(DB::raw("(date_format(joindate,'%Y-%m-%d'))"), '<=', $request->datename)->orderBy('id', 'ASC')->get();
        }
        $branch = Branch::where('id', $request->branch_id)->where('active', 1)->first();
        $reportData = [];

        foreach ($sql as  $value) {
            //In Time
            $inquery = Attendance::getAttendanceIn($value->empid, $request->datename);
            $intime = $inquery->record_time ?? null;
            $in = $intime ? date('h:i:s A', strtotime($intime)) : '---';
            //Out Time
            $outquery = Attendance::getAttendanceOut($value->empid, $request->datename);
            $outtime = $outquery->record_time ?? null;
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
