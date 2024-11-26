<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ScheduleController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view schedules', only: ['index']),
            new Middleware('permission:view own schedules', only: ['mySchedules']),
            new Middleware('permission:create schedules', only: ['create']),
            new Middleware('permission:edit schedules', only: ['edit']),
            new Middleware('permission:delete schedules', only: ['destroy']),
        ];
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch schedules with related doctor and user
        $schedules = Schedule::with('doctor.user')->get();

        // Group schedules by doctor_id
        $groupedSchedules = $schedules->groupBy('doctor_id');

        return view('schedules.index', compact('groupedSchedules', 'schedules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        if ($user->hasRole('Doctor')) {
            $doctor = Auth::user()->doctor;
            return view('schedules.create', compact('doctor'));
        } else {
            $doctors = Doctor::all(); // Fetch all doctors to assign schedules
            return view('schedules.create', compact('doctors'));
        }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedules.*.date' => 'required|date',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.status' => 'required|in:available,booked,unavailable',
            'schedules.*.appointment_id' => 'nullable|exists:appointments,id', // Allow nullable but validate if provided
        ]);

        $user = Auth::user();

        if ($user->hasRole('Doctor')) {
            $doctor = $user->doctor; // Assuming Doctor relationship exists in the User model
            $schedules = $request->input('schedules');

            foreach ($schedules as $schedule) {
                Schedule::create([
                    'doctor_id' => $doctor->id,
                    'appointment_id' => $schedule['appointment_id'] ?? null, // Handle nullable appointment_id
                    'date' => $schedule['date'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'status' => $schedule['status'],
                ]);
            }

            return redirect()->route('my-schedules')->with('success', 'Schedules saved successfully!');
        } else {
            $doctorId = $request->doctor_id;

            foreach ($request->schedules as $scheduleData) {
                // Handle cases where appointment_id is not provided
                $appointmentId = $scheduleData['appointment_id'] ?? null;

                // Optional check for appointment_id if provided
                if ($appointmentId && !Appointment::find($appointmentId)) {
                    return redirect()->back()->with('error', 'Appointment not found.');
                }

                Schedule::create([
                    'doctor_id' => $doctorId,
                    'appointment_id' => $appointmentId, // Nullable appointment_id
                    'date' => $scheduleData['date'],
                    'start_time' => $scheduleData['start_time'],
                    'end_time' => $scheduleData['end_time'],
                    'status' => $scheduleData['status'],
                ]);
            }

            return redirect()->route('schedules.index')->with('success', 'Schedules saved successfully!');
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Find the schedule by ID and load the associated doctor and user relationships
        $schedule = Schedule::with('doctor.user')->findOrFail($id);

        // Retrieve the doctor from the schedule
        $doctor = $schedule->doctor;

        // Pass the doctor and the specific schedule to the view
        return view('schedules.edit', compact('doctor', 'schedule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $doctorId)
    {
        // Validate the incoming data for all schedules
        $request->validate([
            'schedules.*.date' => 'required|date',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.status' => 'required|in:available,booked,unavailable',
        ]);

        // Loop through each schedule and update it
        foreach ($request->schedules as $scheduleId => $scheduleData) {
            $schedule = Schedule::findOrFail($scheduleId);

            $schedule->update([
                'date' => $scheduleData['date'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'status' => $scheduleData['status'],
            ]);
        }

        return redirect()->route('schedules.index')->with('success', 'Schedules updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the schedule by ID
        $schedule = Schedule::findOrFail($id);
        $user = Auth::user();

        // Check if the schedule has an associated appointment
        if ($schedule->appointment) {
            // Delete the associated appointment
            $schedule->appointment->delete();
            if ($user->hasRole('Doctor')) {
                return redirect()->route('my-schedules')
                    ->with('success', 'Schedule deleted successfully!');
            } else {
                return redirect()->route('schedules.index')
                    ->with('success', 'Schedule deleted successfully!');
            }
        } else {
            // Handle the case where the schedule doesn't have an associated appointment
            $schedule->delete();
            if ($user->hasRole('Doctor')) {
                return redirect()->route('my-schedules')
                    ->with('success', 'Schedule deleted successfully!');
            } else {
                // Redirect with a success message
                return redirect()->route('schedules.index')
                    ->with('success', 'Schedule deleted successfully!');
            }
        }
    }

    public function mySchedules()
    {
        $schedules = Schedule::where('doctor_id', auth()->user()->doctor->id)->get();
        return view('schedules.my-schedules', compact('schedules'));
    }


}
