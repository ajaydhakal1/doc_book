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
     * Display a listing of the resource.
     */
    public function index(Appointment $appointment)
    {
        $this->authorize('index', $appointment);
        $appointments = Appointment::with(['patient.user', 'doctor.user'])->get();

        $data = [];
        foreach ($appointments as $appointment) {
            $doctorName = $appointment->doctor?->user?->name ?? 'Unknown Doctor';
            $patientName = $appointment->patient?->user?->name ?? 'Unknown Patient';

            $data[] = [
                'doctor' => $doctorName,
                'patient' => $patientName,
            ];
        }

        return response()->json($data);
    }



    /**
     * Store a newly created resource in storage.
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

        // dd("아니");
        // Create a new schedule
        $schedule = Schedule::create([
            'doctor_id' => $request->input('doctor_id'),
            'date' => $request->input('date'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'status' => 'booked',
        ]);

        // Check if the doctor exists
        $doctor = Doctor::find($request->doctor_id);
        if (!$doctor) {
            return response()->json(['error' => 'Doctor not found'], 404);
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $this->authorize('update', $appointment);

        $appointment->update($request->all());
        return response()->json([
            'message' => 'Appointment updated successfully',
            'appointment' => $appointment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment)
    {
        $this->authorize('destroy', $appointment);
        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted successfully'], 200);

    }
}
