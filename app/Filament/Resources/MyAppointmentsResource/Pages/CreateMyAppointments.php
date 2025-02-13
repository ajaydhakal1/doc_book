<?php

namespace App\Filament\Resources\MyAppointmentsResource\Pages;

use App\Filament\Resources\MyAppointmentsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMyAppointments extends CreateRecord
{
    protected static string $resource = MyAppointmentsResource::class;
}
