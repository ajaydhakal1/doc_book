<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class AdminWidgets extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('All registered users')
                ->descriptionIcon('heroicon-m-users')
                ->chart([7, 4, 6, 8, 5, 9, 7])
                ->color('info')
                ->extraAttributes([
                    'class' => 'ring-2 ring-info-50',
                ]),

            Stat::make('Total Doctors', Doctor::count())
                ->description('Registered medical professionals')
                ->descriptionIcon('heroicon-m-user-group')
                ->chart([5, 3, 7, 6, 8, 4, 6])
                ->color('success')
                ->extraAttributes([
                    'class' => 'ring-2 ring-success-50',
                ]),

            Stat::make('Total Patients', Patient::count())
                ->description('Registered patients')
                ->descriptionIcon('heroicon-m-user')
                ->chart([8, 6, 4, 7, 5, 8, 9])
                ->color('warning')
                ->extraAttributes([
                    'class' => 'ring-2 ring-warning-50',
                ]),

            Stat::make('Today\'s New Users', User::whereDate('created_at', today())->count())
                ->description('Users registered today')
                ->descriptionIcon('heroicon-m-user-plus')
                ->chart([3, 5, 4, 6, 3, 5, 4])
                ->color('primary')
                ->extraAttributes([
                    'class' => 'ring-2 ring-primary-50',
                ]),

            Stat::make('Today\'s Appointments', Appointment::whereDate('created_at', today())->count())
                ->description('Appointments scheduled for today')
                ->descriptionIcon('heroicon-m-calendar')
                ->chart([4, 6, 5, 7, 4, 6, 5])
                ->color('danger')
                ->extraAttributes([
                    'class' => 'ring-2 ring-danger-50',
                ]),

            Stat::make('Monthly Appointments', Appointment::whereMonth('created_at', now()->month)->count())
                ->description('Total appointments this month')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->chart([6, 8, 7, 9, 6, 8, 7])
                ->color('gray')
                ->extraAttributes([
                    'class' => 'ring-2 ring-gray-100',
                ]),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }

    public static function canView(): bool
    {
        return Auth::user()->role_id == 1;
    }
}