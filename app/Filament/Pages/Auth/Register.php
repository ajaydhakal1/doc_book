<?php

namespace App\Filament\Pages\Auth;

use App\Models\Patient;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as BaseRegister;
use Spatie\Permission\Models\Role;

class Register extends BaseRegister
{

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        $this->getAddressFormComponent(),
                        $this->getAgeFormComponent(),
                        $this->getPhoneFormComponent(),
                        $this->getGenderFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getAddressFormComponent(): Component
    {
        return TextInput::make('address')
            ->required();
    }

    protected function getGenderFormComponent(): Component
    {
        return Select::make('gender')
            ->options([
                'male' => 'Male',
                'female' => 'Female',
                'others' => 'Others'
            ])
            ->label('Gender')
            ->required();
    }

    protected function getAgeFormComponent(): Component
    {
        return TextInput::make('age')
            ->numeric()
            ->required();
    }


    protected function getPhoneFormComponent(): Component
    {
        return TextInput::make('phone')
            ->required();
    }

    // protected function getRoleComponent():Component{
    //     return Hidden
    // }



    protected function afterRegister()
    {
        $user = $this->form->model;
        $data = $this->data;
    
        // Retrieve the role ID for the Patient role
        $role = Role::where('name', 'Patient')->first();
    
        if ($role) {
            // Update the role_id of the user
            $user->update(['role_id' => $role->id]);
        }
    
        // Create the Patient record
        $patient = Patient::create([
            'user_id' => $user->id,
            'phone' => $data['phone'],
            'age' => $data['age'], 
            'address' => $data['address'],
            'gender' => $data['gender'],
        ]);
    }
    
}