<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PatientController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view patients', only: ['index']),
            new Middleware('permission:edit patients', only: ['edit']),
            new Middleware('permission:delete patients', only: ['destroy']),
        ];
    }

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
        $genders = [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
        ];

        // Pass $genders to the view.
        return view('patients.create', compact('genders'));
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
        // In your controller or a service, define the gender options:
        $genders = [
            'male' => 'Male',
            'female' => 'Female',
            'other' => 'Other',
        ];

        // Pass $genders to the view.
        return view('patients.edit', compact('patient', 'genders'));

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
        $patient = Patient::find($id);
        $patient->delete();

        if ($patient) {
            return back()->with('success', 'Patient deleted successfully');
        }
        return back()->with('error', 'Patient not found');
    }
}
