<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Requests\DiagnosisRequest;
use Inertia\Inertia;
use App\Enums\AppointmentStatus;
use Illuminate\Support\Facades\Auth;

class DiagnosisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = User::where('role', 'user')
            ->with([
                'patientRecords.doctor.doctorProfile', 
                // Filter the relationship so only completed appointments are loaded
                'appointments' => function ($query) {
                    $query->where('status', 'complete');
                }
            ])
            // Optional: Only return patients who actually HAVE a completed appointment
            ->whereHas('appointments', function ($query) {
                $query->where('status', 'complete');
            })
            ->orderByDesc('created_at')
            ->get();

        return Inertia::render('Diagnosis', [
            'patients' => $patients
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Debugging: Uncomment the line below to see if IDs are actually reaching the server
        // dd($request->all()); 

        return Inertia::render('Diagnosis/Create', [
            'appointment' => [
                'appointment_id' => $request->query('appointment_id'),
                'patient_id'     => $request->query('patient_id'),
                'patient_name'   => $request->query('patient_name'),
                'notes'          => $request->query('notes'),
            ]
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiagnosisRequest $request)
    {
        $validated = $request->validated();

        $validated['doctor_id'] = Auth::id();

        // Filter out empty clinical codes
        if (isset($validated['clinical_codes'])) {
            $validated['clinical_codes'] = array_filter($validated['clinical_codes'], function ($item) {
                return !empty($item['code']) && !empty($item['system']);
            });
            // Re-index array for JSON serialization if it's saved as an array
            $validated['clinical_codes'] = array_values($validated['clinical_codes']);
        }

        if (isset($validated['vitals_at_time'])) {
            $validated['vitals_at_time'] = (object) $validated['vitals_at_time'];
        }

        Diagnosis::create($validated);

        $appointment = Appointment::find($validated['appointment_id']);
        if ($appointment) {
        $appointment->update([
            'status' => AppointmentStatus::COMPLETE->value
        ]);
    }

        return redirect()->route('appointments.index')->with('success', 'Diagnosis Completed Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show($id) 
    {
        $diagnosis = Diagnosis::where('appointment_id', $id)
            ->with(['appointment', 'patient', 'doctor.doctorProfile']) 
            ->first();

        if (!$diagnosis) {
            abort(404, 'Diagnosis record not found for this appointment.');
        }
        
        return Inertia::render('Diagnosis/Show', [
            'diagnosis' => $diagnosis
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Diagnosis $diagnosis)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Diagnosis $diagnosis)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Diagnosis $diagnosis)
    {
        //
    }
}
