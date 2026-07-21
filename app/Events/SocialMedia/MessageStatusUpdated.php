<?php

namespace App\Events\SocialMedia;

use App\Models\SocialMedia\SocialMediaMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MessageStatusUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public SocialMediaMessage $message
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel(
                'conversation.' . $this->message->conversation_id
            )
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.status.updated';
    }

    public function broadcastWith(): array
    {
        return [

            'id' => $this->message->id,

            'status' => $this->message->status,

            'read_at' => $this->message->read_at,

            'delivered_at' => $this->message->delivered_at,

        ];
    }
}
