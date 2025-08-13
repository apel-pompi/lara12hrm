<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HolidayHd extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'holiyear',
        'holimonth',
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
