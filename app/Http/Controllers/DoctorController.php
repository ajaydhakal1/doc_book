<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware; // Make sure to import the Role model


class DoctorController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view doctors', only: ['index']),
            new Middleware('permission:edit doctors', only: ['edit']),
            new Middleware('permission:delete doctors', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with('schedules')->get(); // Eager load schedules
        return view('doctors.index', compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('doctors.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string',
            'department' => 'required|string',
        ]);

        // Create a new user
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->save();

        // Assign the "doctor" role to the user
        $user->assignRole('Doctor');

        // Create a new doctor record
        $doctor = new Doctor();
        $doctor->phone = $request->input('phone');
        $doctor->department = $request->input('department');
        $user->doctor()->save($doctor);

        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $doctor = Doctor::with('schedules')->find($id);

        if (!$doctor) {
            return redirect()->route('doctors.index')->with('error', 'Doctor not found');
        }

        return view('doctors.edit', compact('doctor'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string',
            'department' => 'required|string',
        ]);

        // Find the User
        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        // Update User attributes
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        // Check if the User has an associated Doctor record
        $doctor = $user->doctor;

        if (!$doctor) {
            return back()->with('error', 'Doctor record not found');
        }

        // Update Doctor attributes
        $doctor->phone = $request->input('phone');
        $doctor->department = $request->input('department');

        $schedule = Schedule::find('doctor_id', $doctor->id);
        $schedule->status = $request->status;
        $doctor->save();

        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'Doctor not found');
        }

        $user->doctor()->delete(); // Delete the associated doctor record
        $user->delete(); // Delete the user record

        return back()->with('success', 'Doctor deleted successfully');
    }
}
