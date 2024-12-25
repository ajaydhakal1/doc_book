<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function getRedirectUrl(): string
    {
        return UserResource::getUrl();
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if ($data['role_id'] == 2) {
            $user->role_id = 2;
            $user->save();
            Doctor::create([
                'user_id' => $user->id,
                'phone' => $data['phone'],
                'speciality_id' => $data['speciality_id'],
                'hourly_rate' => $data['hourly_rate'],
            ]);
        } elseif ($data['role_id'] == 3) {
            $user->role_id = 3;
            $user->save();
            Patient::create([
                'user_id' => $user->id,
                'phone' => $data['phone'],
                'address' => $data['address'],
                'age' => $data['age'],
                'gender' => $data['gender'],
            ]);
        }

        return $user;
    }

}
