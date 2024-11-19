<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
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
    public function create()
    {
        $doctors = Doctor::all(); // Fetch all doctors
        return view('appointments.create', compact('doctors'));
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
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Assuming the patient_id is related to the authenticated user
        $userId = Auth::user()->id;
        $patient = Patient::where('user_id', $userId)->first();
        $patientId = $patient->id;
        // Create new appointment
        $appointment = new Appointment();


        $appointment->patient_id = $patientId;  // Assign the patient's ID
        $appointment->doctor_id = $request->doctor_id;
        $appointment->disease = $request->disease;
        $appointment->category = $request->category;  // Optional
        $appointment->date = $request->date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;

        // Save the appointment
        $appointment->save();

        // Redirect to the appointments index with a success message
        return redirect()->route('appointments.index')->with('success', 'Appointment created successfully!');
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
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
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
        // Fetch appointments for the logged-in patient
        $appointments = Appointment::where('patient_id', Auth::id())
            ->orderBy('appointment_date', 'asc')
            ->get();

        return view('appointments.my-appointments', compact('appointments'));
    }
}
