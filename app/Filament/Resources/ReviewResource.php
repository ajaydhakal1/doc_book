<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Filament\Resources\ReviewResource\RelationManagers;
use App\Models\Review;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action as TableAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Joaopaulolndev\FilamentPdfViewer\Forms\Components\PdfViewerField;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $label = 'Remarks';

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-oval-left-ellipsis';

    protected static ?string $navigationGroup = 'User Management';

    protected static ?int $navigationSort = 9;

    // Add custom styling for the navigation item
    protected static ?string $activeNavigationIconColor = 'primary';

    protected static ?string $navigationBadge = 'new';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Details')
                    ->description('Manage review information')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->schema([
                        Forms\Components\TextInput::make('appointment_id')
                            ->required()
                            ->disabledOn('edit')
                            ->numeric()
                            ->prefix('#')
                            ->prefixIcon('heroicon-m-identification')
                            ->helperText('Enter the appointment ID')
                            ->columnSpan(2),

                        Forms\Components\Textarea::make('comment')
                            ->label('Review Comments')
                            ->placeholder('Enter your review comments here...')
                            // ->icon('heroicon-m-chat-bubble-bottom-center-text')
                            ->columnSpan('full'),

                        Forms\Components\FileUpload::make('pdf')
                            ->label('PDF Document')
                            // ->icon('heroicon-o-document')
                            ->acceptedFileTypes(['application/pdf'])
                            ->helperText('Upload PDF documents only')
                            ->downloadable()
                            ->visibleOn('create')
                            ->columnSpan('full'),
                    ])
                    ->columns(2)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('appointment_id')
                    ->label('Appointment ID')
                    ->icon('heroicon-m-identification')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('ID copied')
                    ->copyMessageDuration(1500),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Review Comment')
                    ->icon('heroicon-m-chat-bubble-bottom-center-text')
                    ->searchable()
                    ->wrap()
                    ->limit(50),

                Tables\Columns\TextColumn::make('pdf')
                    ->label('PDF Document')
                    ->icon('heroicon-o-document')
                    ->formatStateUsing(
                        fn(Review $record) => $record->pdf ?
                        view('components.filament.buttons.view-pdf', [
                            'url' => Storage::url($record->pdf)
                        ]) :
                        'No PDF'
                    )
                    ->html(),

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
            ->modifyQueryUsing(function (Builder $query) {
                $user = User::find(Auth::user()->id);
                if ($user->hasRole('Admin')) {
                    return $query;
                }

                if ($user->hasRole('Doctor')) {
                    return $query->where('doctor_id', $user->doctor->id);
                }

                if ($user->hasRole('Patient')) {
                    if ($user->patient) {
                        return $query->where('patient_id', $user->patient->id);
                    } else {
                        return $query->whereRaw('1 = 0');
                    }
                }

                return $query->whereRaw('1 = 0');
            })
            ->filters([
                // Preserved empty filters array
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-m-eye'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square'),
                DeleteAction::make()
                    ->icon('heroicon-m-trash'),
            ])
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('No Reviews yet')
            ->emptyStateDescription('Once reviews are created, they will appear here.')
            ->striped()
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'view' => Pages\ViewReview::route('/{record}'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}