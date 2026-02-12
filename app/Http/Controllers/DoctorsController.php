<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Http\Requests\DoctorsRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DoctorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = User::where('role','doctor')->orderByDesc('created_at')->get();

        return Inertia::render('Doctors', [
            'doctors' => $doctors
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
    public function store(DoctorsRequest $request)
    {
        $data = $request->validated();

        $data['user_id'] = Auth::id();

        $doctor = Doctor::updateOrCreate(
        ['user_id' => Auth::id()], 
        $data
        );

        $message = $doctor->wasRecentlyCreated 
        ? 'Professional profile created successfully.' 
        : 'Professional profile updated successfully.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
            $doctor = User::with('doctorProfile')->findOrFail($id);

        return Inertia::render('Doctors/DoctorsProfile', [
            'doctor' => $doctor
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctors $doctors)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Doctors $doctors)
    {
        $doctor = Doctor::findOrFail($id);
        
        // Security check: Only the owner or an admin should update this
        if (Auth::id() !== $doctor->user_id) {
            abort(403);
        }

        $doctor->update($request->validated());

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctors $doctors)
    {
        //
    }
}
