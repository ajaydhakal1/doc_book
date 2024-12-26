<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Filament\Resources\AppointmentResource\RelationManagers\PaymentRelationManager;
use App\Filament\Resources\AppointmentResource\RelationManagers\ReviewsRelationManager;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientHistory;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;
use Illuminate\Support\Facades\Storage;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationGroup = 'User Management';
    protected static ?int $navigationSort = 6;
    protected static ?string $recordTitleAttribute = 'disease';
    protected static ?string $navigationLabel = 'Appointments';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): string|array|null
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make('Appointment Details')
                        ->description('Create or edit appointment information')
                        ->icon('heroicon-o-calendar')
                        ->schema([
                            Select::make('patient_id')
                                ->options(function () {
                                    $patients = Patient::with('user')->get()->pluck('user.name', 'id');
                                    return $patients;
                                })
                                ->label('Patient Name')
                                ->rules('exists:patients,id')
                                ->required()
                                ->hidden(Auth::user()->role_id == 3)
                                ->searchable()
                                ->prefixIcon('heroicon-m-user'),

                            Select::make('doctor_id')
                                ->options(Doctor::with('user')->get()->pluck('user.name', 'id'))
                                ->label('Doctor')
                                ->rules('exists:doctors,id')
                                ->required()
                                ->reactive()
                                ->default(fn($record) => $record ? $record->doctor_id : null)
                                ->searchable()
                                ->prefixIcon('heroicon-m-user-circle'),

                            TextInput::make('disease')
                                ->label('Disease/Problem')
                                ->required()
                                ->prefixIcon('heroicon-m-exclamation-circle'),

                            DatePicker::make('date')
                                ->minDate(now()->toDateString())
                                ->native(false)
                                ->required()
                                ->prefixIcon('heroicon-m-calendar'),

                            TimePicker::make('start_time')
                                ->seconds(false)
                                ->required()
                                ->prefixIcon('heroicon-m-clock'),

                            TimePicker::make('end_time')
                                ->seconds(false)
                                ->after('start_time')
                                ->required()
                                ->prefixIcon('heroicon-m-clock'),
                        ])
                        ->columns(2),
                ])->from('lg'),

                Split::make([
                    Section::make('Doctor\'s Schedule')
                        ->description('Available time slots for the selected doctor')
                        ->icon('heroicon-o-calendar-days')
                        ->schema([
                            Placeholder::make('schedules')
                                ->label('Available Schedules')
                                ->content(function ($get) {
                                    $doctorId = $get('doctor_id');
                                    if (!$doctorId) {
                                        return 'Please select a doctor to view available schedules.';
                                    }

                                    $schedules = Schedule::where('doctor_id', $doctorId)->get();

                                    if ($schedules->isEmpty()) {
                                        return 'No schedules available for this doctor.';
                                    }

                                    $schedulesData = $schedules->map(function ($schedule) {
                                        return [
                                            'date' => $schedule->date,
                                            'start_time' => $schedule->start_time,
                                            'end_time' => $schedule->end_time,
                                            'status' => $schedule->status,
                                        ];
                                    })->values()->toArray();

                                    return view('filament.pages.list', [
                                        'columns' => ['day', 'time', 'status'],
                                        'rows' => $schedulesData,
                                    ]);
                                })
                                ->columnSpanFull(),
                        ]),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('patient.user.name')
                    ->label('Patient')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Bold)
                    ->icon('heroicon-m-user'),

                Tables\Columns\TextColumn::make('doctor.user.name')
                    ->label('Doctor')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium)
                    ->icon('heroicon-m-user-circle'),

                Tables\Columns\TextColumn::make('disease')
                    ->searchable()
                    ->icon('heroicon-m-exclamation-circle'),

                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable()
                    ->icon('heroicon-m-calendar'),

                Tables\Columns\TextColumn::make('start_time')
                    ->time('h:i A')
                    ->icon('heroicon-m-clock'),

                Tables\Columns\TextColumn::make('end_time')
                    ->time('h:i A')
                    ->icon('heroicon-m-clock'),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'gray',
                        'booked' => 'info',
                        'completed' => 'success',
                        'failed' => 'danger',
                    })
                    ->icon(fn(string $state): string => match ($state) {
                        'pending' => 'heroicon-m-clock',
                        'booked' => 'heroicon-m-check-circle',
                        'completed' => 'heroicon-m-check-badge',
                        'failed' => 'heroicon-m-x-circle',
                    }),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'booked' => 'Booked',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
                Tables\Filters\SelectFilter::make('doctor_id')
                    ->relationship('doctor.user', 'name')
                    ->label('Doctor'),
                // ->icon('heroicon-m-user-circle'),
            ])
            ->actions([
                Tables\Actions\Action::make('pay')
                    ->label('Pay')
                    ->url(fn(Appointment $record) => $record->payment && $record->payment->payment_status === 'pending'
                        ? route('payment.pay', $record->payment->id)
                        : null)
                    ->action(function (Appointment $record) {
                        $patientHistory = PatientHistory::where('appointment_id', $record->id);
                        $patientHistory->update([
                            'payment_id' => $record->payment->id,
                        ]);
                    })
                    ->hidden(fn(Appointment $record) => $record->status !== 'completed' || !$record->payment || $record->payment->payment_status !== 'pending')
                    ->icon('heroicon-m-credit-card')
                    ->color('success')
                    ->button(),

                Tables\Actions\Action::make('reviews')
                    ->label(fn(Appointment $record) => $record->reviews->isEmpty() ? 'Give Comments' : 'View Comments')
                    ->action(function (Appointment $record, array $data): void {
                        if ($record->reviews->isEmpty()) {
                            $filePath = null;
                            if (isset($data['pdf'])) {
                                $filePath = $data['pdf']->store('reviews', 'public');
                            }

                            $record->reviews()->create([
                                'appointment_id' => $data['appointment_id'],
                                'comment' => $data['comment'],
                                'file_path' => $filePath,
                            ]);

                            $patientHistory = PatientHistory::where('appointment_id', $record->id);
                            $patientHistory->update([
                                'review_id' => $record->reviews()->first()->id,
                            ]);
                        } else {
                            redirect()->back();
                        }
                    })
                    ->form(fn(Appointment $record) => $record->reviews->isEmpty() ? [
                        Hidden::make('appointment_id')
                            ->default($record->id),
                        TextInput::make('comment')
                            ->label('Comment')
                            ->placeholder('Write your review...')
                            ->required()
                            ->maxLength(500),
                        FileUpload::make('pdf')
                            ->label('Upload PDF')
                            ->acceptedFileTypes(['application/pdf'])
                            ->directory('reviews')
                            ->maxSize(2048),
                    ] : [
                        TextInput::make('comment')
                            ->label('Comment')
                            ->default($record->reviews->first()->comment)
                            ->disabled(),
                        $record->reviews->first()->pdf ? PdfViewerField::make('pdf')
                            ->label('View PDF')
                            ->fileUrl(Storage::url($record->reviews->first()->pdf))
                            ->minHeight('40svh') :
                        TextInput::make('pdf')
                            ->default('No PDF')
                            ->disabled(),
                    ])
                    ->modalCancelAction(false)
                    ->modalHeading(fn(Appointment $record) => $record->reviews->isEmpty() ? 'Write a Review' : 'View Review')
                    ->modalSubmitActionLabel(fn(Appointment $record) => $record->reviews->isEmpty() ? 'Submit Review' : 'Close')
                    ->hidden(fn(Appointment $record) => $record->status !== 'completed' || !in_array(Auth::user()->role_id, [1, 2]))
                    ->icon(fn(Appointment $record) => $record->reviews->isEmpty() ? 'heroicon-m-chat-bubble-left-right' : 'heroicon-m-eye')
                    ->color(fn(Appointment $record) => $record->reviews->isEmpty() ? 'primary' : 'success')
                    ->tooltip(fn(Appointment $record) => $record->reviews->isEmpty() ? 'Add your comments' : 'View your comments'),

                Tables\Actions\Action::make('updateStatus')
                    ->label('Update Status')
                    ->visible(function (Appointment $record) {
                        return Auth::user()->role_id !== 3 && $record->status !== 'completed';
                    })
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
                                if ($record->status == 'completed') {
                                    $review = $record->reviews()->first()->pluck('id');
                                    $payment = $record->payment->id;
                                } else {
                                    $payment = null;
                                    $review = null;
                                }

                                Payment::create([
                                    'appointment_id' => $record->id,
                                    'amount' => $totalFee,
                                    'patient_id' => $record->patient_id,
                                    'payment_status' => 'pending',
                                ]);

                                PatientHistory::create([
                                    'appointment_id' => $record->id,
                                    'patient_id' => $record->patient_id,
                                    'doctor_id' => $record->doctor_id,
                                    'review_id' => $review,
                                    'payment_id' => $payment,
                                ]);

                                DB::statement('PRAGMA foreign_keys = OFF');
                                Schedule::where('doctor_id', $record->doctor_id)
                                    ->where('date', $record->date)
                                    ->where('start_time', $record->start_time)
                                    ->where('end_time', $record->end_time)
                                    ->delete();
                                DB::statement('PRAGMA foreign_keys = ON');
                                break;

                            case 'failed':
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
                    ->icon('heroicon-m-arrow-path')
                    ->button(),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->icon('heroicon-m-eye'),
                    Tables\Actions\EditAction::make()
                        ->icon('heroicon-m-pencil'),
                    Tables\Actions\DeleteAction::make()
                        ->icon('heroicon-m-trash'),
                ])->color('primary')
            ])
            ->emptyStateIcon('heroicon-o-calendar')
            ->emptyStateHeading('No appointments yet')
            ->emptyStateDescription('Start by creating a new appointment.')
            ->defaultSort('date', 'desc');
    }



    public static function getRelations(): array
    {
        return [
            PaymentRelationManager::class,
            ReviewsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'view' => Pages\ViewAppointment::route('/{record}/view'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }
}