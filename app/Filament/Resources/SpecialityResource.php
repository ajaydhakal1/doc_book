<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SpecialityResource\Pages;
use App\Filament\Resources\SpecialityResource\RelationManagers;
use App\Filament\Resources\SpecialityResource\RelationManagers\DoctorsRelationManager;
use App\Models\Speciality;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;

class SpecialityResource extends Resource
{
    protected static ?string $model = Speciality::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'Doctors Management';
    protected static ?int $navigationSort = 5;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationLabel = 'Specialities';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Speciality Information')
                    ->description('Add or edit medical speciality details')
                    ->icon('heroicon-o-academic-cap')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Speciality Name')
                            ->placeholder('e.g., Cardiology')
                            ->prefixIcon('heroicon-m-document-text')
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('description')
                            ->label('Description')
                            ->placeholder('Brief description of the speciality')
                            ->prefixIcon('heroicon-m-information-circle')
                            ->columnSpanFull(),
                    ])
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Speciality')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-m-academic-cap'),
                TextColumn::make('doctors_count')
                    ->label('Doctors')
                    ->counts('doctors')
                    ->sortable()
                    ->icon('heroicon-m-users'),
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-calendar'),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->icon('heroicon-m-clock'),
            ])
            ->defaultSort('name', 'asc')
            ->filters([
                // Removed TrashedFilter
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-m-eye'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-m-trash'),
            ])
            ->emptyStateIcon('heroicon-o-academic-cap')
            ->emptyStateHeading('No Specialities yet')
            ->emptyStateDescription('Create your first medical speciality to get started.')
            ->emptyStateActions([
                Tables\Actions\Action::make('create')
                    ->label('Create Speciality')
                    ->icon('heroicon-m-plus')
                    ->url(route('filament.admin.resources.specialities.create'))
                    ->button(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            DoctorsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSpecialities::route('/'),
            'create' => Pages\CreateSpeciality::route('/create'),
            'view' => Pages\ViewSpeciality::route('/{record}/view'),
            'edit' => Pages\EditSpeciality::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }
}