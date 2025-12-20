<?php

namespace App\Models\HRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'empid',
        'branch_id',
        'yearname',
        'monthname',
        'workhour',
        'totalhour',
        'deducthour',
        'hrsurplus',
        'nethour',
        'absent',
        'leave',
        'totaldeduct',
        'payablehour',
        'salary',
        'payment',
        'active',
        'user_id',
    ];

    /**
     * Get the employee that owns the AttendanceStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class, 'empid','empid');
    }

    /**
     * Get the user that owns the AttendanceStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
