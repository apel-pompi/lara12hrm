<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
}
