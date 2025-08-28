<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Leaveplan extends Model
{
    use HasFactory;

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
