<?php

namespace App\Http\Controllers\SocialMedia\FollowUp;

use App\Http\Controllers\Controller;
use App\Http\Requests\FollowUpReminder\StoreFollowUpReminderRequest;
use App\Http\Requests\FollowUpReminder\UpdateFollowUpReminderRequest;
use App\Models\SocialMedia\FollowUp\FollowUpReminder;
use Illuminate\Http\JsonResponse;

class FollowUpReminderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(
            FollowUpReminder::with([
                'student',
                'activity',
                'assignedUser',
            ])
                ->latest('reminder_at')
                ->paginate(20)
        );
    }

    public function pending(): JsonResponse
    {
        return response()->json(
            FollowUpReminder::with([
                'student',
                'activity',
                'assignedUser',
            ])
                ->where('status', 'Pending')
                ->orderBy('reminder_at')
                ->get()
        );
    }

    public function today(): JsonResponse
    {
        return response()->json(
            FollowUpReminder::with([
                'student',
                'activity',
                'assignedUser',
            ])
                ->whereDate('reminder_at', today())
                ->orderBy('reminder_at')
                ->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFollowUpReminderRequest $request): JsonResponse
    {
        $reminder = FollowUpReminder::create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Reminder created successfully.',
            'data' => $reminder,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(
        FollowUpReminder $followUpReminder
    ): JsonResponse {
        return response()->json(
            $followUpReminder->load([
                'student',
                'activity',
                'assignedUser',
            ])
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFollowUpReminderRequest $request, FollowUpReminder $followUpReminder): JsonResponse
    {
        $followUpReminder->update(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Reminder updated successfully.',
            'data' => $followUpReminder,
        ]);
    }

    public function complete(
        FollowUpReminder $followUpReminder
    ): JsonResponse {
        $followUpReminder->update([
            'status' => 'Completed',
            'completed_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reminder completed successfully.',
        ]);
    }

    public function snooze(
        FollowUpReminder $followUpReminder
    ): JsonResponse {
        $followUpReminder->update([
            'status' => 'Pending',
            'reminder_at' => now()->addDay(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reminder snoozed for 1 day.',
        ]);
    }

    public function cancel(
        FollowUpReminder $followUpReminder
    ): JsonResponse {
        $followUpReminder->update([
            'status' => 'Cancelled',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Reminder cancelled successfully.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        FollowUpReminder $followUpReminder
    ): JsonResponse {
        $followUpReminder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reminder deleted successfully.',
        ]);
    }
}
