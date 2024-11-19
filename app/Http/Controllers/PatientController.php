<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Paginate patients (e.g., 10 per page)
        $patients = Patient::paginate(10);
        return view('patients.index', compact('patients'));
    }



    public function create()
    {
        return view('patients.create'); // Corrected to the 'create' view, not 'index'
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed', // Ensure password is confirmed
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'gender' => 'required',
            'age' => 'required|integer',

        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Create the user
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password')); // Hash the password
        $user->save();

        $user->assignRole('Patient');

        // Create the patient details
        $patient = new Patient();
        $patient->phone = $request->input('phone');
        $patient->age = $request->age;
        $patient->gender = $request->gender;
        $patient->address = $request->input('address');
        // Save the patient data and associate with the user
        $user->patient()->save($patient);

        // Redirect back with success message
        return redirect()->route('patients.index')->with('success', 'Patient created successfully');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $patient = Patient::find($id);
        return view('patients.edit', compact('patient'));
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
            'address' => 'required|string',
            'gender' => 'required',
            'age' => 'required|integer',

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
        $patient = $user->patient; // Assuming `patient` is a relationship on the `User` model

        if (!$patient) {
            // If no patient exists, create a new instance
            $patient = new Patient();
            $patient->user_id = $user->id; // Ensure the association
        }

        // Update patient attributes
        $patient->phone = $request->input('phone');
        $patient->age = $request->age;
        $patient->address = $request->input('address');
        $patient->gender = $request->gender;
        $patient->save();

        if ($patient) {
            return redirect()->route('patients.index')->with('success', 'Patient updated successfully');
        } else {
            return back()->with('error', 'Failed to update patient');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);
        $user->delete();

        if ($user) {
            return back()->with('success', 'Patient deleted successfully');
        }
        return back()->with('error', 'Patient not found');
    }
}
