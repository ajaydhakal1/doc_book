<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;
    protected function getRedirectUrl(): string
    {
        return AppointmentResource::getUrl();
    }

    public function mutateFormDataBeforeCreate(array $data): array
    {
        if (Auth::user()->role_id === 3) {
            $data['patient_id'] = Auth::user()->patient->id;
        }

        return $data;
    }
}
