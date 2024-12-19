<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\ViewRecord;
use App\Models\User; // Import the User model

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    // Override the initialize() method to eager load relationships
    protected function initialize(): void
    {
        parent::initialize();

        // Eager load doctor and speciality relationships
        $this->record = User::with(['doctor.speciality'])->findOrFail($this->record->id);
    }
}