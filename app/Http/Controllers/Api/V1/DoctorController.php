<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Models\Role as ModelsRole;

class DoctorController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    // DoctorController.php

    public function index()
    {
        $doctors = Doctor::with('user')->get();
        // Eager load the 'user' relationship
        $doctorsData = $doctors->map(function ($doctor) {
            $speciality = Speciality::where('id', $doctor->speciality_id)->pluck('name')->first();
            return [
                'id' => $doctor->id,
                'user_id' => $doctor->user_id,
                'name' => $doctor->user ? $doctor->user->name : null, // Fetch the name from the related User
                'speciality' => $speciality,
                'phone' => $doctor->phone,
                'hourly_rate' => $doctor->hourly_rate,
                'created_at' => $doctor->created_at,
                'updated_at' => $doctor->updated_at,
            ];
        });

        return response()->json($doctorsData);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users, email',
            'phone' => 'required',
            'speciality_id' => 'required|exists:specialities,id',
            'hourly_rate' => 'required',
        ]);

        $user = User::create([
            'role_id' => 2,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        // Create the associated doctor record
        Doctor::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'speciality_id' => $request->speciality_id,
            'hourly_rate' => $request->hourly_rate,
        ]);

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
