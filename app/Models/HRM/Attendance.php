<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'record_time',
        'device_ip',
        'state',
    ];

    public static function getAttendanceIn($empid, $date)
    {
        return DB::table('attendances as a')
            ->select('a.record_time')
            ->where('a.user_id', $empid)
            ->whereDate('a.record_time', $date)
            ->orderBy('a.record_time', 'ASC')
            ->first();
    }

    public static function getAttendanceOut($empid, $date)
    {
        return DB::table('attendances as a')
            ->select('a.record_time')
            ->where('a.user_id', $empid)
            ->whereDate('a.record_time', $date)
            ->orderBy('a.record_time', 'DESC')
            ->first();
    }

    public static function getAttendanceStatus($empid, $date)
    {
        return DB::table('attendances as a')
            ->select(DB::raw("
            CASE WHEN time(a.record_time) <= atten_settings.ptime THEN atten_settings.pname WHEN time(a.record_time) <= atten_settings.ltime THEN atten_settings.lname ELSE 'Absent' END AS TimeName,
            time(a.record_time)
        "))
            ->leftJoin('personal_infos', 'a.user_id', '=', 'personal_infos.empid')
            ->leftJoin('atten_settings', 'personal_infos.branch_id', '=', 'atten_settings.branch_id')
            ->where('a.user_id', $empid)
            ->whereDate('a.record_time', '=', $date)
            ->orderBy('a.record_time', 'ASC')
            ->limit(1)
            ->first();
    }
}
