<?php

namespace App\Filament\Resources\SpecialityResource\Pages;

use App\Filament\Resources\SpecialityResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSpeciality extends ViewRecord
{
    protected static string $resource = SpecialityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    public function doctors()
    {
        $resource = SpecialityResource::class;
        $speciality = $this->record;
        $doctors = $speciality->doctors()->get();
        return view('filament::pages.speciality.doctors', compact('doctors', 'speciality'));
    }
}