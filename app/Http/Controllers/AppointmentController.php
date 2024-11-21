<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
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
        $doctors = Doctor::where('speciality_id', $speciality)->get();
        $patients = Patient::all(); // Fetch all doctors
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
                        $fail('Patient is required for Admin');
                    }
                }
            ],
            'date' => 'required|date',
            'time' => 'required',
        ]);

        // Assuming the patient_id is related to the authenticated user

        
        if (Auth::user()->hasRole('Patient')) {
            $userId = Auth::user()->id;
            $patient = Patient::where('user_id', $userId)->first();
            $patientId = $patient->id;
            $appointment = new Appointment();
            $appointment->patient_id = $patientId;  // Assign the patient's ID
            $appointment->doctor_id = $request->doctor_id;
            $appointment->disease = $request->disease;
            $appointment->category = $request->category;  // Optional
            $appointment->date = $request->date;
            $appointment->time = $request->time;
            $appointment->status = "booked";
            // Save the appointment
            $appointment->save();

            // Redirect to the appointments index with a success message
            return redirect()->route('home')->with('success', 'Appointment created successfully!');
        } else if (Auth::user()->hasRole('Admin')) {
            $appointment = new Appointment();
            $patientId = $request->input('patient_id');
            $appointment->patient_id = $patientId;
            $appointment->doctor_id = $request->doctor_id;
            $appointment->disease = $request->disease;
            $appointment->category = $request->category;  // Optional
            $appointment->date = $request->date;
            $appointment->time = $request->time;
            $appointment->status = "booked";
            // Save the appointment
            $appointment->save();

            // Redirect to the appointments index with a success message
            return redirect()->route('appointments.index')->with('success', 'Appointment created successfully!');
        }

    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        $doctors = Doctor::all(); // Assuming you have a Doctor model to get the list of doctors
        return view('appointments.edit', compact('appointment', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'disease' => 'required|string|max:255',
            'doctor_id' => 'required|exists:doctors,id',
            'category' => 'nullable|string|max:255',
            'date' => 'required|date',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->disease = $request->disease;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->category = $request->category;
        $appointment->date = $request->date;
        $appointment->time = $request->time;
        $appointment->status = $request->status;
        $appointment->save();

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        if ($appointment) {
            return redirect()->route('appointments.index')->with('success', 'Appointment deleted successfully');
        }
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
