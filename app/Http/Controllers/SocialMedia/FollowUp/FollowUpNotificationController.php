<?php

namespace App\Http\Controllers\SocialMedia\FollowUp;

use App\Http\Controllers\Controller;
use App\Models\SocialMedia\FollowUp\FollowUpNotification;
use Illuminate\Http\JsonResponse;
use App\Services\SocialMedia\FollowUp\FollowUpNotificationService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FollowUpNotificationController extends Controller
{
    public function __construct(
        protected FollowUpNotificationService $notificationService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(int $userId): JsonResponse
    {
        $perPage = request()->integer('per_page', 15);

        $notifications = $this->notificationService
            ->paginate($userId, $perPage);

        return response()->json([
            'success' => true,
            'data' => $notifications,
        ]);
    }
    public function all(int $userId)
    {
        $notifications = $this->notificationService
            ->paginate($userId, request()->integer('per_page', 10));

        return Inertia::render('allpages/Agency/MetaChat/FollowUpComponents/FollowUpNotifications', [
            'notifications' => $notifications,
        ]);
    }
    /**
     * Unread notification count
     */
    public function unreadCount(string $userId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'count' => $this->notificationService->unreadCount(
                (int) $userId
            ),
        ]);
    }

    /**
     * Counselor notification dashboard
     */
    public function dashboard(int $userId): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->notificationService
                ->dashboard($userId),
        ]);
    }
    /**
     * Mark single notification as read
     */
    public function markAsRead(
        FollowUpNotification $notification,
        int $userId
    ): JsonResponse {
        $notification = $this->notificationService->markAsRead(
            $notification,
            $userId
        );

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
            'data' => $notification,
        ]);
    }
    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(int $userId): JsonResponse
    {
        $count = $this->notificationService
            ->markAllAsRead($userId);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
            'updated' => $count,
        ]);
    }
}
