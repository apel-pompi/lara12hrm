<?php

namespace App\Models\AgencySetting;

use App\Models\Partner\PartnerTypeSetup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class MasterCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'catname',
        'catadddate',
        'user_id',
        'active'
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
     * Get all of the comments for the MasterCategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function parnerTypes(): HasMany
    {
        return $this->hasMany(PartnerTypeSetup::class, 'mastercaterory_id');
    }
}
