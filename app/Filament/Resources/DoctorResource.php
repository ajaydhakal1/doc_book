<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Filament\Resources\DoctorResource\Pages\ViewDoctor;
use App\Filament\Resources\DoctorResource\RelationManagers\AppointmentsRelationManager;
use App\Filament\Resources\DoctorResource\RelationManagers\SchedulesRelationManager;
use App\Models\Doctor;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Password;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Spatie\Permission\Contracts\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 3;


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Fieldset::make('Doctor Information')
                    ->relationship('user')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Full Name'),
                        TextInput::make('email')
                            ->required()
                            ->email()
                            ->label('Email'),
                        TextInput::make('password')
                            ->password()
                            ->label('Password')
                            ->dehydrateStateUsing(fn($state) => !empty ($state) ? Hash::make($state) : null)
                            ->required(fn($livewire) => $livewire instanceof Pages\CreateDoctor), // Required only on create
                        Hidden::make('role_id')
                            ->default('2')
                    ]),

                Fieldset::make('Additional Details')
                    ->schema([
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
                    ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('user.email')
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
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SchedulesRelationManager::class,
            AppointmentsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoctors::route('/'),
            'view' => ViewDoctor::route('/{record}/view'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure user is created only if relationship condition is met
        if (filled($data['name'])) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            //Assign Role
            $user->assignRole('Doctor');
            // Assign the new user's ID to the doctor data
            $data['user_id'] = $user->id;
        }

        // Remove user-specific fields to avoid duplication
        unset($data['name'], $data['email'], $data['password']);

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data, Model $record): array
    {
        $user = $record->user;

        if (!$user && filled($data['name'])) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $data['user_id'] = $user->id;
        } elseif ($user) {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            if (!empty($data['password'])) {
                $user->update([
                    'password' => Hash::make($data['password']),
                ]);
            }
        }

        unset($data['name'], $data['email'], $data['password']);

        return $data;
    }
}
