<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Enums\AppointmentStatus;
use App\Events\AppointmentNotificationEvent;
use App\Events\AppointmentStatusEvent;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        return Inertia::render('Appointments', [
            'appointments' => Appointment::with(['doctor.doctorProfile', 'patient'])
                ->where('status', '!=', 'complete')
                ->orderBy('date')->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AppointmentRequest $request)
    {
        $data = $request->validated();
        $userId = Auth::id();

        $appointment = Appointment::create(array_merge($data, [
            'patient_id' => $userId,
            'user_id'    => $userId,
            'status'     => 'pending',
        ]));

        $participants = [
            (string) $userId, 
            (string) $data['doctor_id'] 
        ];

        // Check if a room already exists for these participants
        $existingRoom = ChatRoom::where('participants', 'all', $participants)->first();

        if (!$existingRoom) {
            ChatRoom::create([
                'appointment_id' => $appointment->id,
                'participants'   => $participants,
            ]);
        }

        event(new AppointmentNotificationEvent($appointment, 'New appointment request received!'));
        
        return redirect()->back()->with('success', 'Appointment booked and chat room opened!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        return Inertia::render('Appointments/Show', [
            'appointment' => $appointment->load(['doctor.doctorProfile', 'patient']),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Appointment $appointment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $status = match($request->action) {
            'confirm' => AppointmentStatus::CONFIRMED->value,
            'cancel'  => AppointmentStatus::CANCELLED->value,
            'expired'   => AppointmentStatus::EXPIRED->value,
            default   => $appointment->status,
        };

        $appointment->update([
            'status' => $status,
            'reason' => $request->action === 'cancel' ? $request->reason : null
        ]);

        $message = match($request->action) {
            'cancel' => 'Appointment cancelled.',
            'expire' => 'Appointment has expired.',
            'confirm' => 'Appointment confirmed successfully!',
            default   => 'Appointment updated successfully.'
        };

        $recipientId = Auth::id() == $appointment->patient_id ? $appointment->doctor_id : $appointment->patient_id;
        event(new AppointmentStatusEvent($appointment, $message, $recipientId));

        return redirect()->back()->with('success', $message);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        try {
            $user = Auth::user();
            
            if ($user->role === 'user' && $appointment->patient_id !== $user->id) {
                return redirect()->back()->with('error', 'Unauthorized to delete this appointment.');
            }
            
            $appointment->delete();

            return redirect()->back()->with('success', 'Appointment successfully removed from the schedule.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to delete appointment.');
        }
    }
}
