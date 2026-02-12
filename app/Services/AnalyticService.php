<?php

namespace App\Services;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Support\Facades\Auth;
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

    public function doctorsUpcomingAppointment()
    {
        return Appointment::with('patient')
        ->where('doctor_id', Auth::id())
        ->where('date', '>=', now()->toDateString()) 
        ->whereNotIn('status', ['cancelled', 'complete']) 
        ->orderBy('date', 'asc') 
        ->orderBy('time', 'asc') 
        ->first();
    }

    public function usersUpcomingAppointment()
    {
        $userId = (string) Auth::id();

        return Appointment::with(['doctor.doctorProfile', 'patient']) 
            ->where('patient_id', $userId)
            ->where('date', '>=', now()->toDateString())
            ->whereNotIn('status', ['cancelled', 'complete', 'rejected']) 
            ->orderBy('date', 'asc') 
            ->orderBy('time', 'asc') 
            ->first();
    }

    public function monthlyAppointmentCount()
    {
        $results = Appointment::raw(function($collection) {
            return $collection->aggregate([
                [
                    // Convert string dates to Date objects on the fly if necessary
                    '$addFields' => [
                        'converted_date' => [
                            '$cond' => [
                                'if' => [ '$isNumber' => '$date' ], // Handle timestamps if any
                                'then' => [ '$toDate' => '$date' ],
                                'else' => [ 
                                    '$dateFromString' => [ 'dateString' => '$date' ] 
                                ]
                            ]
                        ]
                    ]
                ],
                [
                    '$match' => [
                        'converted_date' => [
                            '$gte' => new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-01-01')) * 1000),
                            '$lte' => new \MongoDB\BSON\UTCDateTime(strtotime(date('Y-12-31')) * 1000),
                        ]
                    ]
                ],
                [
                    '$group' => [
                        '_id' => [ '$month' => '$converted_date' ],
                        'count' => [ '$sum' => 1 ]
                    ]
                ],
                [ '$sort' => [ '_id' => 1 ] ]
            ]);
        });

        // Map to Month Names
        $formatted = collect($results)->map(function($item) {
            return [
                'month' => date("F", mktime(0, 0, 0, $item->_id, 1)),
                'count' => $item->count
            ];
        });

        // OPTIONAL: Ensure all 12 months exist even if count is 0
        $months = collect([
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
        ]);

        return $months->map(function($monthName) use ($formatted) {
            $data = $formatted->firstWhere('month', $monthName);
            return [
                'month' => $monthName,
                'count' => $data ? $data['count'] : 0
            ];
        })->values();
    }

    public function appointmentStatusAnalytics()
    {
        $results = Appointment::raw(function($collection) {
            return $collection->aggregate([
                [
                    '$group' => [
                        '_id' => '$status', // Group by the 'status' field
                        'count' => [ '$sum' => 1 ] // Count each occurrence
                    ]
                ]
            ]);
        });

        return collect($results)->map(function($item) {
            return [
                'status' => $item->_id ?? 'Unknown',
                'count'  => $item->count
            ];
        })->values();
    }
}