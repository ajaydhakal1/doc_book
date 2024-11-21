<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;

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

        return view('schedules.index', compact('groupedSchedules', 'schedules'));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::all(); // Fetch all doctors to assign schedules
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];

        return view('schedules.create', compact('doctors', 'days'));
    }

    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedules.*.day' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.status' => 'required|in:available,booked,unavailable',
        ]);

        $doctorId = $request->doctor_id;

        // Save the schedules
        foreach ($request->schedules as $scheduleData) {
            Schedule::create([
                'doctor_id' => $doctorId,
                'day' => $scheduleData['day'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'status' => $scheduleData['status'], // Save the status for the specific day
            ]);
        }

        return redirect()->route('schedules.index')->with('success', 'Schedule saved successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($doctorId, $id)
    {
        // Find the doctor along with their schedules
        $doctor = Doctor::with('schedules')->findOrFail($doctorId);
        $schedule = Schedule::findOrFail($id); // Find the schedule by ID

        // Pass both doctor and schedules to the view
        return view('schedules.edit', compact('doctor', 'schedule'));
    }





    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $doctorId)
    {
        // Validate the incoming data
        $request->validate([
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i|after:schedules.*.start_time',
            'schedules.*.status' => 'required|in:available,booked,unavailable',
        ]);

        $schedules = $request->input('schedules');

        foreach ($schedules as $scheduleId => $data) {
            // Use `find` instead of `findOrFail` to avoid unnecessary exception throwing
            $schedule = Schedule::find($scheduleId);

            if ($schedule && $schedule->doctor_id == $doctorId) {
                $schedule->update([
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'status' => $data['status'],
                ]);
            }
        }

        return redirect()->route('schedules.index')->with('success', 'Doctor schedule updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Schedule deleted successfully!');
    }


}
