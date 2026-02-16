<?php

namespace App\Events;

use App\Models\Appointment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow; // Change this
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AppointmentNotificationEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $appointment;
    public $message;

    public function __construct(Appointment $appointment, $message)
    {
        $this->appointment = $appointment;
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        // Target a specific doctor's private channel
        return [
            new PrivateChannel('user.' . $this->appointment->doctor_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'appointment.notification';
    }
}