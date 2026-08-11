<?php

namespace App\Models\SocialMedia\FollowUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUpMaster extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'default_days',
        'default_priority',
        'status',
        'sort_order',
        'icon',
        'color',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
}
