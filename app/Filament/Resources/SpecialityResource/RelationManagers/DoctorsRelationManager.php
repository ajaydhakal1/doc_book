<?php

namespace App\Filament\Resources\SpecialityResource\RelationManagers;

use App\Filament\Resources\DoctorResource\Pages\CreateDoctor;
use Filament\Forms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class DoctorsRelationManager extends RelationManager
{
    protected static string $relationship = 'doctors';

    public function form(Form $form): Form
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
                            ->required(fn($livewire) => $livewire instanceof CreateDoctor), // Required only on create
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

    public function table(Table $table): Table
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
}
