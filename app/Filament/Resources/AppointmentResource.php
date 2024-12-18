<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers\PaymentRelationManager;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action as TableAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?int $navigationSort = 6;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Basic Details')
                    ->schema([
                        Select::make('patient_id')
                            ->label('Patient')
                            ->options(
                                Patient::with('user')->get()->pluck('user.name', 'id')
                            )
                            ->native(false)
                            ->searchable()
                            ->required(),

                        Select::make('doctor_id')
                            ->label('Doctor')
                            ->options(
                                Doctor::with('user')->get()->pluck('user.name', 'id')
                            )
                            ->native(false)
                            ->required()
                            ->searchable(),

                        TextInput::make('disease')
                            ->label('Disease')
                            ->required(),
                    ]),

                Fieldset::make('Appointment Details')
                    ->schema([
                        DatePicker::make('date')
                            ->label('Appointment Date')
                            ->default(Carbon::tomorrow())
                            ->closeOnDateSelection()
                            ->required(),

                        TimePicker::make('start_time')
                            ->label('Start Time')
                            ->default(fn() => Carbon::now()->format('H:i'))
                            ->required(),

                        TimePicker::make('end_time')
                            ->label('End Time')
                            ->default(fn() => Carbon::now()->addHour()->format('H:i'))
                            ->required(),

                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'booked' => 'Booked',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ])
                            ->default('pending')
                            ->required()
                            ->disabled(fn($record) => $record && $record->status === 'completed'),
                    ]),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('patient.user.name')
                    ->label('Patient'),

                TextColumn::make('doctor.user.name')
                    ->label('Doctor')
                    ->sortable(),

                TextColumn::make('disease')
                    ->searchable(),

                TextColumn::make('date')
                    ->date()
                    ->sortable(),

                TextColumn::make('start_time')
                    ->time('h:i A'),

                TextColumn::make('end_time')
                    ->time('h:i A'),

                TextColumn::make('status')
                    ->label('Status')
                    ->formatStateUsing(fn($state) => match ($state) {
                        'pending' => '<span class="text-gray-500 font-semibold">Pending</span>',
                        'booked' => '<span class="text-blue-500 font-semibold">Booked</span>',
                        'completed' => '<span class="text-green-500 font-semibold">Completed</span>',
                        'failed' => '<span class="text-red-500 font-semibold">Failed</span>',
                    })
                    ->html(),
            ])
            ->actions([

                TableAction::make('pay')
                    ->label('Pay')
                    ->url(fn(Appointment $record) => $record->payment && $record->payment->payment_status === 'pending'
                        ? route('payment.pay', $record->payment->id)
                        : null)
                    ->hidden(fn(Appointment $record) => $record->status !== 'completed' || !$record->payment || $record->payment->payment_status !== 'pending')
                    ->icon('heroicon-o-credit-card')
                    ->color('success'),

                TableAction::make('updateStatus')
                    ->label('Update Status')
                    ->action(function (Appointment $record, array $data): void {
                        if ($record->status === 'completed') {
                            throw new \Exception('Status cannot be changed after completion.');
                        }

                        $status = $data['status'];

                        switch ($status) {
                            case 'completed':
                                $startTime = Carbon::parse($record->start_time);
                                $endTime = Carbon::parse($record->end_time);
                                $durationInHours = $startTime->diffInHours($endTime);
                                $totalFee = $durationInHours * $record->doctor->hourly_rate;

                                Payment::create([
                                    'appointment_id' => $record->id,
                                    'amount' => $totalFee,
                                    'patient_id' => $record->patient_id,
                                    'payment_status' => 'pending',
                                ]);

                                DB::statement('PRAGMA foreign_keys = OFF');
                                Schedule::where('doctor_id', $record->doctor_id)
                                    ->where('date', $record->date)
                                    ->where('start_time', $record->start_time)
                                    ->where('end_time', $record->end_time)
                                    ->delete();
                                DB::statement('PRAGMA foreign_keys = ON');

                                break;

                            case 'cancelled':
                                Schedule::where('doctor_id', $record->doctor_id)
                                    ->where('date', $record->date)
                                    ->where('start_time', $record->start_time)
                                    ->where('end_time', $record->end_time)
                                    ->delete();

                                break;
                        }

                        $record->update(['status' => $status]);
                    })
                    ->form([
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'booked' => 'Booked',
                                'completed' => 'Completed',
                                'failed' => 'Failed',
                            ])
                            ->required(),
                    ])
                    ->icon('heroicon-o-arrow-path')
                    ->hidden(fn(Appointment $record) => $record->status === 'completed'),

                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            PaymentRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'view' => Pages\ViewAppointment::route('/{record}'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}
