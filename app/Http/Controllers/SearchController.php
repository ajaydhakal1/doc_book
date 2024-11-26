<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Schedule;

class SearchController extends Controller
{
    /**
     * Handle search requests.
     */
    public function search(Request $request)
    {
        $query = $request->input('query');

        // Initialize search results
        $results = [];

        if ($query) {
            // Search in Users (Doctors and Patients)
            $results['users'] = User::where('name', 'LIKE', "%$query%")
                ->orWhere('email', 'LIKE', "%$query%")
                ->get();

            // Search in Schedules
            $results['schedules'] = Schedule::where('date', 'LIKE', "%$query%")
                ->orWhere('start_time', 'LIKE', "%$query%")
                ->orWhere('end_time', 'LIKE', "%$query%")
                ->with('doctor.user') // Eager load related doctor data
                ->get();

            // Search in Appointments
            $results['appointments'] = Appointment::whereHas('patient.user', function ($q) use ($query) {
                $q->where('name', 'LIKE', "%$query%");
            })
                ->orWhereHas('doctor.user', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })
                ->orWhereHas('schedule', function ($q) use ($query) {
                    $q->where('date', 'LIKE', "%$query%");
                })
                ->with(['patient.user', 'doctor.user', 'schedule'])
                ->get();
        }

        return view('search.results', compact('query', 'results'));
    }
}
