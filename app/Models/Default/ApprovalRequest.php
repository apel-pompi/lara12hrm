<?php

namespace App\Models\Default;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprovalRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'description',
        'remarks',
        'status',
        'user_id',
    ];
}
