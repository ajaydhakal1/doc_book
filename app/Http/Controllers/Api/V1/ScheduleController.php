<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch schedules with related doctor and user
        $schedules = Schedule::with('doctor.user')->get();
        // Group schedules by doctor_id
        $groupedSchedules = $schedules->groupBy('doctor_id');
        // Return grouped schedules
        foreach ($groupedSchedules as $doctorId => $schedules) {
            foreach ($schedules as $schedule) {
                return response()->json([
                    'doctor' => $schedule->doctor,
                    'schedules' => $schedule->toArray(),
                ]);
            }
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedules.*.date' => 'required|date',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.status' => 'required|in:available,booked,unavailable',
            'schedules.*.appointment_id' => 'nullable|exists:appointments,id',
        ]);

        $user = Auth::user();

        if ($user->isDoctor) {
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
            return response()->json([
                'message' => 'Schedules created successfully',
                'data' => [
                    'doctor' => Doctor::where('id', $doctor->id),
                    'schedules' => Schedule::where('doctor_id', $doctor->id),
                ],
            ]);
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
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
