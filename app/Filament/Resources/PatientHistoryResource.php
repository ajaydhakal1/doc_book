<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientHistoryResource\Pages;
use App\Filament\Resources\PatientHistoryResource\RelationManagers;
use App\Models\PatientHistory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PatientHistoryResource extends Resource
{
    protected static ?string $model = PatientHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?int $navigationSort = 10;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('patient_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('doctor_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('appointment_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('review_id')
                    ->numeric(),
                Forms\Components\TextInput::make('payment_id')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.user.name'),
                Tables\Columns\TextColumn::make('doctor.user.name'),
                Tables\Columns\TextColumn::make('appointment_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('review.comment'),
                Tables\Columns\TextColumn::make('payment_id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListPatientHistories::route('/'),
            'create' => Pages\CreatePatientHistory::route('/create'),
            'view' => Pages\ViewPatientHistory::route('/{record}'),
            'edit' => Pages\EditPatientHistory::route('/{record}/edit'),
        ];
    }
}
