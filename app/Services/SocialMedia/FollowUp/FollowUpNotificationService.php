<?php

namespace App\Services\SocialMedia\FollowUp;

use App\Events\SocialMedia\FollowUpNotificationCreated;
use App\Models\SocialMedia\FollowUp\FollowUpActivity;
use App\Models\SocialMedia\FollowUp\FollowUpNotification;
use App\Models\SocialMedia\FollowUp\FollowUpReminder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FollowUpNotificationService
{
    /*
    |--------------------------------------------------------------------------
    | Query
    |--------------------------------------------------------------------------
    */

    public function query()
    {
        return FollowUpNotification::query()
            ->with([
                'activity.student',
                'activity.master',
                'activity.status',
                'user',
                'reminder',
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Create Due Notification
    |--------------------------------------------------------------------------
    */

    public function createDueNotification(
        FollowUpReminder $reminder
    ): FollowUpNotification {

        $reminder->loadMissing([
            'activity.student',
            'activity.master',
            'activity.status',
            'assignedUser',
        ]);

        $activity = $reminder->activity;

        if (! $activity) {
            throw new \RuntimeException(
                "Follow-up activity not found for reminder {$reminder->id}"
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Notification
        |--------------------------------------------------------------------------
        */

        $existing = FollowUpNotification::where(
            'follow_up_reminder_id',
            $reminder->id
        )
            ->where(
                'type',
                'follow_up_due'
            )
            ->first();

        if ($existing) {
            return $existing;
        }

        $studentName = $activity->student?->name
            ?? $activity->student?->fname
            ?? 'Student';

        $followUpType = $activity->master?->name
            ?? 'Follow Up';

        $notification = FollowUpNotification::create([
            'user_id' => $activity->assigned_to,

            'follow_up_activity_id' =>
            $activity->id,

            'follow_up_reminder_id' =>
            $reminder->id,

            'type' => 'follow_up_due',

            'title' => 'Follow-up Due',

            'message' =>
            "The {$followUpType} follow-up for {$studentName} is now due.",

            'data' => [
                'student_id' => $activity->student_id,

                'activity_id' => $activity->id,

                'reminder_id' => $reminder->id,

                'follow_up_master_id' =>
                $activity->follow_up_master_id,

                'follow_up_status_id' =>
                $activity->follow_up_status_id,

                'priority' =>
                $activity->priority,

                'follow_up_date' =>
                $activity->follow_up_date?->format('Y-m-d'),

                'follow_up_time' =>
                is_string($activity->follow_up_time)
                    ? $activity->follow_up_time
                    : $activity->follow_up_time?->format('H:i:s'),
            ],

            'read_at' => null,
        ]);
        FollowUpNotificationCreated::dispatch($notification);

        return $notification;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Assigned Notification
    |--------------------------------------------------------------------------
    */

    public function createAssignedNotification(
        FollowUpActivity $activity
    ): FollowUpNotification {

        $activity->loadMissing([
            'student',
            'master',
        ]);

        $studentName = $activity->student?->name
            ?? $activity->student?->fname
            ?? 'Student';

        $followUpType = $activity->master?->name
            ?? 'Follow Up';

        $notification = FollowUpNotification::create([
            'user_id' => $activity->assigned_to,

            'follow_up_activity_id' =>
            $activity->id,

            'follow_up_reminder_id' => null,

            'type' => 'follow_up_assigned',

            'title' => 'New Follow-up Assigned',

            'message' =>
            "{$studentName} has been assigned a {$followUpType} follow-up.",

            'data' => [
                'student_id' =>
                $activity->student_id,

                'activity_id' =>
                $activity->id,

                'priority' =>
                $activity->priority,

                'follow_up_date' =>
                $activity->follow_up_date?->format('Y-m-d'),

                'follow_up_time' =>
                is_string($activity->follow_up_time)
                    ? $activity->follow_up_time
                    : $activity->follow_up_time?->format('H:i:s'),
            ],

            'read_at' => null,
        ]);
        FollowUpNotificationCreated::dispatch($notification);
        return $notification;
    }

    /*
    |--------------------------------------------------------------------------
    | Create Rescheduled Notification
    |--------------------------------------------------------------------------
    */

    public function createRescheduledNotification(
        FollowUpActivity $activity
    ): FollowUpNotification {

        $activity->loadMissing([
            'student',
            'master',
        ]);

        $studentName = $activity->student?->name
            ?? $activity->student?->fname
            ?? 'Student';

        $notification = FollowUpNotification::create([
            'user_id' =>
            $activity->assigned_to,

            'follow_up_activity_id' =>
            $activity->id,

            'follow_up_reminder_id' => null,

            'type' =>
            'follow_up_rescheduled',

            'title' =>
            'Follow-up Rescheduled',

            'message' =>
            "The follow-up for {$studentName} has been rescheduled.",

            'data' => [
                'student_id' =>
                $activity->student_id,

                'activity_id' =>
                $activity->id,

                'follow_up_date' =>
                $activity->follow_up_date?->format('Y-m-d'),

                'follow_up_time' =>
                is_string($activity->follow_up_time)
                    ? $activity->follow_up_time
                    : $activity->follow_up_time?->format('H:i:s'),
            ],

            'read_at' => null,
        ]);
        FollowUpNotificationCreated::dispatch($notification);
        return $notification;
    }

    /*
    |--------------------------------------------------------------------------
    | Unread Notifications
    |--------------------------------------------------------------------------
    */

    public function unread(
        int $userId,
        int $limit = 20
    ): Collection {

        return $this->query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->latest()
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | All Notifications
    |--------------------------------------------------------------------------
    */

    public function notifications(
        int $userId,
        int $limit = 50
    ): Collection {

        return $this->query()
            ->where('user_id', $userId)
            ->latest()
            ->limit($limit)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | Unread Count
    |--------------------------------------------------------------------------
    */

    public function unreadCount(
        int $userId
    ): int {

        return FollowUpNotification::query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /*
    |--------------------------------------------------------------------------
    | Mark As Read
    |--------------------------------------------------------------------------
    */

    public function markAsRead(
        FollowUpNotification $notification,
        int $userId
    ): FollowUpNotification {

        if ($notification->user_id !== $userId) {
            abort(403);
        }

        $notification->markAsRead();

        return $notification->fresh();
    }

    /*
    |--------------------------------------------------------------------------
    | Mark All As Read
    |--------------------------------------------------------------------------
    */

    public function markAllAsRead(
        int $userId
    ): int {

        return FollowUpNotification::query()
            ->where('user_id', $userId)
            ->whereNull('read_at')
            ->update([
                'read_at' => now(),
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Notification
    |--------------------------------------------------------------------------
    */

    public function delete(
        FollowUpNotification $notification,
        int $userId
    ): bool {

        if ($notification->user_id !== $userId) {
            abort(403);
        }

        return (bool) $notification->delete();
    }

    public function paginate(
        int $userId,
        int $perPage = 10
    ) {
        $user = Auth::user();
        /** @var \Spatie\Permission\Traits\HasRoles $user */
        $roles = $user->getRoleNames();
        if ($roles->contains('superadmin') or $roles->contains('Admin') or $roles->contains('Manager')) {
            return FollowUpNotification::query()
                ->latest('created_at')
                ->paginate($perPage);
        } else {
            return FollowUpNotification::query()
                ->where('user_id', $userId)
                ->latest('created_at')
                ->paginate($perPage);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Dashboard Summary
    |--------------------------------------------------------------------------
    */

    public function dashboard(
        int $userId
    ): array {

        return [
            'unread' =>
            FollowUpNotification::query()
                ->where('user_id', $userId)
                ->whereNull('read_at')
                ->count(),

            'today' =>
            FollowUpNotification::query()
                ->where('user_id', $userId)
                ->whereDate(
                    'created_at',
                    today()
                )
                ->count(),

            'due' =>
            FollowUpNotification::query()
                ->where('user_id', $userId)
                ->where(
                    'type',
                    'follow_up_due'
                )
                ->whereNull('read_at')
                ->count(),

            'overdue' =>
            FollowUpNotification::query()
                ->where('user_id', $userId)
                ->where(
                    'type',
                    'follow_up_overdue'
                )
                ->whereNull('read_at')
                ->count(),
        ];
    }
}
