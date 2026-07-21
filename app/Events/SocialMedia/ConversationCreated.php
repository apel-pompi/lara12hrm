<?php

namespace App\Events\SocialMedia;

use App\Models\SocialMedia\SocialMediaConversation;
use Illuminate\Broadcasting\Channel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ConversationCreated
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
            new Channel('conversations')
        ];
    }

    public function broadcastAs(): string
    {
        return 'conversation.created';
    }

    public function broadcastWith(): array
    {
        return [

            'conversation' => [

                'id' => $this->conversation->id,

                'platform' => $this->conversation->platform,

                'social_name' => optional($this->conversation->contact)->social_name,

                'phone' => optional($this->conversation->contact)->phone_number,

                'profile_picture' => optional($this->conversation->contact)->profile_picture,

                'student_status' => optional(optional($this->conversation->contact)->student)->status,

                'last_message' => $this->conversation->last_message,

                'last_message_at' => $this->conversation->last_message_at,

                'last_message_type' => optional(
                    $this->conversation->messages()->latest()->first()
                )->message_type,

                'last_message_status' => optional(
                    $this->conversation->messages()->latest()->first()
                )->status,

                'last_message_direction' => optional(
                    $this->conversation->messages()->latest()->first()
                )->direction,

                'unread_count' => $this->conversation->unread_count,

            ]

        ];
    }
}
