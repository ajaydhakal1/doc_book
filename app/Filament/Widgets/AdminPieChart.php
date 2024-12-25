<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class AdminPieChart extends ChartWidget
{
    protected static ?string $heading = 'Pie Chart - Appointment Status Breakdown';
    protected static ?int $sort = 2;
    protected static ?string $maxHeight = '254px'; // Set to your desired height

    protected static ?array $options = [
        'maintainAspectRatio' => false,
    ];

    protected function getType(): string
    {
        return 'doughnut'; // Pie chart type
    }

    protected function getData(): array
    {
        $appointmentStatuses = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        return [
            'labels' => $appointmentStatuses->keys()->toArray(),
            'datasets' => [
                [
                    'label' => 'Appointments by Status',
                    'data' => $appointmentStatuses->values()->toArray(),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
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
