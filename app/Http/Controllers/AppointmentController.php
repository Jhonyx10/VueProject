<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AppointmentRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
    {
        return Inertia::render('Appointments', [
            'appointments' => Appointment::with(['doctor.doctorProfile', 'patient'])->get(),
            
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        //
    }
}
