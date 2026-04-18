<?php

namespace App\Models\Accounts;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class GroupOne extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = [
        'code',
        'description',
        'user_id',
        'active',
    ];

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = strtoupper($value);
    }

    /**
     * Get the user that owns the GroupOne
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    /**
     * Get all of the GroupTwo for the GroupOne
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function GroupTwo(): HasMany
    {
        return $this->hasMany(GroupTwo::class, 'groupone');
    }

    /**
     * Get all of the GroupThree for the GroupOne
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function GroupThree(): HasMany
    {
        return $this->hasMany(GroupThree::class, 'groupone');
    }

   /**
     * Get all of the GroupThree for the GroupOne
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chartOfAccounts(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'groupone');
    }
}
