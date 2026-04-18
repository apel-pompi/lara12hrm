<?php

namespace App\Services;

use App\Filters\PersonalInfoFilter;
use App\Models\HRM\Attendance;
use Illuminate\Support\Facades\DB;

class AttendanceService
{
    public function get(array $queryParams = [])
    {
        $queryBuilder = Attendance::with(['employee.designation', 'employee.department'])
            ->join('personal_infos', 'attendances.user_id', '=', 'personal_infos.empid')
            ->select(
                'attendances.user_id',
                DB::raw('DATE(attendances.record_time) as attend_date')
            )
            ->groupBy(
                'attendances.user_id',
                DB::raw('DATE(attendances.record_time)')
            )
            ->orderByRaw('DATE(attendances.record_time) DESC');

        $attendance = resolve(PersonalInfoFilter::class)->getResults([
            'builder' => $queryBuilder,
            'params' => $queryParams
        ]);

        /**
         * intime, outtime, status add
         */
        $attendance->getCollection()->transform(function ($row) {

            $date  = $row->attend_date;
            $empid = $row->user_id;

            $in = Attendance::getAttendanceIn($empid, $date);
            $out = Attendance::getAttendanceOut($empid, $date);
            $status = Attendance::getAttendanceStatus($empid, $date);

            $row->intime = $in?->record_time;
            $row->outtime = $out?->record_time;
            $row->status = $status?->TimeName ?? 'Absent';

            return $row;
        });

        return $attendance;
    }
}
