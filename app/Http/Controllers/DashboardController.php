<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Schedule;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $data = [];
        $today = \Carbon\Carbon::today();

        // Populate role-based data
        if ($user->hasRole('Admin')) {
            $data = [
                'total_schedules' => Schedule::count(),
                'total_appointments' => Appointment::count(),
                'total_doctors' => User::role('Doctor')->count(),
                'total_patients' => User::role('Patient')->count(),
                'todays_schedules' => Schedule::where('date', $today)->get(),
                'todays_appointments' => Appointment::whereHas('schedule', function ($q) use ($today) {
                    $q->where('date', $today);
                })->get(),
                'available_doctors' => User::role('Doctor')->get(),
            ];
        }

        if ($user->hasRole('Doctor')) {
            $data['doctor_schedules'] = Schedule::where('doctor_id', $user->doctor->id)
                ->whereDate('date', '>=', now())
                ->orderBy('date', 'asc')
                ->take(5)
                ->get();

            $data['doctor_appointments'] = Appointment::whereHas('schedule', function ($query) use ($user) {
                $query->where('doctor_id', $user->id)
                    ->whereDate('date', '>=', now());
            })
                ->latest('created_at')
                ->take(5)
                ->get();
        }

        if ($user->hasRole('Patient')) {
            // Get the latest 5 appointments for the patient.
            $data['my_appointments'] = Appointment::where('patient_id', $user->patient->id)
                ->latest()
                ->take(5)
                ->with(['doctor.user', 'schedule']) // Eager load relationships to avoid N+1 issues.
                ->get();

            // Fetch all doctors with the "Doctor" role.
            $data['available_doctors'] = User::role('Doctor')->get();
        }

        // Initialize search results.
        $searchResults = null;

        // Handle search queries.
        if ($request->has('query') && !empty($request->input('query'))) {
            $query = $request->input('query');

            // Perform the search across users, schedules, and appointments.
            $searchResults = [
                'users' => User::where('name', 'LIKE', "%$query%")
                    ->orWhere('email', 'LIKE', "%$query%")
                    ->get(),

                'schedules' => Schedule::where('date', 'LIKE', "%$query%")
                    ->orWhere('start_time', 'LIKE', "%$query%")
                    ->orWhere('end_time', 'LIKE', "%$query%")
                    ->with('doctor.user') // Eager load related doctor and user.
                    ->get(),

                'appointments' => Appointment::whereHas('patient.user', function ($q) use ($query) {
                    $q->where('name', 'LIKE', "%$query%");
                })
                    ->orWhereHas('doctor.user', function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%$query%");
                    })
                    ->orWhereHas('schedule', function ($q) use ($query) {
                        $q->where('date', 'LIKE', "%$query%");
                    })
                    ->with(['patient.user', 'doctor.user', 'schedule']) // Eager load relationships.
                    ->get(),
            ];
        }

        return view('dashboard', compact('data', 'searchResults'));
    }
}
