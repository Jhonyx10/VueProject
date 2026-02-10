<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

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
        'status'
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
}
