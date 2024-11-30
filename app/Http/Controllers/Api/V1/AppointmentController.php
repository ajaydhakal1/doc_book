<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Mail\AppointmentCreated;
use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    public function store(Request $request)
    {


        $request->validate([
            'doctor_id' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'patient_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    // Only apply the validation if the logged-in user is an admin
                    if ((Auth::user()->isAdmin) && empty($value)) {
                        $fail('The patient is required when submitting as admin.');
                    }
                }
            ],
        ]);

        $user = Auth::user();

        // Create a new appointment
        $schedule = new Schedule();
        $schedule->doctor_id = $request->doctor_id;
        $schedule->date = $request->date;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->status = 'booked';
        $schedule->save();

        $appointment = new Appointment();
        $appointment->schedule_id = $schedule->id;
        $appointment->disease = $request->disease;
        $appointment->doctor_id = $request->doctor_id;
        if ($user->isAdmin) {
            $appointment->patient_id = $request->patient_id;
        } else {
            $patientId = Auth::user()->patient->id;
            $appointment->patient_id = $patientId;
        }
        $appointment->date = $request->date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        if ($user->isAdmin) {
            $appointment->status = 'booked';
        } else {
            $appointment->status = 'pending';
        }
        $appointment->save();


        if ($appointment) {
            Mail::to($appointment->patient->user->email)->queue(new AppointmentCreated($appointment));
            if ($user->isAdmin) {
                return redirect()->route('appointments.index')->with('success', 'Appointment created successfully');
            } else {
                return redirect()->route('my-appointments')->with('success', 'Appointment created successfully');
            }
        } else {
            return redirect()->back()->with('error', 'Failed to create appointment');
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
