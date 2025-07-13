<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttenSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'pname',
        'lname',
        'ptime',
        'ltime',
        'active',
    ];

    /**
     * Get the user that owns the AttenSetting
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
