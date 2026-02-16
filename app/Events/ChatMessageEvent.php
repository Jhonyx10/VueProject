<?php

namespace App\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
// Remove SerializesModels if the error persists
// use Illuminate\Queue\SerializesModels; 

class ChatMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets; 

    public $message;

    public function __construct($message)
    {
        // Convert to array if the queue keeps failing to find the model
        // but for now, let's keep the object and fix the queue driver.
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('chat.room.' . (string)$this->message->room_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'message.sent';
    }
}