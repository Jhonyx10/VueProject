<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AnalyticService;
use Inertia\Inertia;

class DashboardController extends Controller
{
    protected $service;

    public function __construct(AnalyticService $service)
    {
        $this->service = $service;
    }

    public function dashboard()
    {
        $doctorsCount = $this->service->doctorsCount();
        $usersCount = $this->service->totalRegisteredUsers();
        $totalPendingAppointments = $this->service->totalPendingAppointments();
        $doctorsUpcomingAppointment = $this->service->doctorsUpcomingAppointment();
        $usersUpcomingAppointment = $this->service->usersUpcomingAppointment();
        $monthlyAppointments = $this->service->monthlyAppointmentCount();
        $appointmentStatusCount = $this->service->appointmentStatusAnalytics();

        return Inertia::render('Dashboard', [
            'doctorsCount' => $doctorsCount,
            'usersCount' => $usersCount,
            'appPendingCount' => $totalPendingAppointments,
            'upcomingAppointment' => $doctorsUpcomingAppointment,
            'userUpcomingAppointment' => $usersUpcomingAppointment,
            'monthlyAppointmentCount' => $monthlyAppointments,
            'appointmentStatusCount' => $appointmentStatusCount
        ]);
    }
}
