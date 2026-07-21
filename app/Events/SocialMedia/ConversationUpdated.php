<?php

namespace App\Events\SocialMedia;

use App\Models\SocialMedia\SocialMediaConversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationUpdated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public SocialMediaConversation $conversation
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('conversations'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'conversation.updated';
    }

    public function broadcastWith(): array
    {
        return [

            'conversation' => [

                'id' => $this->conversation->id,

                'last_message' => $this->conversation->last_message,

                'last_message_at' => $this->conversation->last_message_at,

                'unread_count' => $this->conversation->unread_count,

            ]

        ];
    }
}
