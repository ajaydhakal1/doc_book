<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl();
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $user = User::find($data['id']);

        if($data['role_id'] == 2){
            $data['phone'] = $user->doctor->phone;
            $data['address'] = $user->doctor->address;
            $data['hourly_rate'] = $user->doctor->hourly_rate;
            $data['speciality_id'] = $user->doctor->speciality_id;
        }
        elseif($data['role_id'] == 3){
            $data['phone'] = $user->patient->phone;
            $data['age'] = $user->patient->age;
            $data['address'] = $user->patient->address;
            $data['gender'] = $user->patient->gender;
        }

        return $data;
    }
}
