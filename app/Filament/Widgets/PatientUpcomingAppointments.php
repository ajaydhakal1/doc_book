<?php

namespace App\Filament\Widgets;

use Filament\Tables;
use App\Models\Appointment;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\TextColumn;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class PatientUpcomingAppointments extends BaseWidget
{
    protected int|string|array $columnSpan = 'full';

    protected static ?string $icon = 'heroicon-o-calendar';

    protected static ?int $sort = 2;

    protected static ?string $heading = 'Upcoming Appointments';

    public function table(Tables\Table $table): Tables\Table
    {
        // Define the date range for upcoming appointments
        $today = Carbon::today();  // Current date for filtering

        $user = Auth::user();  // Get the currently authenticated user

        // Base query to filter appointments after today
        $query = Appointment::query()
            ->where('date', '>=', $today);  // Filter appointments for today and beyond
        
        // Filter by roles
        if ($user->role_id == 1) {
            // Admins can view all appointments
        } elseif ($user->role_id == 2 && $user->doctor) {
            // Filter by doctor's appointments (only if the user has a doctor relationship)
            $query->where('doctor_id', $user->doctor->id);
        } elseif ($user->role_id == 3 && $user->patient) {  // Assuming role_id 3 is for patients
            // Filter by patient's appointments (only if the user has a patient relationship)
            $query->where('patient_id', $user->patient->id);
        }

        return $table
            ->query($query)
            ->columns([
                TextColumn::make('patient.user.name')  // Display the patient's name
                    ->label('Patient Name')
                    ->searchable(),
                TextColumn::make('doctor.user.name')  // Display the doctor's name
                    ->label('Doctor Name'),
                TextColumn::make('date')  // Appointment date
                    ->label('Appointment Date')
                    ->date('F j, Y'),  // Format the date as 'Month day, year'
                TextColumn::make('start_time')  // Appointment start time
                    ->label('Start Time')
                    ->time('H:i A'),  // Format the time as 24-hour with AM/PM
                TextColumn::make('status')  // Appointment status
                    ->label('Status'),
            ]);
    }

    public static function canView(): bool
    {
        return Auth::user()->role_id !== 1;
    }
}
