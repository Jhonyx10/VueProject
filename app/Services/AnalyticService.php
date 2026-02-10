<?php

namespace App\Services;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;

class AnalyticService
{
    public function doctorsCount()
    {
        return User::where('role','doctor')->count();
    }

    public function totalRegisteredUsers()
    {
        return User::where('role','user')->count();
    }

    public function totalPendingAppointments()
    {
        return Appointment::where('status', 'pending')->count();
    }

}