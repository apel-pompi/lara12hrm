<?php

namespace App\Models\SocialMedia\FollowUp;

use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUpTimeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'follow_up_activity_id',
        'student_id',
        'user_id',
        'event_type',
        'title',
        'description',
        'old_values',
        'new_values',
        'meta',
        'is_system',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'meta' => 'array',
        'is_system' => 'boolean',
    ];

    public function activity(): BelongsTo
    {
        return $this->belongsTo(FollowUpActivity::class, 'follow_up_activity_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
