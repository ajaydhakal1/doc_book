<?php

namespace App\Http\Controllers;

use App\Mail\AppointmentCreated;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use App\Models\Patient;
use App\Models\PatientHistory;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class AppointmentController extends Controller implements HasMiddleware
{

    public static function middleware()
    {
        return [
            new Middleware('permission:view appointments', only: ['index']),
            new Middleware('permission:create appointments', only: ['create']),
            new Middleware('permission:edit appointments', only: ['edit']),
            new Middleware('permission:delete appointments', only: ['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Fetch all appointments along with the associated user and doctor details
        $appointments = Appointment::with(['patient', 'doctor'])->paginate(10);

        return view('appointments.index', compact('appointments'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $speciality = $request->speciality_id;
        $doctors = Doctor::where('speciality_id', $speciality)->with('schedules')->get();
        $patients = Patient::all();

        return view('appointments.create', compact('doctors', 'patients'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'doctor_id' => 'required',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'patient_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    // Only apply the validation if the logged-in user is an admin
                    if (Auth::user()->hasRole('Admin') && empty($value)) {
                        $fail('The patient is required when submitting as admin.');
                    }
                }
            ],
        ]);
        // Create a new appointment
        $schedule = new Schedule();
        $schedule->doctor_id = $request->doctor_id;
        $schedule->date = $request->date;
        $schedule->start_time = $request->start_time;
        $schedule->end_time = $request->end_time;
        $schedule->status = 'booked';
        $schedule->save();

        $appointment = new Appointment();
        $appointment->schedule_id = $schedule->id;
        $appointment->disease = $request->disease;
        $appointment->doctor_id = $request->doctor_id;
        if (Auth::user()->hasRole('Admin')) {
            $appointment->patient_id = $request->patient_id;
        } else {
            $patientId = Auth::user()->patient->id;
            $appointment->patient_id = $patientId;
        }
        $appointment->date = $request->date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        if (Auth::user()->hasRole('Admin')) {
            $appointment->status = 'booked';
        } else {
            $appointment->status = 'pending';
        }
        $appointment->save();


        if ($appointment) {
            Mail::to($appointment->patient->user->email)->queue(new AppointmentCreated($appointment));
            if (Auth::user()->hasRole('Admin')) {
                return redirect()->route('appointments.index')->with('success', 'Appointment created successfully');
            } else {
                return redirect()->route('my-appointments')->with('success', 'Appointment created successfully');
            }
        } else {
            return redirect()->back()->with('error', 'Failed to create appointment');
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        return view('appointments.edit', compact('appointment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'disease' => 'required|string|max:255',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'doctor_id' => 'required_if:is_admin,true|exists:doctors,id',
        ]);

        // Fetch the appointment to update
        $appointment = Appointment::findOrFail($id);

        // Admin-specific updates
        if (Auth::user()->hasRole('Admin')) {
            $doctor = Doctor::findOrFail($request->doctor_id);

            // Check for conflicts in the doctor's appointments or schedules
            $conflict = $doctor->appointments()
                ->where('id', '!=', $appointment->id) // Exclude the current appointment
                ->where('date', $request->date)
                ->where(function ($query) use ($request) {
                    $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                        ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                        ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->start_time])
                        ->orWhereRaw('? BETWEEN start_time AND end_time', [$request->end_time]);
                })
                ->exists();

            if ($conflict) {
                return redirect()->back()->with('error', 'The selected doctor is unavailable during the chosen time.');
            }

            // Update all fields for Admin
            $appointment->doctor_id = $request->doctor_id;
        } else {
            // Ensure non-admin users cannot modify critical fields
            if ($request->has('doctor_id') || $request->has('patient_id')) {
                return redirect()->back()->with('error', 'Unauthorized action.');
            }
        }

        // Update appointment fields
        $appointment->disease = $request->disease;
        $appointment->date = $request->date;
        $appointment->start_time = $request->start_time;
        $appointment->end_time = $request->end_time;
        $appointment->save();

        // Update the related schedule in the `schedules` table
        $schedule = $appointment->doctor->schedules()->where('date', $request->date)->first();

        if ($schedule) {
            $schedule->update([
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => 'booked',
            ]);
        } else {
            // If there's no existing schedule, create a new one
            $appointment->doctor->schedules()->create([
                'date' => $request->date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'status' => 'booked',
            ]);
        }

        // Save the updated appointment
        $appointment->save();

        return redirect()->route('appointments.index')->with('success', 'Appointment updated successfully!');
    }

    public function updateStatus(Request $request, Appointment $appointment, Schedule $appointmentSlot)
    {
        $request->validate([
            'status' => 'required|in:pending,booked,rescheduled,cancelled,completed',
        ]);

        $status = $request->status;

        // Prevent modifying a completed appointment
        if ($appointment->status === 'completed' && $status !== 'completed') {
            return redirect()->route('appointments.index')->with('error', 'This appointment is already completed and cannot be modified.');
        }

        switch ($status) {
            case 'pending':
                $appointment->status = 'pending';

                // Check if the slot is already booked
                $existingSlot = Schedule::where('doctor_id', $appointment->doctor_id)
                    ->where('date', $appointment->date)
                    ->where('start_time', $appointment->start_time)
                    ->where('end_time', $appointment->end_time)
                    ->where('status', 'booked')
                    ->first();

                if ($existingSlot) {
                    return redirect()->route('appointments.index')->with('error', 'This time slot is already booked.');
                }
                break;

            case 'booked':
                $appointment->status = 'booked';

                // Create or update the appointment slot
                $existingSlot = Schedule::where('doctor_id', $appointment->doctor_id)
                    ->where('date', $appointment->date)
                    ->where('start_time', $appointment->start_time)
                    ->where('end_time', $appointment->end_time)
                    ->where('status', 'booked')
                    ->first();

                if ($existingSlot) {
                    return redirect()->route('appointments.index')->with('error', 'This time slot is already booked.');
                }

                $appointmentSlot->fill([
                    'status' => 'booked',
                    'start_time' => $appointment->start_time,
                    'doctor_id' => $appointment->doctor_id,
                    'end_time' => $appointment->end_time,
                    'date' => $appointment->date,
                ])->save();
                break;

            case 'rescheduled':
                $appointment->status = 'rescheduled';
                break;

            case 'cancelled':
                $appointment->status = 'cancelled';

                // Delete associated slot
                Schedule::where('doctor_id', $appointment->doctor_id)
                    ->where('date', $appointment->date)
                    ->where('start_time', $appointment->start_time)
                    ->where('end_time', $appointment->end_time)
                    ->delete();

                // Delete the appointment
                $appointment->delete();

                return redirect()->route('appointments.index')->with('success', 'Appointment cancelled successfully.');
                break;

            case 'completed':
                if ($appointment->status !== 'completed') {
                    $appointment->status = 'completed';

                    // Delete the corresponding appointment slot
                    DB::statement('PRAGMA foreign_keys = OFF');
                    Schedule::where('doctor_id', $appointment->doctor_id)
                        ->where('date', $appointment->date)
                        ->where('start_time', $appointment->start_time)
                        ->where('end_time', $appointment->end_time)
                        ->delete();
                    DB::statement('PRAGMA foreign_keys = ON');

                }

                // Check for existing reviews
                // if (!$appointment->reviews || $appointment->reviews->isEmpty()) {
                //     return back()->with('error', 'Please add a review first.');
                // }

                // Calculate total fee
                $startTime = Carbon::parse($appointment->start_time);
                $endTime = Carbon::parse($appointment->end_time);
                $durationInHours = $startTime->diffInHours($endTime);
                $totalFee = $durationInHours * $appointment->doctor->hourly_rate;
                // dd($appointment->id);
                // Create payment record
                $payment = Payment::create([
                    'appointment_id' => $appointment->id,
                    'amount' => $totalFee,
                    'patient_id' => $appointment->patient_id,
                ]);

                // Create patient history record
                $firstReview = $appointment->reviews->first();
                PatientHistory::create([
                    'appointment_id' => $appointment->id,
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    // 'review_id' => $firstReview->id,
                    'payment_id' => $payment->id,
                ]);

                // Send email notification
                $patient = Patient::find($appointment->patient_id);

                $appointment->save();

                return redirect()->route('appointments.index')->with('success', 'Appointment marked as completed.')->with('showReviewModal', true);
                break;

            default:
                $appointment->status = 'pending';
        }

        $appointment->save();

        return redirect()->route('appointments.index')->with('success', 'Appointment status updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->schedule->delete();

        return redirect()->route('appointments.index')->with('success', 'Appointment and related schedules deleted successfully!');
    }


    public function myAppointments()
    {
        $userId = Auth::user()->id;
        $patient = Patient::where('user_id', $userId)->first();
        if (Auth::user()->hasRole('Patient')) {
            // Get the logged-in user's associated patient ID
            $payments = Payment::where('patient_id', $patient->id)
                ->with(['patient', 'appointment'])->paginate(5);
            $data['payments'] = $payments;
            $data['payments'] = Payment::with(['patient', 'appointment'])->paginate(5);
            // Fetch appointments for the logged-in patient
            $data['appointments'] = Appointment::where('patient_id', $patient->id)
                ->orderBy('date', 'asc') // Ensure column names match your table schema
                ->get();
            return view('appointments.my-appointments', $data);


        } elseif (Auth::user()->hasRole('Doctor')) {
            $doctor = Doctor::where('user_id', Auth::id())->first();

            if (!$doctor) {
                // Handle the case where the user is not a patient
                return redirect()->route('home')->with('error', 'You do not have any appointments.');
            }

            $appointments = Appointment::where('doctor_id', $doctor->id)->orderBy('date', 'asc')->get();
            return view('appointments.my-appointments', compact('appointments'));
        }
    }

    public function deleteMyAppointment($id, Request $request)
    {
        // Fetch the appointment or return a 404 if not found
        $appointment = Appointment::findOrFail($id);

        // Find the associated schedule based on appointment details
        $schedule = Schedule::where('doctor_id', $appointment->doctor_id)
            ->where('date', $appointment->date)
            ->where('start_time', $appointment->start_time)
            ->where('end_time', $appointment->end_time)
            ->first();

        // Ensure the user has the "Patient" role
        if (Auth::user()->hasRole('Patient')) {
            // Check if the authenticated user is the owner of the appointment
            if ($appointment->patient_id === Auth::user()->patient->id) {
                // Delete the schedule if it exists
                if ($schedule) {
                    $schedule->delete();
                }

                // Delete the appointment
                $appointment->delete();

                return redirect()
                    ->route('my-appointments')
                    ->with('success', 'Appointment deleted successfully.');
            }

            // User does not own the appointment
            return redirect()
                ->back()
                ->with('error', 'You do not have permission to delete this appointment.');
        }

        // If the user is not a patient but has permission to delete
        if ($schedule) {
            $schedule->delete();
        }
        $appointment->delete();

        return redirect()
            ->route('my-appointments')
            ->with('success', 'Appointment deleted successfully.');
    }

}