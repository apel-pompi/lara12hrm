<?php

namespace App\Models\SocialMedia\FollowUp;

use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class FollowUpReminder extends Model
{
    use HasFactory;

    protected $fillable = [

        'follow_up_activity_id',

        'student_id',

        'assigned_to',

        'remind_at',

        'channel',

        'status',

        'is_sent',

        'is_read',

        'sent_at',

        'read_at',

        'error_message',

        'payload',

    ];

    protected $casts = [
        'remind_at' => 'datetime',
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'payload' => 'array',
    ];

    public function activity()
    {
        return $this->belongsTo(
            FollowUpActivity::class,
            'follow_up_activity_id'
        );
    }

    public function student()
    {
        return $this->belongsTo(
            Student::class,
            'student_id'
        );
    }

    public function assignedUser()
    {
        return $this->belongsTo(
            User::class,
            'assigned_to'
        );
    }
    public function notifications(): HasMany
    {
        return $this->hasMany(
            FollowUpNotification::class,
            'follow_up_reminder_id'
        );
    }
}
