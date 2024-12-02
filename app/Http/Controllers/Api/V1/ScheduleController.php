<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{

    use AuthorizesRequests;
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

    public function store(Request $request, Schedule $schedule)
    {
        $this->authorize('store', $schedule);
        // Validate the request data
        $request->validate([
            'doctor_id' => 'required',
            'schedules.*.date' => 'required',
            'schedules.*.start_time' => 'required',
            'schedules.*.end_time' => 'required|after:schedules.*.start_time',
            'schedules.*.status' => 'required|in:booked,unavailable',
            'schedules.*.appointment_id' => 'nullable',
        ]);

        // Check if the user is authenticated
        $user = Auth::guard('api')->user(); // Ensure you're using the correct guard

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the user has a doctor record
        if ($request->user()->isDoctor()) {
            $doctor = $user->doctor;
            if (!$doctor) {
                return response()->json(['error' => 'Doctor record not found for this user'], 404);
            }
        } else {
            $doctor_id = $request->doctor_id;
        }


        // Get schedules from the request and make sure it's an array
        $schedules = $request->input('schedules');

        // Check if schedules is an array
        if (!is_array($schedules) || empty($schedules)) {
            return response()->json(['error' => 'Schedules are required and must be an array'], 400);
        }

        // Process the schedules
        foreach ($schedules as $schedule) {
            if ($request->user()->isDoctor()) {
                Schedule::create([
                    'doctor_id' => $doctor->id,
                    'appointment_id' => $schedule['appointment_id'] ?? null, // Handle nullable appointment_id
                    'date' => $schedule['date'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'status' => $schedule['status'],
                ]);
            } else {
                Schedule::create([
                    'doctor_id' => $doctor_id,
                    'appointment_id' => $schedule['appointment_id'] ?? null, // Handle nullable appointment_id
                    'date' => $schedule['date'],
                    'start_time' => $schedule['start_time'],
                    'end_time' => $schedule['end_time'],
                    'status' => $schedule['status'],
                ]);
            }
        }

        return response()->json([
            'message' => 'Schedules created successfully',
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id, Schedule $schedule)
    {
        $this->authorize('show', $schedule);
        $doctor = Doctor::find($id);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }
        return response()->json([
            'doctor' => $doctor->user->name,
            'schedules' => $doctor->schedules,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $this->authorize('update', $schedule);
        $schedule->update($request->all());
        return response()->json([
            'message' => 'Schedule updated successfully',
            'schedule' => $schedule,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Schedule $schedule)
    {
        $this->authorize('delete', $schedule);
        $schedule->delete();
        return response()->json([
            'message' => 'Schedule deleted successfully',
        ]);
    }
}
