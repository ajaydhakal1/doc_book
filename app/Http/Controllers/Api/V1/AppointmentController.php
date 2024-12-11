<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    use AuthorizesRequests;
    /**
     * View Appointments
     */
    public function index(Appointment $appointment)
    {
        $this->authorize('index', $appointment);
        $appointments = Appointment::with(['patient.user', 'doctor.user'])->get();

        $data = [];
        foreach ($appointments as $appointment) {
            $doctorName = $appointment->doctor?->user?->name ?? 'Unknown Doctor';
            $patientName = $appointment->patient?->user?->name ?? 'Unknown Patient';

            $data = [
                'doctor' => $doctorName,
                'patient' => $patientName,
                'date' => $appointment->date,
                'start_time' => $appointment->start_time,
                'end_time' => $appointment->end_time,
                'status' => $appointment->status,
            ];
        }

        return response()->json($data);
    }



    /**
     * Create Appointment
     */
    public function store(Request $request, Appointment $appointment)
    {
        $this->authorize('store', $appointment);

        // Validate the request data
        $request->validate([
            'doctor_id' => 'required',
            'patient_id' => 'nullable',
            'disease' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Check if the user is authenticated
        $user = Auth::guard('api')->user(); // Ensure you're using the correct guard

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if the doctor exists
        $doctor = Doctor::find($request->doctor_id);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
        }

        // Check if the doctor is unavailable or already booked during the requested time slot
        $existingSchedule = Schedule::where('doctor_id', $request->doctor_id)
            ->where('date', $request->date)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start_time', '<', $request->end_time)
                            ->where('end_time', '>', $request->start_time);
                    });
            })
            ->exists();

        if ($existingSchedule) {
            return response()->json(['error' => 'The doctor is already booked or unavailable at the selected time'], 400);
        }

        // Handle patient logic
        if ($request->user()->isAdmin()) {
            $patientId = $request->patient_id;
            $status = 'booked';
        } else {
            // Assuming the patient is authenticated user
            $patientId = Auth::user()->patient->id;
            $status = 'pending';
        }

        // Check if patient exists if provided
        if ($patientId && !Patient::find($patientId)) {
            return response()->json(['error' => 'Patient not found'], 404);
        }

        // Create the schedule if the doctor is available
        $schedule = Schedule::create([
            'doctor_id' => $request->input('doctor_id'),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'status' => 'booked',
        ]);

        // Create appointment
        Appointment::create([
            'schedule_id' => $schedule->id,
            'patient_id' => $patientId,
            'doctor_id' => $request->doctor_id,
            'disease' => $request->disease,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $status,
        ]);

        return response()->json(['message' => 'Appointment created successfully'], 200);
    }


    /**
     * Show Appointment
     */
    public function show(Appointment $appointment)
    {
        $this->authorize('show', $appointment);

        return response()->json([
            'patient' => $appointment->patient->user->name,
            'doctor' => $appointment->doctor->user->name,
            'appointment' => $appointment,
        ]);
    }

    /**
     * Update Appointment
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $appointment->update($request->all());
        return response()->json([
            'message' => 'Appointment updated successfully',
            'appointment' => [
                'id' => $appointment->id,
                'schedule_id' => $appointment->schedule_id,
                'patient' => $appointment->patient->user->name,
                'doctor' => $appointment->doctor->user->name,
                'disease' => $appointment->disease,
                'start_time' => $appointment->start_time,
                'end_time' => $appointment->end_time,
                'status' => $appointment->status,
            ]
        ], 200);
    }

    /**
     * Delete Appointment
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorize('destroy', $appointment);
        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted successfully'], 200);

    }
}
