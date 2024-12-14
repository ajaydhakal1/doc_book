<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Speciality;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SpecialityController extends Controller
{
    use AuthorizesRequests;
    /**
     * View Specialties
     */
    public function index()
    {
        $specialities = Speciality::all();
        return response()->json($specialities);
    }

    /**
     * Create Specialty
     */
    public function store(Request $request, Speciality $speciality)
    {
        $this->authorize('store', $speciality);
        $request->validate([
            'name' => 'required|string',
        ]);

        // Save the new specialty by updating the doctors table
        $speciality = new Speciality();
        $speciality->name = $request->name;
        $speciality->save();

        return response()->json(['message' => 'Specialty created successfully'], 201);
    }

    /**
     * Show Specialty
     */
    public function show(string $id)
    {
        // Find the speciality by its ID
        $speciality = Speciality::findOrFail($id);

        // Retrieve doctors associated with the speciality
        $doctors = Doctor::where('speciality_id', $speciality->id)->get();
        foreach ($doctors as $doctor) {
            $name = $doctor->user->name;
        }
        return response()->json([
            'speciality' => $speciality,
            'doctors' => $doctors,
            'name' => $name,
        ]);
    }

    /**
     * Update Specialty
     */
    public function update(Request $request, string $id, Speciality $speciality)
    {
        $this->authorize('update', $speciality);
        $speciality = Speciality::findOrFail($id);
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 422);
        }

        $speciality->name = $input['name'];
        $speciality->save();
        return response()->json([
            'message' => 'Specialty updated successfully',
            'name' => $speciality->name
        ], 200);

    }

    /**
     * Delete Specialty
     */
    public function destroy(Speciality $speciality)
    {
        $this->authorize('destroy', $speciality);
        $speciality->delete();
        return response()->json(['message' => 'Specialty deleted successfully'], 200);
    }
}
