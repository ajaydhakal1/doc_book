<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\Section as InfolistSection;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Support\Enums\FontWeight;
use Illuminate\Support\Facades\Auth;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 2;
    protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Section::make('Basic Information')
                    ->description('Manage user personal information')
                    ->icon('heroicon-o-user-circle')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Full Name')
                            ->prefixIcon('heroicon-m-user')
                            ->placeholder('John Doe'),
                        TextInput::make('email')
                            ->email()
                            ->required()
                            ->label('Email Address')
                            ->prefixIcon('heroicon-m-envelope')
                            ->placeholder('john@example.com'),
                        TextInput::make('password')
                            ->password()
                            ->label('Password')
                            ->prefixIcon('heroicon-m-key')
                            ->hiddenOn('edit')
                            ->required(fn($livewire) => $livewire instanceof Pages\CreateUser),
                        Select::make('role_id')
                            ->label('Role')
                            ->options([
                                '1' => 'Admin',
                                '2' => 'Doctor',
                                '3' => 'Patient',
                            ])
                            ->prefixIcon('heroicon-m-user-group')
                            ->native(false)
                            ->reactive()
                            ->required(),
                    ])->columns(2),

                Section::make('Additional Details')
                    ->description('Role-specific information')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        TextInput::make('age')
                            ->numeric()
                            ->required()
                            ->prefixIcon('heroicon-m-calendar')
                            ->visible(fn($get) => $get('role_id') == 3),
                        TextInput::make('address')
                            ->required()
                            ->prefixIcon('heroicon-m-home')
                            ->visible(fn($get) => $get('role_id') == 3),
                        TextInput::make('phone')
                            ->required()
                            ->prefixIcon('heroicon-m-phone')
                            ->visible(fn($get) => $get('role_id') == 3 || $get('role_id') == 2),
                        TextInput::make('hourly_rate')
                            ->numeric()
                            ->required()
                            ->prefixIcon('heroicon-m-currency-dollar')
                            ->visible(fn($get) => $get('role_id') == 2),
                        Select::make('speciality_id')
                            ->label('Speciality')
                            ->relationship('doctor.speciality', 'name')
                            ->prefixIcon('heroicon-m-academic-cap')
                            ->visible(fn($get) => $get('role_id') == 2)
                            ->native(false)
                            ->required(),
                        Select::make('gender')
                            ->label('Gender')
                            ->options([
                                'male' => 'Male',
                                'female' => 'Female',
                                'others' => 'Others',
                            ])
                            ->prefixIcon('heroicon-m-user')
                            ->native(false)
                            ->visible(fn($get) => $get('role_id') == 3),
                    ])->columns(2),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-m-user'),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->icon('heroicon-m-envelope'),
                Tables\Columns\TextColumn::make('role_id')
                    ->label('Role')
                    ->badge()
                    ->color(fn($state): string => match ($state) {
                        1 => 'danger',
                        2 => 'warning',
                        3 => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn($state) => match ($state) {
                        1 => 'Admin',
                        2 => 'Doctor',
                        3 => 'Patient',
                        default => 'Unknown',
                    })
                    ->icon('heroicon-m-user-group'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role_id')
                    ->label('Role')
                    ->options([
                        1 => 'Admin',
                        2 => 'Doctor',
                        3 => 'Patient',
                    ])
                // ->icon('heroicon-m-funnel'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-m-eye'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-m-trash'),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                InfolistSection::make('User Information')
                    ->icon('heroicon-o-user')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Name')
                            ->icon('heroicon-m-user'),
                        TextEntry::make('email')
                            ->label('Email')
                            ->icon('heroicon-m-envelope'),
                    ])->columns(2),

                InfolistSection::make('Role-Specific Information')
                    ->icon('heroicon-o-identification')
                    ->schema([
                        TextEntry::make('doctor.phone')
                            ->label('Phone')
                            ->icon('heroicon-m-phone')
                            ->getStateUsing(fn($record) => $record->doctor?->phone ?? 'N/A')
                            ->visible(fn($record) => $record->role_id == 2),
                        TextEntry::make('patient.phone')
                            ->label('Phone')
                            ->icon('heroicon-m-phone')
                            ->getStateUsing(fn($record) => $record->patient?->phone ?? 'N/A')
                            ->visible(fn($record) => $record->role_id == 3),
                        TextEntry::make('patient.age')
                            ->label('Age')
                            ->icon('heroicon-m-calendar')
                            ->getStateUsing(fn($record) => $record->patient?->age ?? 'N/A')
                            ->visible(fn($record) => $record->role_id == 3),
                        TextEntry::make('patient.address')
                            ->label('Address')
                            ->icon('heroicon-m-home')
                            ->getStateUsing(fn($record) => $record->patient?->address ?? 'N/A')
                            ->visible(fn($record) => $record->role_id == 3),
                        TextEntry::make('patient.gender')
                            ->label('Gender')
                            ->icon('heroicon-m-user')
                            ->getStateUsing(fn($record) => $record->patient?->gender ?? 'N/A')
                            ->visible(fn($record) => $record->role_id == 3),
                    ])->columns(2),
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