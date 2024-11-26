<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Speciality;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class SpecialityController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view specialities', only: ['index']),
            new Middleware('permission:create specialities', only: ['create']),
            new Middleware('permission:edit specialities', only: ['edit']),
            new Middleware('permission:delete specialities', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $specialities = Speciality::paginate(5);
        return view('specialities.index', compact('specialities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('specialities.create');
    }

    /**
     * Store a newly created specialty in the database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        // Save the new specialty by updating the doctors table
        $speciality = new Speciality();
        $speciality->name = $request->name;
        $speciality->save();

        return redirect()->route('specialities.index')->with('success', 'Specialty added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $speciality = Speciality::find($id);
        return view('specialities.edit', compact('speciality'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $speciality = Speciality::find($id);
        $request->validate
        ([
                'name' => 'required|string',
            ]);
        $speciality->name = $request->name;
        $speciality->save();
        return redirect()->route('specialities.index')->with('success', 'Specialty updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $specialty = Speciality::find($id);
        $specialty->delete();
        return redirect()->route('specialities.index')->with('success', 'Specialty deleted successfully!');
    }

    public function chooseSpeciality()
    {
        $specialities = Speciality::all();
        return view('specialities.choose', compact('specialities'));
    }

    public function doctorsBySpeciality($id)
    {
        // Find the speciality by its ID
        $speciality = Speciality::findOrFail($id);

        // Retrieve doctors associated with the speciality
        $doctors = Doctor::where('speciality_id', $speciality->id)->get();

        // Return the view with doctors and speciality
        return view('specialities.doctors', compact('doctors', 'speciality'));
    }

}
