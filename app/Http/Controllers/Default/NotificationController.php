<?php

namespace App\Http\Controllers\Default;

use App\Http\Controllers\Controller;
use App\Models\Default\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class NotificationController extends Controller
{
    /**
     * Get user notifications
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $notifications = Notification::where('user_id', $user->id)
                ->orderBy('id', 'desc')
                ->limit(50)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'type' => $notification->type,
                        'read' => (bool) $notification->read,
                        'action_url' => $notification->action_url,
                        'created_at' => $notification->created_at->toISOString(),
                        'time_ago' => $notification->created_at->diffForHumans(),
                    ];
                });

            $unreadCount = Notification::where('user_id', $user->id)
                ->where('read', false)
                ->count();
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();

            $notification = Notification::where('user_id', $user->id)
                ->where('id', $id)
                ->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->update([
                'read' => true,
                'type' => 'success',
                'read_at' => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark notification as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead(Request $request)
    {
        try {
            $user = $request->user();

            $updatedCount = Notification::where('user_id', $user->id)
                ->where('read', false)
                ->update([
                    'read' => true,
                    'type' => 'success',
                    'read_at' => Carbon::now()
                ]);

            // JSON response for Inertia/AJAX calls
            if ($request->expectsJson() || $request->header('X-Inertia')) {
                return response()->json([
                    'success' => true,
                    'message' => 'All notifications marked as read',
                    'updated_count' => $updatedCount
                ]);
            }

            // For regular form submissions
            return back()->with([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        } catch (\Exception $e) {
            // JSON response for errors
            if ($request->expectsJson() || $request->header('X-Inertia')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to mark all notifications as read',
                    'error' => $e->getMessage()
                ], 500);
            }

            return back()->with([
                'success' => false,
                'message' => 'Failed to mark all notifications as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get unread notifications count
     */
    public function unreadCount(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            $count = Notification::where('user_id', $user->id)
                ->where('read', false)
                ->count();

            return response()->json([
                'success' => true,
                'unread_count' => $count
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to get unread count',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a notification
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = $request->user();

            $notification = Notification::where('user_id', $user->id)
                ->where('id', $id)
                ->first();

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notification not found'
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notification deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear all notifications
     */
    public function clearAll(Request $request): JsonResponse
    {
        try {
            $user = $request->user();

            Notification::where('user_id', $user->id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'All notifications cleared'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create a new notification (for testing or admin use)
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'type' => 'required|in:info,success,warning,error',
                'user_id' => 'required|exists:users,id',
                'action_url' => 'nullable|string'
            ]);

            $notification = Notification::create([
                'user_id' => $request->user_id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'action_url' => $request->action_url,
                'read' => false
            ]);

            // You can broadcast this notification via WebSocket/Pusher here
            // broadcast(new NotificationCreated($notification))->toOthers();

            return response()->json([
                'success' => true,
                'message' => 'Notification created successfully',
                'notification' => $notification
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create notification',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
