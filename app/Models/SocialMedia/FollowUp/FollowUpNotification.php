<?php

namespace App\Models\SocialMedia\FollowUp;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowUpNotification extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'follow_up_activity_id',
        'follow_up_reminder_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'read_at' => 'datetime',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }
    public function activity(): BelongsTo
    {
        return $this->belongsTo(
            FollowUpActivity::class,
            'follow_up_activity_id'
        );
    }
    public function reminder(): BelongsTo
    {
        return $this->belongsTo(
            FollowUpReminder::class,
            'follow_up_reminder_id'
        );
    }
    public function isRead(): bool
    {
        return ! is_null($this->read_at);
    }

    public function isUnread(): bool
    {
        return is_null($this->read_at);
    }

    public function markAsRead(): void
    {
        if ($this->isUnread()) {
            $this->update([
                'read_at' => now(),
            ]);
        }
    }

    public function markAsUnread(): void
    {
        $this->update([
            'read_at' => null,
        ]);
    }
}
