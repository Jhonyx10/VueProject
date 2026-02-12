<?php

namespace App\Enums;

enum AppointmentStatus: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
    case COMPLETE = 'complete';
    case CANCELLED = 'cancelled';
    case EXPIRED = 'expired';
}