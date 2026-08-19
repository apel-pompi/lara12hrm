<?php

namespace App\Http\Controllers\SocialMedia\FollowUp;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\FollowUp\FollowUpTimeline;
use App\Models\SocialMedia\FollowUp\FollowUpActivity;
use Illuminate\Http\JsonResponse;

class FollowUpTimelineController extends Controller
{
    private function attachAssignedUserName($timeline)
    {
        return $timeline->map(function ($item) {
            $assignedUserName = optional(
                optional($item->activity)->assignedTo
            )->name;

            if ($item->activity) {
                $item->activity->setAttribute(
                    'assigned_user_name',
                    $assignedUserName
                );
            }

            return $item;
        });
    }

    /**
     * Display a listing of the resource.
     */
    public function index(FollowUpActivity $followUpActivity): JsonResponse
    {

        $timeline = $followUpActivity
            ->timelines()
            ->with([
                'user:id,name',
                'activity.assignedTo:id,name',
            ])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $this->attachAssignedUserName($timeline),
        ]);
    }

    public function student(int $studentId): JsonResponse
    {

        $timeline = FollowUpTimeline::with([
            'activity.master',
            'activity.status',
            'activity.assignedTo:id,name',
            'user:id,name',
        ])
            ->where('student_id', $studentId)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $this->attachAssignedUserName($timeline),
        ]);
    }
}
