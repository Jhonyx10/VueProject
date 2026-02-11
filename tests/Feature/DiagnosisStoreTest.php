<?php

namespace Tests\Feature;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class DiagnosisStoreTest extends TestCase
{
    public function test_doctor_can_store_diagnosis()
    {
        $doctor = User::create([
            'name' => 'Doctor Test',
            'email' => 'doctor@test.com',
            'password' => 'password',
            'role' => 'doctor'
        ]);
        $patient = User::create([
            'name' => 'Patient Test',
            'email' => 'patient@test.com',
            'password' => 'password',
            'role' => 'user'
        ]);
        
        $appointment = Appointment::create([
            'doctor_id' => (string)$doctor->_id,
            'patient_id' => (string)$patient->_id,
            'date' => '2025-01-01',
            'time' => '10:00',
            'status' => 'pending',
            'notes' => 'Test appointment'
        ]);

        $response = $this->actingAs($doctor)->post(route('diagnosis.store'), [
            'appointment_id' => (string)$appointment->_id,
            'patient_id' => (string)$patient->_id,
            'type' => 'primary',
            'status' => 'preliminary',
            'condition_name' => 'Influenza',
            'severity' => 'moderate',
            'notes' => 'Patient has fever.',
            'clinical_codes' => [
                ['code' => 'J11', 'system' => 'ICD-10']
            ],
            'vitals_at_time' => [
                'bp' => '120/80',
                'temp' => 38.5
            ]
        ]);

        $response->assertRedirect(route('appointments.index'));
        
        $this->assertDatabaseHas('diagnosis', [
            'condition_name' => 'Influenza',
            'patient_id' => (string)$patient->_id,
        ], 'mongodb');

        $this->assertEquals('confirmed', $appointment->fresh()->status);
    }

    public function test_diagnoisis_store_fails_with_invalid_data()
    {
        $doctor = User::create([
            'name' => 'Doctor Test',
            'email' => 'doctor2@test.com',
            'password' => 'password',
            'role' => 'doctor'
        ]);
        
        $response = $this->actingAs($doctor)->post(route('diagnosis.store'), [
            'condition_name' => 'Fl', // too short
        ]);

        $response->assertSessionHasErrors(['condition_name', 'appointment_id', 'patient_id']);
    }

    public function test_diagnosis_store_filters_empty_codes()
    {
        $doctor = User::create([
            'name' => 'Doctor Test',
            'email' => 'doctor3@test.com',
            'password' => 'password',
            'role' => 'doctor'
        ]);
        $patient = User::create([
            'name' => 'Patient Test',
            'email' => 'patient3@test.com',
            'password' => 'password',
            'role' => 'user'
        ]);
        $appointment = Appointment::create([
            'doctor_id' => (string)$doctor->_id,
            'patient_id' => (string)$patient->_id,
            'date' => '2025-01-01',
            'time' => '10:00',
            'status' => 'pending'
        ]);

        $response = $this->actingAs($doctor)->post(route('diagnosis.store'), [
            'appointment_id' => (string)$appointment->_id,
            'patient_id' => (string)$patient->_id,
            'type' => 'primary',
            'status' => 'preliminary',
            'condition_name' => 'Common Cold',
            'severity' => 'mild',
            'clinical_codes' => [
                ['code' => '', 'system' => 'ICD-10'], // empty code
                ['code' => 'K00', 'system' => 'SNOMED']
            ]
        ]);

        $response->assertRedirect(route('appointments.index'));
        
        $diagnosis = \App\Models\Diagnosis::where('condition_name', 'Common Cold')->first();
        $this->assertCount(1, $diagnosis->clinical_codes);
        $this->assertEquals('K00', $diagnosis->clinical_codes[0]['code']);
    }
}
