<?php

namespace App\Events\HRM;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttendanceSyncCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public array $result,
        public ?int $userId = null
    ) {}

    public function broadcastOn(): array
    {
        if ($this->userId) {
            return [
                new PrivateChannel('attendance-sync.' . $this->userId),
            ];
        }

        return [];
    }

    public function broadcastAs(): string
    {
        return 'attendance.sync.completed';
    }

    public function broadcastWith(): array
    {
        return [
            'result' => $this->result,
        ];
    }
}
