<?php

namespace App\Models\AgencySetting;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Academic extends Model
{
    
    use HasFactory,SoftDeletes;
    
    protected $fillable = [
        'name',
        'adddate',
        'user_id',
        'active',
    ];

     /**
     * Get the user that owns the Fees
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
