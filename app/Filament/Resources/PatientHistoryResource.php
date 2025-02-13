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
use Illuminate\Support\Facades\Auth;

class PatientHistoryResource extends Resource
{
    protected static ?string $model = PatientHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?string $modelLabel = 'Patient History';

    protected static ?string $navigationLabel = 'Medical History';

    protected static ?int $navigationSort = 10;

    protected static ?string $activeNavigationIconColor = 'warning';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Patient History Record')
                    ->description('Manage patient history information')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('patient_id')
                                    ->required()
                                    ->numeric()
                                    ->prefix('#')
                                    ->prefixIcon('heroicon-m-user')
                                    ->label('Patient ID')
                                    ->helperText('Enter the patient identifier')
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('doctor_id')
                                    ->required()
                                    ->numeric()
                                    ->prefix('#')
                                    ->prefixIcon('heroicon-m-user-circle')
                                    ->label('Doctor ID')
                                    ->helperText('Enter the doctor identifier')
                                    ->columnSpan(1),
                            ]),

                        Forms\Components\Section::make('Related Information')
                            ->icon('heroicon-m-link')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('appointment_id')
                                            ->required()
                                            ->numeric()
                                            ->prefix('#')
                                            ->prefixIcon('heroicon-m-calendar')
                                            ->label('Appointment ID')
                                            ->helperText('Enter the appointment reference'),

                                        Forms\Components\TextInput::make('review_id')
                                            ->numeric()
                                            ->prefix('#')
                                            ->prefixIcon('heroicon-m-chat-bubble-left')
                                            ->label('Review ID')
                                            ->helperText('Optional review reference'),

                                        Forms\Components\TextInput::make('payment_id')
                                            ->required()
                                            ->numeric()
                                            ->prefix('#')
                                            ->prefixIcon('heroicon-m-currency-dollar')
                                            ->label('Payment ID')
                                            ->helperText('Enter the payment reference'),
                                    ]),
                            ]),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.user.name')
                    ->label('Patient Name')
                    ->icon('heroicon-m-user')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Name copied')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('doctor.user.name')
                    ->label('Doctor Name')
                    ->icon('heroicon-m-user-circle')
                    ->searchable()
                    ->sortable()
                    ->copyable(),

                Tables\Columns\TextColumn::make('appointment_id')
                    ->label('Appointment ID')
                    ->icon('heroicon-m-calendar')
                    ->numeric()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('review.comment')
                    ->label('Remarks')
                    ->icon('heroicon-m-chat-bubble-left')
                    ->wrap()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('payment_id')
                    ->label('Payment ID')
                    ->icon('heroicon-m-currency-dollar')
                    ->numeric()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created Date')
                    ->icon('heroicon-m-calendar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->icon('heroicon-m-clock')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Preserved empty filters array
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-m-eye'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square'),
            ])
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('No Patient History Records')
            ->emptyStateDescription('Patient history records will appear here once created.')
            ->defaultSort('created_at', 'desc')
            ->striped()
            ->searchable()
            ->poll('60s');
    }

    public static function getRelations(): array
    {
        return [
            // Preserved empty relations array
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

    public static function canViewAny(): bool
    {
        $user = Auth::user();
        return $user->role_id == 1;
    }
}