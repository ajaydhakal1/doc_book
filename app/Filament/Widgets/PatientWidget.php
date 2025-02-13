<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PatientWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $user = Auth::user();
        $patient = Patient::where('user_id', '=', $user->id)->first(); // Fetch the first matching patient record

        // Check if the user has an associated patient record
        if (!$patient) {
            return [
                Stat::make('Appointments Today', 0)
                    ->icon('heroicon-o-calendar')
                    ->color('success')
                    ->extraAttributes([
                        'class' => 'ring-2 ring-info-50',
                    ]),

                Stat::make('Appointments This Month', 0)
                    ->icon('heroicon-o-calendar')
                    ->color('primary')
                    ->extraAttributes([
                        'class' => 'ring-2 ring-info-50',
                    ]),

                Stat::make('Appointments This Year', 0)
                    ->icon('heroicon-o-calendar')
                    ->color('secondary')
                    ->extraAttributes([
                        'class' => 'ring-2 ring-info-50',
                    ]),

                Stat::make('Most Preferred Doctor', 'N/A')
                    ->icon('heroicon-o-user')
                    ->color('warning')
                    ->extraAttributes([
                        'class' => 'ring-2 ring-info-50',
                    ]),
            ];
        }

        // Get appointments for the patient
        $today = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear = Carbon::now()->year;

        // Appointments today
        $appointmentsToday = Appointment::where('patient_id', $patient->id)
            ->whereDate('date', $today) // Use whereDate instead of where for comparing the date
            ->count();

        // Appointments this month
        $appointmentsThisMonth = Appointment::where('patient_id', $patient->id)
            ->whereMonth('date', $thisMonth)
            ->count();

        // Appointments this year
        $appointmentsThisYear = Appointment::where('patient_id', $patient->id)
            ->whereYear('date', $thisYear)
            ->count();

        // Most preferred doctor (doctor with most appointments)
        $mostPreferredDoctor = Appointment::where('patient_id', $patient->id)
            ->select('doctor_id', DB::raw('count(*) as count'))
            ->groupBy('doctor_id')
            ->orderByDesc('count')
            ->first();

        $mostPreferredDoctorName = null;
        if ($mostPreferredDoctor) {
            $mostPreferredDoctorName = Doctor::find($mostPreferredDoctor->doctor_id)?->user->name ?? 'N/A';
        }

        return [
            Stat::make('Appointments Today', $appointmentsToday)
                ->icon('heroicon-o-calendar')
                ->color('success'),

            Stat::make('Appointments This Month', $appointmentsThisMonth)
                ->icon('heroicon-o-calendar')
                ->color('primary'),

            Stat::make('Appointments This Year', $appointmentsThisYear)
                ->icon('heroicon-o-calendar')
                ->color('secondary'),

            Stat::make('Most Preferred Doctor', $mostPreferredDoctorName)
                ->icon('heroicon-o-user')
                ->color('warning'),
        ];
    }

    public static function canView(): bool
    {
        return Auth::user()->role_id == 3;
    }
}
