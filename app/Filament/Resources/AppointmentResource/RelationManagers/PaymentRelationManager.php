<?php

namespace App\Filament\Resources\AppointmentResource\RelationManagers;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class PaymentRelationManager extends RelationManager
{
    protected static string $relationship = 'payment';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('patient.user.name')
                    ->label('Patient')
                    ->disabled()
                    ->extraInputAttributes(['readonly' => true]),
                Forms\Components\TextInput::make('appointment_id')
                    ->required()
                    ->disabled()
                    ->readOnly(true)
                    ->numeric(),
                Forms\Components\TextInput::make('amount')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('payment_type')
                    ->required(),
                Forms\Components\TextInput::make('payment_status')
                    ->required(),
                Forms\Components\TextInput::make('transaction_id'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_type'),
                Tables\Columns\TextColumn::make('payment_status'),
                Tables\Columns\TextColumn::make('transaction_id'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])->modifyQueryUsing(function (Builder $query) {
                // Get the currently authenticated user
                $user = User::find(Auth::user()->id);
                // If the user is an admin, they can see all appointments
                if ($user->hasRole('Admin')) {
                    return $query;
                }

                // If the user is a doctor, only their appointments are shown
                if ($user->hasRole('Doctor')) {
                    return $query->where('doctor_id', $user->doctor->id);
                }

                // If the user is a patient, only their appointments are shown
                if ($user->hasRole('Patient')) {

                    if ($user->patient) {
                        return $query->where('patient_id', $user->patient->id);
                    } else {
                        return $query->whereRaw('1 = 0'); // If no patient relationship, show no appointments
                    }
                }

                // Default to no appointments
                return $query->whereRaw('1 = 0');
            })
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                DeleteAction::make()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}