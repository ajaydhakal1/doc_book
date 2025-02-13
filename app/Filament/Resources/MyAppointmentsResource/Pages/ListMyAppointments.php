<?php

namespace App\Filament\Resources\MyAppointmentsResource\Pages;

use App\Filament\Resources\MyAppointmentsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMyAppointments extends ListRecords
{
    protected static string $resource = MyAppointmentsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
