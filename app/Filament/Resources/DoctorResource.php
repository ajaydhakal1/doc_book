<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Models\Doctor;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Password;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Full Name'),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->label('Email'),
                TextInput::make('password')
                    ->required()
                    ->password()
                    ->label('Password'),
                TextInput::make('phone')
                    ->required()
                    ->label('Phone'),
                TextInput::make('hourly_rate')
                    ->required()
                    ->numeric()
                    ->label('Hourly Rate'),
                Select::make('speciality_id')
                    ->label('Speciality')
                    ->relationship('speciality', 'name')
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('Phone'),
                TextColumn::make('hourly_rate')
                    ->label('Hourly Rate')
                    ->sortable(),
                TextColumn::make('speciality.name')
                    ->label('Speciality')
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Create a new user first
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Assign the new user's ID to the doctor's user_id
        $data['user_id'] = $user->id;

        // Remove user-specific fields from the doctor data
        unset($data['name'], $data['email'], $data['password']);

        return $data;
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }
}
