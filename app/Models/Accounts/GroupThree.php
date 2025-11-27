<?php

namespace App\Models\Accounts;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GroupThree extends Model
{
    use HasFactory,SoftDeletes;
    
    protected $fillable = [
        'groupone',
        'grouptwo',
        'groupthree',
        'description',
        'user_id',
        'active',
    ];

    /**
     * Get the GroupOne that owns the GroupTwo
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function GroupOne(): BelongsTo
    {
        return $this->belongsTo(GroupOne::class, 'groupone', 'groupone');
    }

    /**
     * Get the GroupTwo that owns the GroupThree
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function GroupTwo(): BelongsTo
    {
        return $this->belongsTo(GroupTwo::class, 'grouptwo', 'grouptwo');
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
