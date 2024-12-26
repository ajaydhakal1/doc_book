<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class DoctorWidget extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $doctorId = Auth::user()?->doctor?->id;

        if (!$doctorId) {
            return [];
        }

        $today = now()->startOfDay();
        $weekStart = now()->startOfWeek();
        $monthStart = now()->startOfMonth();
        $yearStart = now()->startOfYear();

        return [
            Stat::make(
                'Pending Appointments',
                Appointment::query()
                    ->where('doctor_id', $doctorId)
                    ->where('status', 'pending')
                    ->count()
            )
                ->description('Appointments waiting for approval')
                ->icon('heroicon-m-clock')
                ->color('warning')
                ->chart([]),

            Stat::make(
                'Completed Appointments',
                Appointment::query()
                    ->where('doctor_id', $doctorId)
                    ->where('status', 'completed')
                    ->count()
            )
                ->description('Appointments marked as completed')
                ->icon('heroicon-m-check-circle')
                ->color('success')
                ->chart([]),

            Stat::make(
                'Today\'s Appointments',
                Appointment::query()
                    ->where('doctor_id', $doctorId)
                    ->whereDate('date', $today)
                    ->count()
            )
                ->description('Appointments scheduled for today')
                ->icon('heroicon-m-calendar')
                ->color('primary')
                ->chart([]),

            Stat::make(
                'This Week\'s Appointments',
                Appointment::query()
                    ->where('doctor_id', $doctorId)
                    ->whereBetween('date', [$weekStart, now()->endOfWeek()])
                    ->count()
            )
                ->description('Appointments scheduled this week')
                ->icon('heroicon-m-calendar-days')
                ->color('info')
                ->chart([]),

            Stat::make(
                'This Month\'s Appointments',
                Appointment::query()
                    ->where('doctor_id', $doctorId)
                    ->whereBetween('date', [$monthStart, now()->endOfMonth()])
                    ->count()
            )
                ->description('Appointments scheduled this month')
                ->icon('heroicon-m-calendar')
                ->color('gray')
                ->chart([]),

            Stat::make(
                'This Year\'s Appointments',
                Appointment::query()
                    ->where('doctor_id', $doctorId)
                    ->whereBetween('date', [$yearStart, now()->endOfYear()])
                    ->count()
            )
                ->description('Appointments scheduled this year')
                ->icon('heroicon-m-calendar')
                ->color('success')
                ->chart([]),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }

    public static function canView(): bool
    {
        return Auth::user()->role_id === 2;
    }
}