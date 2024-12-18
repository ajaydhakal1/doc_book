<?php

namespace App\Filament\Resources\PatientHistoryResource\Pages;

use App\Filament\Resources\PatientHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPatientHistory extends ViewRecord
{
    protected static string $resource = PatientHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
