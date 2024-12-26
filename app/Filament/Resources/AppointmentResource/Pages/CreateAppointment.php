<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\Schedule;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function getRedirectUrl(): string
    {
        return AppointmentResource::getUrl();
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Assign patient ID for logged-in patients
        if (Auth::user()->role_id === 3) {
            $data['patient_id'] = Auth::user()->patient->id;
        }

        // Validate overlapping schedules
        $overlap = Schedule::where('doctor_id', $data['doctor_id'])
            ->where('date', $data['date'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                    ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('start_time', '<=', $data['start_time'])
                            ->where('end_time', '>=', $data['end_time']);
                    });
            })
            ->exists();

        if ($overlap) {
            Notification::make()
                ->title('Doctor Not Available')
                ->body('The selected doctor is not available at the selected time.')
                ->icon('heroicon-o-information-circle')
                ->danger()
                ->send();

            $this->halt(); // Prevents further processing
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $data = $this->record->toArray();

        // Create the schedule after appointment creation
        Schedule::create([
            'doctor_id' => $data['doctor_id'],
            'date' => $data['date'],
            'start_time' => $data['start_time'],
            'end_time' => $data['end_time'],
            'status' => 'booked',
        ]);

        Notification::make()
            ->title('Appointment Created')
            ->body('The appointment has been successfully created.')
            ->icon('heroicon-o-check-circle')
            ->success()
            ->send();
    }
}
