<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $this->authorize('create', $appointment);

        // Normalize time input to H:i format
        try {
            $startTime = Carbon::parse($request->start_time)->format('H:i');
            $endTime = Carbon::parse($request->end_time)->format('H:i');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid time format'], 422);
        }

        // Validate the request data
        $request->merge([
            'start_time' => $startTime,
            'end_time' => $endTime,
        ]);

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'nullable|exists:patients,id',
            'disease' => 'required|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Check if the user is authenticated
        $user = Auth::guard('api')->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Verify doctor's availability by checking both schedules and appointments
        $isUnavailable = Schedule::where('doctor_id', $request->doctor_id)
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

        $isBooked = Appointment::where('doctor_id', $request->doctor_id)
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

        if ($isUnavailable || $isBooked) {
            return response()->json(['error' => 'The doctor is unavailable or already booked at the selected time'], 400);
        }

        // Determine the patient and status
        $patientId = $request->user()->isAdmin() ? $request->patient_id : $user->patient->id;
        $status = $request->user()->isAdmin() ? 'booked' : 'pending';

        // Create the schedule and appointment
        DB::transaction(function () use ($request, $patientId, $status) {
            $schedule = Schedule::create([
                'doctor_id' => $request->doctor_id,
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => 'booked',
            ]);

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
        });

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
        $this->authorize('delete', $appointment);
        $appointment->delete();
        return response()->json(['message' => 'Appointment deleted successfully'], 200);

    }
}
