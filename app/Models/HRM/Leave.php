<?php

namespace App\Models\HRM;

use App\Models\User;
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
        'requestdays',
        'approveddate',
        'approveddays',
        'substitute',
        'contact_address',
        'reason',
        'status',
        'user_id'
    ];

    /**
     * Get the leavePlan that owns the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leavePlan(): BelongsTo
    {
        return $this->belongsTo(Leaveplan::class, 'leaveplan_id','id');
    }

    /**
     * Get the employee that owns the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class, 'empid','id');
    }

    /**
     * Get the substituteEmployee that owns the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function substituteEmployee(): BelongsTo
    {
        return $this->belongsTo(PersonalInfo::class, 'substitute','id');
    }

    /**
     * Get the user that owns the Leave
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
