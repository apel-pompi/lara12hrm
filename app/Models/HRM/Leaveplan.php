<?php

namespace App\Models\HRM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Leaveplan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'leavename',
        'leaveyear',
        'leavedays',
        'active',
    ];

    /**
     * Get all of the comments for the Leaveplan
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leaves(): HasMany
    {
        return $this->hasMany(Leave::class, 'leaveplan_id', 'id');
    }
}
