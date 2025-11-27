<?php

namespace App\Models\HRM;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttenDeduct extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'starttime',
        'endtime',
        'deduct',
        'active',
        'user_id',
    ];


    /**
     * Get the branch that owns the AttenDeduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    /**
     * Get the user that owns the AttenDeduct
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    
    public static function getDeductHour($branch, $time)
    {
        $deductRule = AttenDeduct::where('active', 1)
            ->where('branch_id', $branch)
            ->whereTime('starttime', '<=', $time)
            ->whereTime('endtime', '>=', $time)
            ->first();
        $deduct = $deductRule->deduct ?? 0;
        return $deduct;
    }
}
