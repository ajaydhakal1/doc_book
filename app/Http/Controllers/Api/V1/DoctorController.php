<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DoctorController extends Controller
{
    use AuthorizesRequests;
    /**
     * View Doctors
     */
    // DoctorController.php

    public function index()
    {
        $doctors = Doctor::with('user')->get();
        // Eager load the 'user' relationship
        $doctorsData = $doctors->map(function ($doctor) {
            $speciality = Speciality::where('id', $doctor->speciality_id)->pluck('name')->first();
            return [
                'id' => (int) $doctor->id,
                'user_id' => (int) $doctor->user_id,
                'name' => $doctor->user ? $doctor->user->name : null, // Fetch the name from the related User
                'email' => $doctor->user->email,
                'speciality' => $speciality,
                'phone' => $doctor->phone,
                'hourly_rate' => $doctor->hourly_rate,
                'created_at' => $doctor->created_at,
                'updated_at' => $doctor->updated_at,
            ];
        });

        return response()->json([
            'data' => $doctorsData,
        ]);
    }



    /**
     * Create Doctor
     */
    public function store(Request $request, Doctor $doctor)
    {
        $this->authorize('store', $doctor);
        $request->validate([
            'email' => 'required|email|unique:users, email',
            'phone' => 'required',
            'speciality_id' => 'required|exists:specialities,id',
            'hourly_rate' => 'required',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => 2,
        ]);
        // Generate and store a remember token
        $rememberToken = Str::random(60);
        $user->remember_token = $rememberToken;
        $user->assignRole("Doctor");
        $user->save();

        // Create the associated doctor record
        $doctor = Doctor::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'speciality_id' => $request->speciality_id,
            'hourly_rate' => $request->hourly_rate,
        ]);

        return response()->json([
            'message' => 'Registered successfully',
            'data' => [
                'id' => $doctor->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $doctor->phone,
                'specialty' => $doctor->speciality->name,
                'hourly_rate' => $doctor->hourly_rate,
            ],
        ]);
    }

    /**
     * Show Doctor
     */
    public function show(Doctor $doctor)
    {
        $this->authorize('show', $doctor);

        // Prepare doctor details
        $doctorDetails = [
            "id" => (int) $doctor->id, // Ensure the id is an integer
            "name" => $doctor->user->name ?? 'Unknown', // Default to 'Unknown' if name is null
            "email" => $doctor->user->email ?? '',
            "speciality" => $doctor->speciality->name ?? 'N/A', // Default to 'N/A' if speciality is null
            "phone" => $doctor->phone ?? '', // Default to empty string if phone is null
            "hourly_rate" => (float) $doctor->hourly_rate,
        ];

        // Categorize appointments into upcoming and past
        $appointments = [
            'upcoming' => $doctor->appointments()
                ->where('date', '>', Carbon::now())
                ->get(['patient_id', 'date', 'start_time', 'end_time'])
                ->map(function ($appointment) {
                    return [
                        'patient_name' => $appointment->patient->user->name ?? 'N/A', // Ensure 'patient_name' is a string
                        'date' => $appointment->date, // Ensure date is in string format (e.g., '2024-12-06')
                        'start_time' => $appointment->start_time, // Ensure time is in string format (e.g., '13:00:00')
                        'end_time' => $appointment->end_time, // Ensure time is in string format (e.g., '14:00:00')
                    ];
                }),
            'past' => $doctor->appointments()
                ->where('date', '<', Carbon::now())
                ->get(['patient_id', 'date', 'start_time', 'end_time'])
                ->map(function ($appointment) {
                    return [
                        'patient_name' => $appointment->patient->user->name ?? 'N/A', // Ensure 'patient_name' is a string
                        'date' => $appointment->date, // Ensure date is in string format (e.g., '2024-12-06')
                        'start_time' => $appointment->start_time, // Ensure time is in string format (e.g., '13:00:00')
                        'end_time' => $appointment->end_time, // Ensure time is in string format (e.g., '14:00:00')
                    ];
                }),
        ];

        // Fetch schedules
        $schedules = $doctor->schedules->map(function ($schedule) {
            return [
                'start_time' => $schedule->start_time, // Ensure time is in string format
                'end_time' => $schedule->end_time, // Ensure time is in string format
                'date' => $schedule->date, // Ensure date is in string format
            ];
        });

        // Return JSON response with doctor details, appointments, and schedules
        return response()->json([
            'doctor' => $doctorDetails,
            'appointments' => $appointments,
            'schedules' => $schedules,
        ]);
    }




    /**
     * Update Doctor
     */
    public function update(Request $request, Doctor $doctor)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'email|unique:users,email,' . $doctor->user_id,
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = User::where('id', $doctor->user_id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role_id' => $request->role_id,
        ]);
        $doctor->update($request->all());
        return response()->json([
            'message' => 'Updated successfully',
            'doctor' => [
                'id' => $doctor->id,
                'name' => $doctor->user->name,
                'email' => $doctor->user->email,
            ]
        ]);
    }

    /**
     * Delete Doctor
     */
    public function destroy(Doctor $doctor)
    {
        $doctor->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
