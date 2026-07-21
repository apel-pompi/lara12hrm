<?php

namespace App\Events\SocialMedia;

use App\Models\SocialMedia\SocialMediaMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewMessageReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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
    public function broadcastOn()
    {
        return [
            new Channel(
                'conversation.' . $this->message->conversation_id
            )
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.received';
    }

    public function broadcastWith(): array
    {
        $conversation = $this->message
            ->conversation
            ->fresh();

        return [

            'id' => $this->message->id,

            'conversation_id' => $conversation->id,

            'direction' => $this->message->direction,

            'sender_type' => $this->message->sender_type,

            'message_type' => $this->message->message_type,

            'message' => $this->message->message,

            'attachment' => $this->message->attachment,

            'status' => $this->message->status,

            'created_at' => $this->message->created_at,

            'conversation' => [

                'id' => $conversation->id,

                'last_message' => $conversation->last_message,

                'last_message_at' => $conversation->last_message_at,

                'unread_count' => $conversation->unread_count,

            ],

        ];
    }
}
