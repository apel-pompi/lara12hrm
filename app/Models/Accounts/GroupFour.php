<?php

namespace App\Models\Accounts;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GroupFour extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'groupone',
        'grouptwo',
        'groupthree',
        'code',
        'description',
        'user_id',
        'active',
    ];

    // Mutator to automatically convert description to uppercase
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
        return $this->belongsTo(GroupOne::class, 'groupone');
    }

    /**
     * Get the GroupTwo that owns the GroupThree
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function GroupTwo(): BelongsTo
    {
        return $this->belongsTo(GroupTwo::class, 'grouptwo');
    }

    /**
     * Get the GroupThree that owns the GroupFour
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function GroupThree(): BelongsTo
    {
        return $this->belongsTo(GroupThree::class, 'groupthree');
    }

    public function chartOfAccounts(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'groupfour');
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
