<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActiveSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'last_activity_at',
        'ip_address'
    ];

    /**
     * Get the user that owns the ActiveSession
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    
}
