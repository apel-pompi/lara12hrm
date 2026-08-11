<?php

namespace App\Models\SocialMedia\FollowUp;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FollowUpStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'color',
        'icon',
        'is_completed',
        'is_cancelled',
        'is_default',
        'allow_reschedule',
        'allow_edit',
        'status',
        'sort_order',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'is_cancelled' => 'boolean',
        'is_default' => 'boolean',
        'allow_reschedule' => 'boolean',
        'allow_edit' => 'boolean',
        'status' => 'boolean',
    ];
}
