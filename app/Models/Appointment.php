<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;
use MongoDB\Laravel\Relations\hasOne;
use App\Enums\AppointmentStatus;

class Appointment extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'appointments';

    protected $fillable = [
        'doctor_id',
        'date',
        'time',
        'notes',
        'patient_id',
        'status',
        'reason',
    ];

    protected $casts = [
        'status' => AppointmentStatus::class,
    ];
    
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the patient (User) associated with the appointment.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function diagnosis(): hasOne
    {
        return $this->hasOne(Diagnosis::class, 'appointment_id');
    }
}
