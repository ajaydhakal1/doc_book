<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AdminCharts extends ChartWidget
{
    protected static ?string $heading = 'Bar Chart - User Registrations and Appointments';
    protected static ?int $sort = 1;
    // protected int|string|array $columnSpan = '1'; // Adjust column span as needed

    protected function getType(): string
    {
        return 'bar'; // First chart type
    }

    protected function getData(): array
    {
        $userRegistrations = User::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->take(7)
            ->pluck('count', 'date');

        $appointments = Appointment::selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->take(7)
            ->pluck('count', 'date');

        return [
            'labels' => $userRegistrations->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'User Registrations',
                    'data' => $userRegistrations->values()->toArray(),
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ],
                [
                    'label' => 'Appointments',
                    'data' => $appointments->values()->toArray(),
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgba(153, 102, 255, 1)',
                    'borderWidth' => 1,
                ],
            ],
        ];
    }

    public static function canView(): bool
    {
        return Auth::user()->role_id == 1;
    }

}
