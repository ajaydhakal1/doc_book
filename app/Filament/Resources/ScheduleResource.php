<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers;
use App\Models\Doctor;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationGroup = 'Doctors Management';
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?int $navigationSort = 7;
    protected static ?string $modelLabel = 'Doctor Schedule';
    protected static ?string $pluralModelLabel = 'Doctor Schedules';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Schedule Details')
                    ->schema([
                        Select::make('doctor_id')
                            ->label('Doctor')
                            ->options(
                                Doctor::with('user')->get()->pluck('user.name', 'id')
                            )
                            ->native(false)
                            ->searchable()
                            ->required()
                            ->prefixIcon('heroicon-o-user-circle'),

                        Forms\Components\DatePicker::make('date')
                            ->required()
                            ->prefixIcon('heroicon-o-calendar'),

                        Forms\Components\TimePicker::make('start_time')
                            ->seconds(false)
                            ->required()
                            ->prefixIcon('heroicon-o-play'),

                        Forms\Components\TimePicker::make('end_time')
                            ->seconds(false)
                            ->required()
                            ->prefixIcon('heroicon-o-stop'),

                        Select::make('status')
                            ->options([
                                'booked' => 'Booked',
                                'unavailable' => 'Unavailable'
                            ])
                            ->required()
                            ->native(false)
                            ->prefixIcon('heroicon-o-check-circle')
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doctor.user.name')
                    ->prefix('Dr.')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user-circle'),

                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->icon('heroicon-o-calendar'),

                Tables\Columns\TextColumn::make('start_time')
                    ->time(format: 'h:i A'),

                Tables\Columns\TextColumn::make('end_time')
                    ->time(format: 'h:i A'),

                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'booked' => 'warning',
                        'unavailable' => 'danger',
                        default => 'gray'
                    }),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-clock'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-o-arrow-path'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->color('warning'),
                Tables\Actions\DeleteAction::make()
                    ->color('danger'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'view' => Pages\ViewSchedule::route('/{record}'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user->role_id == 1 || $user->role_id == 2;
    }
}