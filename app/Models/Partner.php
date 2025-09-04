<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'workflow_id',
        'master_cat_id',
        'partner_type_id',
        'state_id',
        'city_id',
        'brn',
        'currency',
        'phone',
        'email',
        'fax',
        'website',
        'photo',
        'partner_branch_id',
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
}
