<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentResource\Pages;
use App\Models\Payment;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\FontWeight;

class PaymentResource extends Resource
{
    protected static ?string $model = Payment::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'Payments';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 8;
    protected static ?string $recordTitleAttribute = 'transaction_id';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('payment_status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Payment Details')
                    ->description('Manage payment information')
                    ->icon('heroicon-o-credit-card')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('patient_id')
                            ->disabledOn('edit')
                            ->required()
                            ->numeric()
                            ->prefixIcon('heroicon-m-user')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('appointment_id')
                            ->disabledOn('edit')
                            ->required()
                            ->numeric()
                            ->prefixIcon('heroicon-m-calendar')
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('amount')
                            ->disabledOn('edit')
                            ->required()
                            ->prefix('$')
                            ->numeric()
                            ->prefixIcon('heroicon-m-currency-dollar')
                            ->columnSpan(1),
                        Forms\Components\Select::make('payment_type')
                            ->options([
                                'cash' => 'Cash',
                                'online' => 'Online',
                                '-' => 'N/A',
                            ])
                            ->required()
                            ->prefixIcon('heroicon-m-credit-card')
                            ->native(false)
                            ->columnSpan(1),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                'pending' => 'Pending',
                                'completed' => 'Paid'
                            ])
                            ->required()
                            ->prefixIcon('heroicon-m-check-circle')
                            ->native(false)
                            ->columnSpan(1),
                        Forms\Components\TextInput::make('transaction_id')
                            ->prefixIcon('heroicon-m-identification')
                            ->columnSpan(1),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.user.name')
                    ->label('Patient')
                    ->icon('heroicon-m-user')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('appointment_id')
                    ->icon('heroicon-m-calendar')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->icon('heroicon-m-currency-dollar')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'cash' => 'success',
                        '-' => 'danger',
                        'online' => 'info',
                    })
                    ->icon(function (Payment $record) {
                        if ($record->payment_type != '-') {
                            return 'heroicon-m-credit-card';
                        }
                    })
                    ->searchable(),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                    })
                    ->icon('heroicon-m-check-circle')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_id')
                    ->icon('heroicon-m-identification')
                    ->searchable()
                    ->tooltip('Click To Copy')
                    ->copyable()
                    ->copyMessage('Transaction ID copied')
                    ->copyMessageDuration(1500),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->modifyQueryUsing(function (Builder $query) {
                $user = User::find(Auth::user()->id);

                if ($user->role_id == 1) {
                    return $query;
                }

                if ($user->role_id == 2) {
                    return $query->where('doctor_id', $user->doctor->id);
                }

                if ($user->role_id == 3) {
                    if ($user->patient) {
                        return $query->where('patient_id', $user->patient->id);
                    } else {
                        return $query->whereRaw('1 = 0');
                    }
                }

                return $query->whereRaw('1 = 0');
            })
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Paid',
                    ]),
                // ->icon('heroicon-m-funnel'),
                Tables\Filters\SelectFilter::make('payment_type')
                    ->options([
                        'cash' => 'Cash',
                        '-' => 'N/A',
                        'online' => 'Online',
                    ]),
                // ->icon('heroicon-m-funnel'),
            ])
            ->actions([
                // Tables\Actions\Action::make('pay')
                //     ->label('Pay')
                //     ->url(function ($record) {
                //         if ($record && $record->payment_status === 'pending') {
                //             $url = url('/admin/payments/stripe-payment', ['id' => $record->id]);
                //             // dd($url);
                //             return $url;
                //         }
                //         return null;
                //     })
                //     // ->hidden(fn(Appointment $record) => $record->status !== 'completed' || !$record->payment || $record->payment->payment_status !== 'pending')
                //     ->icon('heroicon-m-credit-card')
                //     ->color('success')
                //     ->button(),
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-m-eye'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil'),
                Tables\Actions\DeleteAction::make()
                    ->icon('heroicon-m-trash'),
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
            'index' => Pages\ListPayments::route('/'),
            'create' => Pages\CreatePayment::route('/create'),
            'view' => Pages\ViewPayment::route('/{record}'),
            'edit' => Pages\EditPayment::route('/{record}/edit'),
            'stripePayment' => Pages\StripePayment::route('/stripe-payment/{record}'),
        ];
    }
}