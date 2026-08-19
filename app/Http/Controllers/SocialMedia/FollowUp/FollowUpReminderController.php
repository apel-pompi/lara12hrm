<?php

namespace App\Http\Controllers\SocialMedia\FollowUp;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\FollowUp\FollowUpReminder;
use App\Services\SocialMedia\FollowUp\FollowUpReminderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FollowUpReminderController extends Controller
{
    public function __construct(
        protected FollowUpReminderService $reminderService
    ) {}

    public function index(Request $request): JsonResponse
    {
        $reminders = $this->reminderService
            ->query()
            ->orderBy('remind_at')
            ->paginate(
                $request->integer('per_page', 15)
            );
        return response()->json([
            'success' => true,
            'data' => $reminders,
        ]);
    }

    public function dashboard(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->reminderService->dashboard(),
        ]);
    }

    public function pending(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->reminderService->pending(),
        ]);
    }

    public function today(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->reminderService->today(),
        ]);
    }

    public function due(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->reminderService->due(),
        ]);
    }

    public function overdue(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->reminderService->overdue(),
        ]);
    }

    public function upcoming(Request $request): JsonResponse
    {
        $days = $request->integer('days', 7);

        return response()->json([
            'success' => true,
            'data' => $this->reminderService->upcoming($days),
        ]);
    }

    public function markAsSent(FollowUpReminder $reminder): JsonResponse
    {
        $reminder = $this->reminderService
            ->markAsSent($reminder);

        return response()->json([
            'success' => true,
            'message' => 'Reminder marked as sent.',
            'data' => $reminder,
        ]);
    }

    public function markAsRead(FollowUpReminder $reminder): JsonResponse
    {
        $reminder = $this->reminderService
            ->markAsRead($reminder);

        return response()->json([
            'success' => true,
            'message' => 'Reminder marked as read.',
            'data' => $reminder,
        ]);
    }

    public function snooze(Request $request, FollowUpReminder $reminder): JsonResponse
    {
        $validated = $request->validate([
            'minutes' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $reminder = $this->reminderService->snooze(
            $reminder,
            $validated['minutes']
        );

        return response()->json([
            'success' => true,
            'message' => 'Reminder snoozed successfully.',
            'data' => $reminder,
        ]);
    }

    public function destroy(FollowUpReminder $reminder): JsonResponse
    {
        $this->reminderService->delete($reminder);

        return response()->json([
            'success' => true,
            'message' => 'Reminder deleted successfully.',
        ]);
    }

    public function runScheduler(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->reminderService->runScheduler(),
        ]);
    }
}
