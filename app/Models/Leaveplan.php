<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Leaveplan extends Model
{
    use HasFactory;

    protected $fillable = [
        'leavename',
        'leaveyear',
        'leavedays',
        'active',
    ];
}
