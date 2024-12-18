<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Filament\Resources\PatientResource\RelationManagers\AppointmentsRelationManager;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Password;
use Filament\Forms\Components\Radio;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 4;


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Fieldset::make('Patient Information')
                    ->relationship('user') // Correctly linking to the `user` relationship
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
                            ->required(fn($livewire) => $livewire instanceof Pages\CreatePatient), // Required only on create
                        Hidden::make('role_id')
                            ->default('3')
                    ]),
                Fieldset::make('Additional Details')
                    ->schema([
                        TextInput::make('age')
                            ->label('Age')
                            ->numeric()
                            ->required(),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->required(),
                        TextInput::make('address')
                            ->label('Address')
                            ->required(),
                        Radio::make('gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'others' => 'Others',
                            ])
                            ->label('Gender')
                            ->required()
                            ->reactive()
                            ->dehydrateStateUsing(fn($state) => $state),
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
                TextColumn::make('address')
                    ->label('Address'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(), // Ensure EditAction is added
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AppointmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'), // Ensure this route is correct
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $data['user_id'] = $user->id;

        unset($data['name'], $data['email'], $data['password']);

        return $data;
    }

    public static function mutateFormDataBeforeSave(array $data, Model $record): array
    {
        $user = $record->user;

        if (!$user) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);
            $data['user_id'] = $user->id;
        } else {
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


    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user->role_id == 1;
    }
}
