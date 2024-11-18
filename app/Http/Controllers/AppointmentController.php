<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Doctor;
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
        $appointments = Appointment::with(['user', 'doctor'])->get();

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
        $request->validate([
            'disease' => 'required|string',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_datetime' => 'required|date|after:now', // Ensures the appointment is in the future
        ]);

        $appointment = new Appointment();
        $appointment->user_id = Auth::user()->id; // Assuming the user is logged in
        $appointment->doctor_id = $request->doctor_id;
        $appointment->disease = $request->disease;
        $appointment->category = $request->category;
        $appointment->appointment_datetime = $request->appointment_datetime;
        $appointment->save();

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
            'appointment_datetime' => 'required|date',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->disease = $request->disease;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->category = $request->category;
        $appointment->appointment_datetime = $request->appointment_datetime;
        $appointment->save();

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
