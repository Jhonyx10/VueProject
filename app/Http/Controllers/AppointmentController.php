<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Enums\AppointmentStatus;

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
            
            'doctors' => User::where('role', 'doctor')
                ->with('doctorProfile') 
                ->get(['_id']) 
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

        $data['user_id'] = Auth::id();

        Appointment::create(array_merge($data, [
        'patient_id' => Auth::id(),
        'status'     => 'pending',
    ]));

    return redirect()->back()->with('success', 'Appointment booked successfully!');

    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment)
    {
        //
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
            'expire'  => AppointmentStatus::EXPIRED->value,
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
