<?php

namespace App\Services\SocialMedia\FollowUp;

use App\Models\SocialMedia\FollowUp\FollowUpActivity;
use App\Models\SocialMedia\FollowUp\FollowUpStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FollowUpActivityService
{
    public function __construct(
        protected FollowUpReminderService $reminderService,
        protected FollowUpTimelineService $timelineService,
        protected FollowUpNotificationService $notificationService,
    ) {}


    /**
     * ----------------------------------------------------------
     * Create New Follow Up Activity
     * ----------------------------------------------------------
     */
    public function create(array $data): FollowUpActivity
    {


        DB::beginTransaction();

        try {

            $activity = FollowUpActivity::create([

                'student_id' => $data['student_id'],

                'conversation_id' => $data['conversation_id'] ?? null,

                'follow_up_master_id' => $data['follow_up_master_id'],

                'follow_up_status_id' => $data['follow_up_status_id'],

                'assigned_to' => $data['assigned_to'],

                'created_by' => Auth::id(),

                'title' => $data['title'],

                'description' => $data['description'] ?? null,

                'follow_up_date' => $data['follow_up_date'],

                'follow_up_time' => $data['follow_up_time'] ?? null,

                'priority' => $data['priority'] ?? 'Medium',

                'status' => 'Pending',

                'remarks' => $data['remarks'] ?? null,

                'completed_at' => null,

                'is_auto' => $data['is_auto'] ?? false,

                'meta' => $data['meta'] ?? null,

            ]);

            /*
            |--------------------------------------------------------------------------
            | Reminder
            |--------------------------------------------------------------------------
            */

            $this->reminderService
                ->createReminder($activity);

            /*
            |--------------------------------------------------------------------------
            | Timeline
            |--------------------------------------------------------------------------
            */

            $this->timelineService
                ->activityCreated($activity);

            /*
            |--------------------------------------------------------------------------
            | Notification
            |--------------------------------------------------------------------------
            */

            $this->notificationService
                ->createAssignedNotification($activity);

            DB::commit();

            return $activity->fresh();
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('FOLLOW UP CREATE FAILED', [

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

                'payload' => $data,

            ]);

            throw $e;
        }
    }

    /**
     * ----------------------------------------------------------
     * Update Existing Activity
     * ----------------------------------------------------------
     */
    public function update(
        FollowUpActivity $activity,
        array $data
    ): FollowUpActivity {

        DB::beginTransaction();

        try {

            $oldAssigned = $activity->assigned_to;

            $oldDate = $activity->follow_up_date;

            $oldTime = $activity->follow_up_time;

            $oldValues = [
                'follow_up_master_id' => $activity->follow_up_master_id,
                'follow_up_status_id' => $activity->follow_up_status_id,
                'assigned_to' => $activity->assigned_to,
                'title' => $activity->title,
                'description' => $activity->description,
                'follow_up_date' => $activity->date,
                'follow_up_time' => $activity->time,
                'priority' => $activity->priority,
                'remarks' => $activity->remarks,
            ];

            $activity->update([

                'follow_up_master_id' =>
                $data['follow_up_master_id']
                    ?? $activity->follow_up_master_id,

                'follow_up_status_id' =>
                $data['follow_up_status_id']
                    ?? $activity->follow_up_status_id,

                'assigned_to' =>
                $data['assigned_to']
                    ?? $activity->assigned_to,

                'title' =>
                $data['title']
                    ?? $activity->title,

                'description' =>
                $data['description']
                    ?? $activity->description,

                'follow_up_date' =>
                $data['follow_up_date']
                    ?? $activity->follow_up_date,

                'follow_up_time' =>
                $data['follow_up_time']
                    ?? $activity->follow_up_time,

                'priority' =>
                $data['priority']
                    ?? $activity->priority,

                'remarks' =>
                $data['remarks']
                    ?? $activity->remarks,

                'meta' =>
                $data['meta']
                    ?? $activity->meta,

            ]);
            /*
        |--------------------------------------------------------------------------
        | New Values
        |--------------------------------------------------------------------------
        */
            $newValues = [
                'follow_up_master_id' => $activity->follow_up_master_id,
                'follow_up_status_id' => $activity->follow_up_status_id,
                'assigned_to' => $activity->assigned_to,
                'title' => $activity->title,
                'description' => $activity->description,
                'follow_up_date' => $activity->date,
                'follow_up_time' => $activity->time,
                'priority' => $activity->priority,
                'remarks' => $activity->remarks,
            ];
            /*
            |--------------------------------------------------------------------------
            | Reminder Update
            |--------------------------------------------------------------------------
            */

            if (

                $oldDate != $activity->follow_up_date ||

                $oldTime != $activity->follow_up_time

            ) {

                $this->reminderService
                    ->updateReminder($activity);
            }

            /*
            |--------------------------------------------------------------------------
            | Timeline
            |--------------------------------------------------------------------------
            */

            $this->timelineService
                ->activityUpdated(
                    $activity,
                    $oldValues,
                    $newValues
                );

            /*
            |--------------------------------------------------------------------------
            | Reassigned
            |--------------------------------------------------------------------------
            */

            if ($oldAssigned != $activity->assigned_to) {

                $this->notificationService
                    ->createAssignedNotification($activity);
            }

            DB::commit();

            return $activity->fresh();
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('FOLLOW UP UPDATE FAILED', [

                'message' => $e->getMessage(),

                'line' => $e->getLine(),

                'file' => $e->getFile(),

            ]);

            throw $e;
        }
    }


    /**
     * ----------------------------------------------------------
     * Complete Follow Up
     * ----------------------------------------------------------
     */
    public function complete(
        FollowUpActivity $activity,
        ?string $remarks = null
    ): FollowUpActivity {

        DB::beginTransaction();

        try {

            $completedStatus = FollowUpStatus::whereCode(
                'COMPLETED'
            )->first();

            $activity->update([

                'status' => 'Completed',

                'completed_at' => now(),

                'remarks' => $remarks
                    ?? $activity->remarks,

                'follow_up_status_id' =>
                $completedStatus?->id
                    ?? $activity->follow_up_status_id,

            ]);

            /*
        |--------------------------------------------------------------------------
        | Reminder
        |--------------------------------------------------------------------------
        */

            $this->reminderService
                ->cancelReminder($activity);

            /*
        |--------------------------------------------------------------------------
        | Timeline
        |--------------------------------------------------------------------------
        */

            $this->timelineService
                ->activityCompleted($activity);

            /*
        |--------------------------------------------------------------------------
        | Notification
        |--------------------------------------------------------------------------
        */

            $this->notificationService
                ->createAssignedNotification($activity);

            DB::commit();

            return $activity->fresh();
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('FOLLOW UP COMPLETE FAILED', [

                'activity_id' => $activity->id,

                'message' => $e->getMessage(),

            ]);

            throw $e;
        }
    }


    /**
     * ----------------------------------------------------------
     * Cancel Follow Up
     * ----------------------------------------------------------
     */
    public function cancel(
        FollowUpActivity $activity,
        ?string $remarks = null
    ): FollowUpActivity {

        DB::beginTransaction();

        try {

            $cancelStatus = FollowUpStatus::whereCode(
                'CANCELLED'
            )->first();

            $activity->update([

                'status' => 'Cancelled',

                'remarks' => $remarks
                    ?? $activity->remarks,

                'follow_up_status_id' =>
                $cancelStatus?->id
                    ?? $activity->follow_up_status_id,

            ]);

            /*
        |--------------------------------------------------------------------------
        | Reminder
        |--------------------------------------------------------------------------
        */

            $this->reminderService
                ->cancelReminder($activity);

            /*
        |--------------------------------------------------------------------------
        | Timeline
        |--------------------------------------------------------------------------
        */

            $this->timelineService
                ->activityCancelled($activity);

            DB::commit();

            return $activity->fresh();
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('FOLLOW UP CANCEL FAILED', [

                'activity_id' => $activity->id,

                'message' => $e->getMessage(),

            ]);

            throw $e;
        }
    }

    /**
     * ----------------------------------------------------------
     * Reschedule Follow Up
     * ----------------------------------------------------------
     */
    public function reschedule(
        FollowUpActivity $activity,
        string $date,
        ?string $time = null,
        ?string $remarks = null
    ): FollowUpActivity {

        DB::beginTransaction();

        try {
            $oldDate = $activity->follow_up_date;
            $oldTime = $activity->follow_up_time;
            /*
        |--------------------------------------------------------------------------
        | Activity
        |--------------------------------------------------------------------------
        */
            $activity->update([
                'status' => 'Rescheduled',

                'follow_up_date' => $date,

                'follow_up_time' => $time,

                'remarks' => $remarks
                    ?? $activity->remarks,
            ]);

            /*
        |--------------------------------------------------------------------------
        | Reminder
        |--------------------------------------------------------------------------
        */

            $this->reminderService
                ->updateReminder($activity);

            /*
        |--------------------------------------------------------------------------
        | Timeline
        |--------------------------------------------------------------------------
        */

            $this->timelineService
                ->activityRescheduled(
                    $activity,
                    $oldDate,
                    $oldTime
                );


            /*
        |--------------------------------------------------------------------------
        | Notification
        |--------------------------------------------------------------------------
        */

            $this->notificationService
                ->createRescheduledNotification($activity);

            DB::commit();

            return $activity->fresh();
        } catch (\Throwable $e) {

            DB::rollBack();

            Log::error('FOLLOW UP RESCHEDULE FAILED', [

                'activity_id' => $activity->id,

                'message' => $e->getMessage(),

            ]);

            throw $e;
        }
    }

    /**
     * ----------------------------------------------------------
     * Find Activity
     * ----------------------------------------------------------
     */
    public function find(int $id): ?FollowUpActivity
    {
        return FollowUpActivity::with([

            'student',

            'master',

            'status',

            'assignedTo',

            'creator',

            'reminders',

        ])->find($id);
    }

    public function findOrFail(int $id): FollowUpActivity
    {
        return FollowUpActivity::with([

            'student',

            'master',

            'status',

            'assignedTo',

            'creator',

            'reminders',

        ])->findOrFail($id);
    }

    /**
     * ----------------------------------------------------------
     * Base Query
     * ----------------------------------------------------------
     */
    public function query()
    {
        return FollowUpActivity::query()

            ->with([

                'student',

                'master',

                'status',

                'assignedTo',

                'creator',

            ]);
    }

    /**
     * ----------------------------------------------------------
     * List
     * ----------------------------------------------------------
     */
    public function list(
        array $filters = [],
        int $perPage = 20
    ) {
        $query = $this->query();

        if (!empty($filters['student_id'])) {

            $query->where(
                'student_id',
                $filters['student_id']
            );
        }

        if (!empty($filters['assigned_to'])) {

            $query->where(
                'assigned_to',
                $filters['assigned_to']
            );
        }

        if (!empty($filters['status'])) {

            $query->where(
                'status',
                $filters['status']
            );
        }

        if (!empty($filters['priority'])) {

            $query->where(
                'priority',
                $filters['priority']
            );
        }

        if (!empty($filters['master'])) {

            $query->where(
                'follow_up_master_id',
                $filters['master']
            );
        }

        if (!empty($filters['date_from'])) {

            $query->whereDate(
                'follow_up_date',
                '>=',
                $filters['date_from']
            );
        }

        if (!empty($filters['date_to'])) {

            $query->whereDate(
                'follow_up_date',
                '<=',
                $filters['date_to']
            );
        }

        return $query

            ->orderBy('follow_up_date')

            ->orderBy('follow_up_time')

            ->paginate($perPage);
    }


    /**
     * ----------------------------------------------------------
     * Today's Follow Up
     * ----------------------------------------------------------
     */
    public function today(): Collection
    {
        return $this->query()

            ->whereDate(
                'follow_up_date',
                today()
            )

            ->where('status', 'Pending')

            ->orderBy('follow_up_time')

            ->get();
    }

    /**
     * ----------------------------------------------------------
     * Overdue
     * ----------------------------------------------------------
     */
    public function overdue(): Collection
    {
        return $this->query()

            ->whereDate(
                'follow_up_date',
                '<',
                today()
            )

            ->where('status', 'Pending')

            ->orderBy('follow_up_date')

            ->get();
    }

    /**
     * ----------------------------------------------------------
     * Upcoming
     * ----------------------------------------------------------
     */
    public function upcoming(
        int $days = 7
    ): Collection {

        return $this->query()

            ->whereBetween(
                'follow_up_date',
                [

                    today(),

                    today()->addDays($days)

                ]
            )

            ->where('status', 'Pending')

            ->orderBy('follow_up_date')

            ->orderBy('follow_up_time')

            ->get();
    }


    /**
     * ----------------------------------------------------------
     * Assigned To
     * ----------------------------------------------------------
     */

    public function assignedTo(
        int $userId
    ): Collection {

        return $this->query()

            ->where(
                'assigned_to',
                $userId
            )

            ->where(
                'status',
                'Pending'
            )

            ->orderBy('follow_up_date')

            ->orderBy('follow_up_time')

            ->get();
    }
    /**
     * ----------------------------------------------------------
     * Student Timeline
     * ----------------------------------------------------------
     */
    public function studentTimeline(
        int $studentId
    ): Collection {

        return $this->query()

            ->where('student_id', $studentId)

            ->latest('follow_up_date')

            ->latest('follow_up_time')

            ->get();
    }


    /**
     * ----------------------------------------------------------
     * Dashboard Summary
     * ----------------------------------------------------------
     */
    public function dashboardSummary(): array
    {
        $current = Carbon::now();
        $currentDay = $current->year . '-' . $current->month . '-' . $current->day;
        $followUpQuery = FollowUpActivity::query();
        $followUpStats = [
            'total' => (clone $followUpQuery)->count(),

            'pending' => (clone $followUpQuery)
                ->whereHas('status', function ($q) {
                    $q->where('name', 'Pending');
                })
                ->count(),

            'due_today' => (clone $followUpQuery)
                ->whereDate('follow_up_date', $currentDay)
                ->whereHas('status', function ($q) {
                    $q->where('name', 'Pending');
                })
                ->count(),

            'completed' => (clone $followUpQuery)
                ->whereHas('status', function ($q) {
                    $q->where('name', 'Completed');
                })
                ->count(),

            'overdue' => (clone $followUpQuery)
                ->whereDate('follow_up_date', '<', $currentDay)
                ->whereHas('status', function ($q) {
                    $q->where('name', 'Pending');
                })
                ->count(),

            'urgent' => (clone $followUpQuery)
                ->where('priority', 'Urgent')
                ->whereHas('status', function ($q) {
                    $q->where('name', 'Pending');
                })
                ->count(),
        ];
        return $followUpStats;
        // return [

        //     'today' => FollowUpActivity::pending()
        //         ->whereDate('follow_up_date', today())
        //         ->count(),

        //     'overdue' => FollowUpActivity::pending()
        //         ->whereDate('follow_up_date', '<', today())
        //         ->count(),

        //     'upcoming' => FollowUpActivity::pending()
        //         ->whereDate('follow_up_date', '>', today())
        //         ->count(),

        //     'completed' => FollowUpActivity::completed()
        //         ->count(),

        // ];
    }

    public function counselorPerformance()
    {
        return FollowUpActivity::query()
            ->select([
                'assigned_to',
                DB::raw('COUNT(*) as total'),

                DB::raw("
                SUM(
                    CASE
                        WHEN status = 'Pending'
                        THEN 1
                        ELSE 0
                    END
                ) as pending
            "),

                DB::raw("
                SUM(
                    CASE
                        WHEN status = 'Completed'
                        THEN 1
                        ELSE 0
                    END
                ) as completed
            "),

                DB::raw("
                SUM(
                    CASE
                        WHEN status = 'Pending'
                        AND follow_up_date < CURDATE()
                        THEN 1
                        ELSE 0
                    END
                ) as overdue
            "),

                DB::raw("
                SUM(
                    CASE
                        WHEN priority = 'Urgent'
                        AND status = 'Pending'
                        THEN 1
                        ELSE 0
                    END
                ) as urgent
            "),
            ])
            ->with('assignedTo:id,name')
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to')
            ->orderByDesc('total')
            ->get()
            ->map(function ($item) {
                return [
                    'user_id' => (int) $item->assigned_to,

                    'user_name' =>
                    $item->assignedTo?->name
                        ?? 'Unknown Counselor',

                    'total' => (int) $item->total,
                    'pending' => (int) $item->pending,
                    'completed' => (int) $item->completed,
                    'overdue' => (int) $item->overdue,
                    'urgent' => (int) $item->urgent,
                ];
            });
    }
    /**
     * ----------------------------------------------------------
     * My Pending Follow-ups
     * ----------------------------------------------------------
     */
    public function myPending(
        ?int $userId = null
    ): Collection {

        $userId ??= Auth::id();

        return $this->query()

            ->where('assigned_to', $userId)

            ->where('status', 'Pending')

            ->orderBy('follow_up_date')

            ->orderBy('follow_up_time')

            ->get();
    }


    /**
     * ----------------------------------------------------------
     * Calendar Events
     * ----------------------------------------------------------
     */
    public function calendar(
        ?string $start = null,
        ?string $end = null
    ): Collection {

        $query = $this->query();

        if ($start) {

            $query->whereDate(
                'follow_up_date',
                '>=',
                $start
            );
        }

        if ($end) {

            $query->whereDate(
                'follow_up_date',
                '<=',
                $end
            );
        }

        return $query

            ->orderBy('follow_up_date')

            ->orderBy('follow_up_time')

            ->get();
    }

    /**
     * ----------------------------------------------------------
     * Next Follow-up
     * ----------------------------------------------------------
     */

    public function nextActivity(
        int $studentId
    ): ?FollowUpActivity {

        return $this->query()

            ->where('student_id', $studentId)

            ->where('status', 'Pending')

            ->whereDate(
                'follow_up_date',
                '>=',
                today()
            )

            ->orderBy('follow_up_date')

            ->orderBy('follow_up_time')

            ->first();
    }

    /**
     * ----------------------------------------------------------
     * Last Completed Activity
     * ----------------------------------------------------------
     */
    public function lastActivity(
        int $studentId
    ): ?FollowUpActivity {

        return $this->query()

            ->where('student_id', $studentId)

            ->latest('follow_up_date')

            ->latest('follow_up_time')

            ->first();
    }


    /**
     * ----------------------------------------------------------
     * Student Follow-up History
     * ----------------------------------------------------------
     */

    public function history(
        int $studentId
    ): Collection {

        return $this->query()

            ->where('student_id', $studentId)

            ->orderByDesc('follow_up_date')

            ->orderByDesc('follow_up_time')

            ->get();
    }
}
