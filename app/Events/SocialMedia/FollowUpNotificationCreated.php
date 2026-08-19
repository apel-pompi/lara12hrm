<?php

namespace App\Events\SocialMedia;

use App\Models\SocialMedia\FollowUp\FollowUpNotification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FollowUpNotificationCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public FollowUpNotification $notification
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel(
                'follow-up-notifications.' .
                    $this->notification->user_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'follow-up.notification.created';
    }

    public function broadcastWith(): array
    {
        return [
            'notification' => $this->notification->fresh()->toArray(),
        ];
    }
}
