<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->user()->isAdmin()) {
            $patients = Patient::all();
            return response()->json([
                'message' => 'Patients retrieved successfully',
                'data' => PatientResource::collection($patients),
            ]);
        } else {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
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
        // Find the patient by ID along with their appointments and related doctor details
        $patient = Patient::with(['appointments.doctor.user'])->findOrFail($id);

        // Prepare patient details
        $patientDetails = [
            "name" => $patient->user->name,
            "email" => $patient->user->email,
            "address" => $patient->address,
            "phone" => $patient->phone,
            "age" => $patient->age,
            "gender" => $patient->gender,
        ];

        // Categorize appointments into upcoming and past
        $appointments = [
            'upcoming' => $patient->appointments()
                ->where('date', '>', Carbon::now())
                ->get(['doctor_id', 'date', 'start_time', 'end_time'])
                ->map(function ($appointment) {
                    return [
                        'doctor_name' => $appointment->doctor->user->name ?? 'N/A',
                        'date' => $appointment->date,
                        'start_time' => $appointment->start_time,
                        'end_time' => $appointment->end_time,
                    ];
                }),
            'past' => $patient->appointments()
                ->where('date', '<', Carbon::now())
                ->get(['doctor_id', 'date', 'start_time', 'end_time'])
                ->map(function ($appointment) {
                    return [
                        'doctor_name' => $appointment->doctor->user->name ?? 'N/A',
                        'date' => $appointment->date,
                        'start_time' => $appointment->start_time,
                        'end_time' => $appointment->end_time,
                    ];
                }),
        ];

        // Return JSON response with patient and appointment details
        return response()->json([
            'patient' => $patientDetails,
            'appointments' => $appointments,
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|unique:users,email,' . $patient->user_id,
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('id', $patient->user_id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);
        $patient->update($request->all());
        return response()->json([
            'message' => 'Updated successfully',
            'patient' => [
                'id' => $patient->id,
                'name' => $patient->user->name,
                'email' => $patient->user->email,
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
