<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leave extends Model
{
    use HasFactory, SoftDeletes;

     protected $fillable = [
        'leaveplan_id',
        'empid',
        'fromdate',
        'todate',
        'days',
        'substitute',
        'reason',
        'status',
    ];

    /**
     * Get the leavePlan that owns the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leavePlan(): BelongsTo
    {
        return $this->belongsTo(Leaveplan::class, 'leaveplan_id', 'id');
    }

    /**
     * Get the employee that owns the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class, 'empid', 'empid');
    }

    /**
     * Get the substituteEmployee that owns the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function substituteEmployee(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class, 'substitute', 'empid');
    }
}
