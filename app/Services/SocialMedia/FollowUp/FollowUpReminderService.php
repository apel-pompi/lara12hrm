<?php

namespace App\Services\SocialMedia\FollowUp;

use App\Models\SocialMedia\FollowUp\FollowUpActivity;
use App\Models\SocialMedia\FollowUp\FollowUpReminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FollowUpReminderService
{
    public function query()
    {
        return FollowUpReminder::query()
            ->with([
                'activity',
                'student',
                'assignedUser',
            ]);
    }

    public function createReminder(FollowUpActivity $activity): FollowUpReminder
    {

        return DB::transaction(function () use ($activity) {
            $remindAt = Carbon::parse($activity->date_time);

            return FollowUpReminder::create([
                'follow_up_activity_id' => $activity->id,
                'student_id' => $activity->student_id,
                'assigned_to' => $activity->assigned_to,
                'remind_at' => $remindAt,
                'status' => 'Pending',
                'is_sent' => false,
                'is_read' => false,
            ]);
        });
    }

    public function updateReminder(
        FollowUpActivity $activity
    ): FollowUpReminder {

        $reminder = FollowUpReminder::where(
            'follow_up_activity_id',
            $activity->id
        )->firstOrFail();

        $remindAt = Carbon::parse(
            $activity->follow_up_date->format('Y-m-d')
                . ' '
                . ($activity->follow_up_time ?: '09:00:00')
        );

        $reminder->update([
            'assigned_to' => $activity->assigned_to,
            'remind_at' => $remindAt,
            'status' => 'Pending',
            'sent_at' => null,
        ]);

        return $reminder->fresh();
    }

    public function completeReminder(
        FollowUpActivity $activity
    ): void {
        FollowUpReminder::whereCode(
            'follow_up_activity_id',
            $activity->id
        )->update([
            'status' => 'Completed',
        ]);
    }

    public function cancelReminder(
        FollowUpActivity $activity
    ): void {
        FollowUpReminder::whereCode(
            'follow_up_activity_id',
            $activity->id
        )->update([
            'status' => 'Cancelled',
        ]);
    }

    public function pending()
    {
        return $this
            ->query()
            ->where('status', 'Pending')
            ->orderBy('remind_at')
            ->get();
    }

    public function due()
    {
        return $this
            ->query()
            ->where('status', 'Pending')
            ->where(
                'remind_at',
                '<=',
                now()
            )
            ->get();
    }

    public function today()
    {
        return $this
            ->query()
            ->whereDate(
                'remind_at',
                today()
            )
            ->orderBy(
                'remind_at'
            )
            ->get();
    }

    public function overdue()
    {
        return $this
            ->query()
            ->where(
                'status',
                'Pending'
            )
            ->where(
                'remind_at',
                '<',
                now()
            )
            ->get();
    }

    public function upcoming(
        int $days = 7
    ) {
        return $this
            ->query()
            ->whereBetween(
                'remind_at',
                [
                    now(),
                    now()->addDays($days)
                ]
            )
            ->orderBy(
                'remind_at'
            )
            ->get();
    }

    public function markAsSent(
        FollowUpReminder $reminder
    ) {
        $reminder->update([
            'is_sent' => true,
            'sent_at' => now(),
        ]);

        return $reminder;
    }

    public function markAsRead(
        FollowUpReminder $reminder
    ) {
        $reminder->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return $reminder;
    }

    public function snooze(
        FollowUpReminder $reminder,
        int $minutes
    ): FollowUpReminder {

        $date = Carbon::parse($reminder->remind_at)
            ->addMinutes($minutes);

        $reminder->update([
            'remind_at' => $date,
        ]);

        return $reminder->fresh();
    }

    public function delete(
        FollowUpReminder $reminder
    ) {
        return $reminder->delete();
    }

    public function dashboard(): array
    {
        return [
            'pending' => FollowUpReminder::where('status', 'Pending')->count(),

            'today' => FollowUpReminder::whereDate('remind_at', today())->count(),

            'overdue' => FollowUpReminder::where('status', 'Pending')
                ->where('remind_at', '<', now())
                ->count(),

            'completed' => FollowUpReminder::where('status', 'Completed')->count(),
        ];
    }

    public function sendDueNotifications(): int
    {
        Log::info('sendDueNotifications() Called');
        $reminders = FollowUpReminder::with([
            'activity',
            'student',
            'activity.master',
            'assignedUser'
        ])
            ->where('status', 'Pending')
            ->whereNull('sent_at')
            ->where('remind_at', '<=', now())
            ->get();

        Log::info('Due Reminder Count', [
            'count' => $reminders->count(),
        ]);

        $count = 0;

        foreach ($reminders as $reminder) {
            try {
                Log::info('Processing Reminder', [
                    'id' => $reminder->id,
                    'student_id' => $reminder->student_id,
                    'remind_at' => $reminder->remind_at,
                ]);

                /*
                 * |--------------------------------------------------------------------------
                 * | Here call Notification Service
                 * |--------------------------------------------------------------------------
                 */

                // $this->notificationService->send($reminder);

                $reminder->update([
                    'is_sent' => true,
                    'sent_at' => now(),
                ]);
                Log::info('Reminder Sent', [
                    'id' => $reminder->id,
                ]);
                $count++;
            } catch (\Throwable $e) {
                Log::error('FollowUp Reminder Failed', [
                    'remind_at' => $reminder->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return $count;
    }

    public function runScheduler(): array
    {
        Log::info('Follow-up Scheduler Started');
        $due = $this->due();

        $sent = $this->sendDueNotifications();

        Log::info('Follow-up Scheduler Finished', [
            'time' => now(),
            'due_count' => $due->count(),
            'sent_count' => $sent,
        ]);

        return [
            'time' => now(),
            'due_count' => $due->count(),
            'sent_count' => $sent,
        ];
    }
}
