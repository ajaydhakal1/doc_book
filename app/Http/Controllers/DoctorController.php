<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware; // Make sure to import the Role model
use Illuminate\Support\Facades\Mail;

class DoctorController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view doctors', only: ['index']),
            new Middleware('permission:edit doctors', only: ['edit']),
            new Middleware('permission:delete doctors', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::with('speciality', 'user')->paginate(10);
        return view('doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specialities = Speciality::all();
        if (Auth::user()) {
            if (Auth::user()->hasRole('Admin')) {
                return view('doctors.create', compact('specialities'));
            } else {
                abort(403, 'You dont have permission to perform this action');
            }
        } else {
            return view('auth.doctor-register', compact('specialities'));
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string',
            'speciality_id' => 'required|exists:specialities,id',
            'hourly_rate' => 'required',
        ]);

        // Create a new user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $user->assignRole('Doctor');

        // Create the associated doctor record
        Doctor::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'speciality_id' => $request->speciality_id,
            'hourly_rate' => $request->hourly_rate,
        ]);

        if (Auth::user()) {
            if (Auth::user()->hasRole('Admin')) {
                return redirect()->route('doctors.index')->with('success', 'Doctor created successfully');
            }
        } else {
            Mail::to($user->email)->queue(new RegisterMail($user));
            return redirect()->route('login')->with('success', 'Registration Successful! Please login to continue');
        }
    }

    public function edit($id)
    {
        $doctor = Doctor::with('speciality')->find($id);
        $specialities = Speciality::all();
        if (!$doctor) {
            return redirect()->route('doctors.index')->with('error', 'Doctor not found');
        }

        return view('doctors.edit', compact('doctor', 'specialities'));
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required',
            'speciality_id' => 'required',
            'hourly_rate' => 'required',
        ]);

        $user = User::find($id);

        // Update user details
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->save();

        $doctor = $user->doctor;

        if (!$doctor) {
            return back()->with('error', 'Doctor record not found');
        }

        // Update doctor details
        $doctor->phone = $request->input('phone');
        $doctor->speciality_id = $request->input('speciality_id');
        $doctor->hourly_rate = $request->input('hourly_rate');
        $doctor->save();

        return redirect()->route('doctors.index')->with('success', 'Doctor updated successfully');
    }

    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->user->delete(); // Cascade delete user and related doctor
        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully');
    }
}
