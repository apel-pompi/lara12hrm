<?php

namespace App\Traits;

use Illuminate\Support\Facades\Broadcast;

trait BroadcastsSafely
{
    /**
     * Broadcast an event without risking a 500 when the WebSocket
     * server (Reverb) is unreachable. Unlike the `broadcast()` helper,
     * this fires synchronously inside the try/catch (the helper defers
     * the HTTP call to the PendingBroadcast destructor, which runs
     * outside any try/catch and therefore cannot be caught).
     */
    protected function safeBroadcast($event): void
    {
        try {
            Broadcast::connection()->broadcast(
                [$event->broadcastOn()],
                class_basename($event),
                $event->broadcastWith()
            );
        } catch (\Throwable $e) {
            // Realtime is optional; ignore if the broadcaster is unavailable.
        }
    }
}
