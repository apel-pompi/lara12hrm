<?php

namespace App\Models\SocialMedia\FollowUp;

use App\Models\SocialMedia\SocialMediaConversation;
use App\Models\Student\Student;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class FollowUpActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'conversation_id',
        'follow_up_master_id',
        'follow_up_status_id',
        'assigned_to',
        'created_by',
        'title',
        'description',
        'follow_up_date',
        'follow_up_time',
        'priority',
        'status',
        'remarks',
        'completed_at',
        'is_auto',
        'meta',

    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(
            SocialMediaConversation::class,
            'conversation_id'
        );
    }
    public function master(): BelongsTo
    {
        return $this->belongsTo(FollowUpMaster::class, 'follow_up_master_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(FollowUpStatus::class, 'follow_up_status_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(FollowUpReminder::class, 'follow_up_activity_id');
    }

    public function timelines(): HasMany
    {
        return $this->hasMany(
            FollowUpTimeline::class,
            'follow_up_activity_id'
        )->latest();
    }

    protected function casts(): array
    {
        return [
            'follow_up_date' => 'date',
            'follow_up_time' => 'string',
            'completed_at' => 'datetime',
            'is_auto' => 'boolean',
            'meta' => 'array',
        ];
    }

    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->follow_up_date->format('Y-m-d')
        );
    }

    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->follow_up_time
                ? substr($this->follow_up_time, 0, 8)
                : null
        );
    }

    protected function dateTime(): Attribute
    {
        return Attribute::make(

            get: fn() =>

            $this->follow_up_date

                ? $this->follow_up_date->format('Y-m-d')

                . ' '

                . ($this->follow_up_time ?? '00:00:00')

                : null

        );
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate(
            'follow_up_date',
            today()
        )->where('status', 'Pending');
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween(
            'follow_up_date',
            [now()->startOfWeek(), now()->endOfWeek()]
        )->where('status', 'Pending');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    public function isDueToday(): bool
    {
        return $this->follow_up_date->isSameDay(today())
            && $this->status === 'Pending';
    }

    public function scopeOverdue($query)
    {
        return $query->where('follow_up_date', '<', today())
            ->where('status', 'Pending');
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'Completed',
            'completed_at' => now(),
        ]);
    }

    public function cancel(): void
    {
        $this->update([
            'status' => 'Cancelled',
        ]);
    }

    public function reschedule(
        string $date,
        ?string $time = null,
        ?string $remarks = null
    ): void {

        $this->update([

            'follow_up_date' => $date,

            'follow_up_time' => $time,

            'remarks' => $remarks,

            'status' => 'Rescheduled',

        ]);
    }

    protected static function booted(): void
    {
        static::updated(function ($model) {
            if (
                $model->status === 'Completed'
                && $model->wasChanged('status')
            ) {
                // Delete future reminders on completion
                $model->reminders()->where(
                    'status',
                    'Pending'
                )->delete();
            }
        });
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where(
            'assigned_to',
            $userId
        );
    }

    public function scopeStudent($query, int $studentId)
    {
        return $query->where(
            'student_id',
            $studentId
        );
    }
}
