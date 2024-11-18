<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role; // Make sure to import the Role model


class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::all();
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
        $user->password = bcrypt($request->input('password')); // Make sure to hash the password
        $user->save();

        // Assign the "doctor" role to the user
        $user->assignRole('Doctor'); // Make sure the "doctor" role exists

        // Create a new doctor record
        $doctor = new Doctor();
        $doctor->phone = $request->phone;
        $doctor->department = $request->department;
        $user->doctor()->save($doctor);

        // Redirect with success message
        return redirect()->route('doctors.index')->with('success', 'Doctor created successfully');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $doctor = Doctor::find($id);
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
            'phone' => 'required',
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
        $doctor = $user->doctor; // Assuming `doctor` is a relationship on the `User` model

        if (!$doctor) {
            // If no doctor exists, create a new instance
            $doctor = new Doctor();
            $doctor->user_id = $user->id; // Ensure the association
        }

        // Update Doctor attributes
        $doctor->phone = $request->input('phone');
        $doctor->department = $request->input('department');
        $doctor->save();

        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();

        if ($user) {
            return back()->with('success', 'Doctor deleted successfully');
        }
        return back()->with('error', 'Doctor not found');
    }
}
