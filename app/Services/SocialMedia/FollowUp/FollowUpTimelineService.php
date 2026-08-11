<?php

namespace App\Services\SocialMedia\FollowUp;

use App\Models\SocialMedia\FollowUp\FollowUpActivity;
use App\Models\SocialMedia\FollowUp\FollowUpTimeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FollowUpTimelineService
{
    public function create(
        FollowUpActivity $activity,
        string $eventType,
        string $title,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?array $meta = null,
        bool $isSystem = false
    ): FollowUpTimeline {

        return FollowUpTimeline::create([
            'follow_up_activity_id' => $activity->id,

            'student_id' => $activity->student_id,

            'user_id' => Auth::id(),

            'event_type' => $eventType,

            'title' => $title,

            'description' => $description,

            'old_values' => $oldValues,

            'new_values' => $newValues,

            'meta' => $meta,

            'is_system' => $isSystem,
        ]);
    }

    public function activityCreated(
        FollowUpActivity $activity
    ): FollowUpTimeline {

        return $this->create(
            activity: $activity,
            eventType: 'created',
            title: 'Follow-up Created',
            description: $activity->title,
            newValues: [
                'status' => $activity->status,
                'follow_up_date' => $activity->date,
                'follow_up_time' => $activity->time,
                'priority' => $activity->priority,
                'assigned_to' => $activity->assigned_to,
            ]
        );
    }

    public function activityCompleted(
        FollowUpActivity $activity,
        ?string $remarks = null
    ): FollowUpTimeline {

        return $this->create(
            activity: $activity,
            eventType: 'completed',
            title: 'Follow-up Completed',
            description: $remarks ?? 'Follow-up completed successfully.',
            newValues: [
                'status' => 'Completed',
                'completed_at' => now()->toDateTimeString(),
                'remarks' => $remarks,
            ]
        );
    }

    public function activityCancelled(
        FollowUpActivity $activity,
        ?string $remarks = null
    ): FollowUpTimeline {

        return $this->create(
            activity: $activity,
            eventType: 'cancelled',
            title: 'Follow-up Cancelled',
            description: $remarks ?? 'Follow-up was cancelled.',
            newValues: [
                'status' => 'Cancelled',
                'remarks' => $remarks,
            ]
        );
    }

    public function activityRescheduled(
        FollowUpActivity $activity,
        array $oldValues,
        ?string $remarks = null
    ): FollowUpTimeline {

        return $this->create(
            activity: $activity,
            eventType: 'rescheduled',
            title: 'Follow-up Rescheduled',
            description: $remarks ?? 'Follow-up was rescheduled.',
            oldValues: $oldValues,
            newValues: [
                'follow_up_date' => $activity->date,
                'follow_up_time' => $activity->time,
                'remarks' => $remarks,
            ]
        );
    }

    public function activityUpdated(
        FollowUpActivity $activity,
        array $oldValues,
        array $newValues,
        ?string $remarks = null
    ): FollowUpTimeline {

        return $this->create(
            activity: $activity,
            eventType: 'updated',
            title: 'Follow-up Updated',
            description: $remarks ?? 'Follow-up activity was updated.',
            oldValues: $oldValues,
            newValues: $newValues,
            meta: [
                'changed_fields' => array_keys(
                    array_diff_assoc(
                        $newValues,
                        $oldValues
                    )
                ),
            ]
        );
    }
}
