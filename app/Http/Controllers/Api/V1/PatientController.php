<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PatientResource;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    use AuthorizesRequests;
    /**
     * View Patients
     */
    public function index(Request $request, Patient $patient)
    {
        $this->authorize('index', $patient);
        $patients = Patient::all();
        $patientsData = $patients->map(function ($patient) {
            return [
                'id' => $patient->id,
                'name' => $patient->user->name,
                'email' => $patient->user->email,
                'age' => $patient->age,
                'address' => $patient->address,
                'phone' => $patient->phone,
                'gender' => $patient->gender,
                'role_id' => $patient->user->role_id,
                'role' => $patient->user->role->name
            ];
        });
        return response()->json([
            'patientsData' => $patientsData
        ]);
    }

    /**
     * Create Patient
     */
    public function store(Request $request, Patient $patient)
    {
        // $this->authorize('store', $patient);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'age' => 'required|integer',
            'phone' => 'required|string',
            'address' => 'nullable|string',
            'gender' => 'required',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed!',
                'errors' => $validator->errors(),
            ], 422);
        }


        // Create the user
        $user = new User();
        $user->role_id = 3;
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password')); // Hash the password
        // Generate and store a remember token
        $rememberToken = Str::random(60);
        $user->remember_token = $rememberToken;
        $user->assignRole('Patient');
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
            'data' => [
                'id' => $patient->id,
                'name' => $user->name,
                'email' => $user->email,
                'age' => $patient->address,
                'phone' => $patient->phone,
                'gender' => $patient->gender,
            ],
        ]);
    }

    /**
     * Show Patient
     */
    public function show(Patient $patient)
    {

        $this->authorize('show', $patient);

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
     * Update Patient
     */
    public function update(Request $request, Patient $patient)
    {
        $user = User::where('id', $patient->user_id);
        $user->update($request->all());
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
     * Delete Patient
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
