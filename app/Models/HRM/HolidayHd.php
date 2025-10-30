<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HolidayHd extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'branch_id',
        'yearname',
        'monthname',
        'holidays',
        'holiworking',
        'active',
    ];


    /**
     * Get the branch that owns the HolidayHd
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class,'branch_id');
    }
}
