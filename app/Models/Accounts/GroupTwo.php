<?php

namespace App\Models\Accounts;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupTwo extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = [
        'groupone',
        'grouptwo',
        'description',
        'user_id',
        'active',
    ];

    public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = strtoupper($value);
    }
    
    /**
     * Get the GroupOne that owns the GroupTwo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function GroupOne(): BelongsTo
    {
        return $this->belongsTo(GroupOne::class, 'groupone','groupone');
    }

     /**
     * Get all of the GroupThree for the GroupOne
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function GroupThree(): HasMany
    {
        return $this->hasMany(GroupThree::class, 'grouptwo', 'grouptwo');
    }

    /**
     * Get all of the chartOfAccounts for the GroupOne
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chartOfAccounts(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'grouptwo', 'grouptwo');
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
}
