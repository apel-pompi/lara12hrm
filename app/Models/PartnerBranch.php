<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PartnerBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_name',
        'branch_email',
        'branch_state_id',
        'branch_city_id',
        'branch_phoneno',
        'user_id',
        'active',
    ];

    /**
     * Get the user that owns the MasterCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }



    /**
     * Get the states that owns the PartnerBranch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function states(): BelongsTo
    {
        return $this->belongsTo(State::class, 'branch_state_id');
    }

    /**
     * Get the citys that owns the PartnerBranch
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function citys(): BelongsTo
    {
        return $this->belongsTo(City::class, 'branch_city_id');
    }
}
