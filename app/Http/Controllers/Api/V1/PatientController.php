<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
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
        $patients = Patient::all();
        return response()->json($patients);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8', 
            'age' => 'required|integer',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'gender' => 'required',
            
        ]);
        
        
        // Create the user
        $user = new User();
        $user->role_id = 3;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password')); // Hash the password
        $user->save();

        // Create the patient details
        $patient = new Patient();
        $patient->user_id = $user->id;
        $patient->phone = $request->input('phone');
        $patient->age = $request->age;
        $patient->gender = $request->gender;
        $patient->address = $request->input('address');
        // Save the patient data and associate with the user
        $user->patient()->save($patient);

        // Redirect back with success message
        return response()->json([
            'message' => 'Registered successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
        ]);
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
