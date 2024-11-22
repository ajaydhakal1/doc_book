<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use App\Models\Patient;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class AppointmentController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view appointments', only: ['index']),
            new Middleware('permission:create appointments', only: ['create']),
            new Middleware('permission:edit appointments', only: ['edit']),
            new Middleware('permission:delete appointments', only: ['destroy']),
            new Middleware('permission:edit own appointment', only: ['editMyAppointment']),
            new Middleware('permission:delete own appointment', only: ['deleteMyAppointment']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all appointments along with the associated user and doctor details
        $appointments = Appointment::with(['patient', 'doctor'])->get();

        return view('appointments.index', compact('appointments'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $speciality = $request->speciality_id;
        $doctors = Doctor::where('speciality_id', $speciality)->with('schedules')->get();
        $patients = Patient::all();

        return view('appointments.create', compact('doctors', 'patients'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'disease' => 'required|string',
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (Auth::user()->hasRole('Admin') && empty($value)) {
                        $fail('Patient is required for Admin.');
                    }
                },
            ],
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // If the user is a Patient
        if (Auth::user()->hasRole('Patient')) {
            $userId = Auth::user()->id;
            $patient = Patient::where('user_id', $userId)->firstOrFail(); // Ensure the patient exists

            // Check and update the doctor's schedule
            $doctor = Doctor::findOrFail($request->doctor_id);
            $existingSchedule = $doctor->schedules()
                ->where('date', $request->date)
                ->where('start_time', '<=', $request->end_time)
                ->where('end_time', '>=', $request->start_time)
                ->first();

            if ($existingSchedule) {
                return redirect()->back()->with('error', 'The selected doctor is unavailable during the chosen time.');
            }

            $doctor->schedules()->create([
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => 'booked',
            ]);

            $appointment = new Appointment();
            $appointment->patient_id = $patient->id;
            $appointment->doctor_id = $request->doctor_id;
            $appointment->schedule_id = $request->schedule_id;
            $appointment->disease = $request->disease;
            $appointment->date = $request->date;
            $appointment->start_time = $request->start_time;
            $appointment->end_time = $request->end_time;
            $appointment->status = "booked";

            $appointment->save();

            return redirect()->route('home')->with('success', 'Appointment created successfully!');
        }

        // If the user is an Admin
        if (Auth::user()->hasRole('Admin')) {
            $doctor = Doctor::findOrFail($request->doctor_id);

            // Ensure there are no conflicts in the doctor's schedule
            $existingSchedule = $doctor->schedules()
                ->where('date', $request->date)
                ->where('start_time', '<=', $request->end_time)
                ->where('end_time', '>=', $request->start_time)
                ->first();

            if ($existingSchedule) {
                return redirect()->back()->with('error', 'The selected doctor is unavailable during the chosen time.');
            }

            $schedule = Schedule::create([
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'doctor_id' => $request->doctor_id,
                'status' => 'booked',
            ]);

            Appointment::create([
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'schedule_id' => $schedule->id,
                'disease' => $request->disease,
                'status' => 'booked',
            ]);



            return redirect()->route('appointments.index')->with('success', 'Appointment created successfully!');
        }

        return redirect()->back()->with('error', 'Unauthorized action.');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('appointments.edit', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'disease' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'doctor_id' => 'required_if:is_admin,true|exists:doctors,id',
        ]);

        // Fetch the appointment to update
        $appointment = Appointment::findOrFail($id);

        // Admin-specific updates
        if (Auth::user()->hasRole('Admin')) {
            $doctor = Doctor::findOrFail($request->doctor_id);

            // Check for conflicts in the doctor's appointments or schedules
            $conflict = $doctor->appointments()
                ->where('id', '!=', $appointment->id) // Exclude the current appointment
                ->where('date', $request->date)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->start_time])
                        ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->end_time]);
                })
                ->exists();

            if ($conflict) {
                return redirect()->back()->with('error', 'The selected doctor is unavailable during the chosen time.');
            }

            // Update all fields for Admin
            $appointment->doctor_id = $request->doctor_id;
        } else {
            // Ensure non-admin users cannot modify critical fields
            if ($request->has('doctor_id') || $request->has('patient_id')) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }
        }

        // Update appointment fields
        $appointment->disease = $request->disease;
        $appointment->date = $request->date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;

        // Update the related schedule in the `schedules` table
        $schedule = $appointment->doctor->schedules()->where('date', $request->date)->first();

        if ($schedule) {
            $schedule->update([
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => 'booked',
            ]);
        } else {
            // If there's no existing schedule, create a new one
            $appointment->doctor->schedules()->create([
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => 'booked',
            ]);
        }

        // Save the updated appointment
        $appointment->save();

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully!');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->schedule->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment and related schedules deleted successfully!');
    }


    public function myAppointments()
    {
        if (Auth::user()->hasRole('Patient')) {
            // Get the logged-in user's associated patient ID
            $patient = Patient::where('user_id', Auth::id())->first();

            if (!$patient) {
                // Handle the case where the user is not a patient
                return redirect()->route('home')->with('error', 'You do not have any appointments.');
            }

            // Fetch appointments for the logged-in patient
            $appointments = Appointment::where('patient_id', $patient->id)
                ->orderBy('date', 'asc') // Ensure column names match your table schema
                ->get();

            return view('appointments.my-appointments', compact('appointments'));
        } elseif (Auth::user()->hasRole('Doctor')) {
            $doctor = Doctor::where('user_id', Auth::id())->first();

            if (!$doctor) {
                // Handle the case where the user is not a patient
                return redirect()->route('home')->with('error', 'You do not have any appointments.');
            }

            $appointments = Appointment::where('doctor_id', $doctor->id)->orderBy('date', 'asc')->get();
            return view('appointments.my-appointments', compact('appointments'));
        }
    }

    public function editMyAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $userId = Auth::user()->id;
        $patient = Patient::where('user_id', $userId)->first();
        $patientId = $patient->id;
        if ($appointment->patient_id !== $patientId) {
            abort(403, 'Unauthorized action.');
        }

        $doctors = Doctor::all();
        return view('appointments.edit', compact('appointment', 'doctors'));
    }

    public function deleteMyAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->patient_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $appointment->delete();
        return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully.');
    }

}
