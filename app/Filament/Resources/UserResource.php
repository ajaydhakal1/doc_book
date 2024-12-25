<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    protected static ?int $navigationSort = 2;

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Full Name'),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Email Address'),
                TextInput::make('password')
                    ->password()
                    ->label('Password')
                    ->hiddenOn('edit')
                    ->required(fn($livewire) => $livewire instanceof Pages\CreateUser),
                Select::make('role_id')
                    ->label('Role')
                    ->options([
                        '1' => 'Admin',
                        '2' => 'Doctor',
                        '3' => 'Patient',
                    ])
                    ->reactive()
                    ->required(),
                TextInput::make('age')
                    ->numeric()
                    ->required()
                    ->visible(fn($get) => $get('role_id') == 3),
                TextInput::make('address')
                    ->required()
                    ->visible(fn($get) => $get('role_id') == 3),
                TextInput::make('phone')
                    ->required()
                    ->visible(fn($get) => $get('role_id') == 3 || $get('role_id') == 2),
                TextInput::make('hourly_rate')
                    ->numeric()
                    ->required()
                    ->visible(fn($get) => $get('role_id') == 2),
                Select::make('speciality_id')
                    ->label('Speciality')
                    ->relationship('doctor.speciality', 'name')
                    ->visible(fn($get) => $get('role_id') == 2)
                    ->required(),
                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'others' => 'Others',
                    ])
                    ->visible(fn($get) => $get('role_id') == 3),
            ]);
    }



    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name')->sortable()->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('created_at')->label('Created At')->dateTime()->sortable(),
                TextColumn::make('role_id')
                    ->label('Role')
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => 'Admin',
                        2 => 'Doctor',
                        3 => 'Patient',
                        default => 'Unknown',
                    }),
            ])->actions([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                TextEntry::make('name')->label('Name'),
                TextEntry::make('email')->label('Email'),
                TextEntry::make('doctor.phone')
                    ->label('Phone')
                    ->getStateUsing(fn($record) => $record->doctor?->phone ?? 'N/A')
                    ->visible(fn($record) => $record->role_id == 2),
                TextEntry::make('patient.phone')
                    ->label('Phone')
                    ->getStateUsing(fn($record) => $record->patient?->phone ?? 'N/A')
                    ->visible(fn($record) => $record->role_id == 3),
                TextEntry::make('patient.age')
                    ->label('Age')
                    ->getStateUsing(fn($record) => $record->patient?->age ?? 'N/A')
                    ->visible(fn($record) => $record->role_id == 3),
                TextEntry::make('patient.address')
                    ->label('Address')
                    ->getStateUsing(fn($record) => $record->patient?->address ?? 'N/A')
                    ->visible(fn($record) => $record->role_id == 3),
                TextEntry::make('patient.gender')
                    ->label('Gender')
                    ->getStateUsing(fn($record) => $record->patient?->gender ?? 'N/A')
                    ->visible(fn($record) => $record->role_id == 3),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'view' => Pages\ViewUser::route('/{record}/view'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()->role_id == 1;
    }
}
