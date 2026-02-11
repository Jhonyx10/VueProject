<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use MongoDB\Laravel\Relations\BelongsTo;

class Diagnosis extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'diagnosis';

    protected $fillable = [
        'appointment_id',
        'doctor_id',
        'patient_id',
        'type',             // primary, secondary
        'status',           // preliminary, final
        'condition_name',   // Human readable
        'clinical_codes',   // Array: [['code' => 'J01', 'system' => 'ICD10']]
        'severity',
        'onset_date',       // When the symptoms started
        'notes',
        'vitals_at_time',   // Embedded object {bp: '120/80', temp: 37.5}
        'is_resolved'       // Boolean for chronic vs temporary
    ];

    // Helpful Casts
    protected $casts = [
        'onset_date' => 'datetime',
        'is_resolved' => 'boolean',
        'clinical_codes' => 'array',
        'vitals_at_time' => 'object'
    ];

    public function appointment(): belongsTo
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

     public function doctor(): belongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

     public function patient(): belongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
