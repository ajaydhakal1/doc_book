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
        return view('schedules.create', compact('doctors'));
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
        ]);

        $doctorId = $request->doctor_id;

        // Save the schedules
        foreach ($request->schedules as $scheduleData) {
            Schedule::create([
                'doctor_id' => $doctorId,
                'date' => $scheduleData['date'],
                'start_time' => $scheduleData['start_time'],
                'end_time' => $scheduleData['end_time'],
                'status' => $scheduleData['status'],
            ]);
        }

        return redirect()->route('schedules.index')->with('success', 'Schedules saved successfully!');
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
    public function update(Request $request, $id)
    {
        // Validate the incoming data
        $request->validate([
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'status' => 'required|in:available,booked,unavailable',
        ]);

        $schedule = Schedule::findOrFail($id);

        $schedule->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status,
        ]);

        return redirect()->route('schedules.index')->with('success', 'Schedule updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Find the schedule by ID
        $schedule = Schedule::findOrFail($id);

        // Delete the schedule
        $schedule->delete();

        // Redirect with a success message
        return redirect()->route('schedules.index')
            ->with('success', 'Schedule deleted successfully!');
    }
}
