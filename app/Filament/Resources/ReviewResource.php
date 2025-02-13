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

    protected static ?string $activeNavigationIconColor = 'primary';

    protected static ?string $navigationBadge = 'new';

    protected static ?string $recordTitleAttribute = 'appointment_id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Review Details')
                    ->description('Manage review information')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->collapsible()
                    ->persistCollapsed()
                    ->schema([
                        Forms\Components\TextInput::make('appointment_id')
                            ->required()
                            ->disabledOn('edit')
                            ->numeric()
                            ->prefix('#')
                            ->prefixIcon('heroicon-m-identification')
                            ->helperText('Enter the appointment ID')
                            ->columnSpan(2)
                            ->extraInputAttributes(['class' => 'font-bold'])
                            ->live(),

                        Forms\Components\Textarea::make('comment')
                            ->label('Review Comments')
                            ->placeholder('Enter your review comments here...')
                            ->columnSpan('full')
                            ->rows(4)
                            ->extraInputAttributes(['class' => 'prose'])
                            ->maxLength(1000)
                            ->helperText('Maximum 1000 characters')
                            ->live(),

                        Forms\Components\FileUpload::make('pdf')
                            ->label('PDF Document')
                            ->acceptedFileTypes(['application/pdf'])
                            ->helperText('Upload PDF documents only')
                            ->downloadable()
                            ->preserveFilenames()
                            ->maxSize(5120)
                            ->directory('review-documents')
                            ->visibleOn('create')
                            ->columnSpan('full')
                            ->uploadProgressIndicator()
                            ->imagePreviewHeight('250')
                            ->panelAspectRatio('2:1')
                            ->panelLayout('integrated')
                            ->removeUploadedFileButtonPosition('right')
                            ->uploadButtonPosition('left'),
                    ])
                    ->columns(2)
                    ->compact()
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
                    ->copyMessageDuration(1500)
                    ->weight('bold')
                    ->color('primary'),

                Tables\Columns\TextColumn::make('comment')
                    ->label('Review Comment')
                    ->icon('heroicon-m-chat-bubble-bottom-center-text')
                    ->searchable()
                    ->wrap()
                    ->limit(50)
                    ->tooltip(function (Tables\Columns\TextColumn $column): ?string {
                        $state = $column->getState();
                        if (strlen($state) <= 50) {
                            return null;
                        }
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('pdf')
                    ->label('PDF Document')
                    ->alignCenter()
                    ->formatStateUsing(
                        fn(Review $record) => $record->pdf
                        ? '<div class="flex justify-center"><a href="' . Storage::url($record->pdf) . '" target="_blank" class="inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 focus:ring-2 focus:ring-primary-300 transition-colors duration-200">View PDF</a></div>'
                        : '<div class="flex justify-center"><span class="inline-flex items-center px-3 py-1.5 text-sm text-gray-500 bg-gray-100 rounded-lg">No PDF</span></div>'
                    )
                    ->html(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created Date')
                    ->icon('heroicon-m-calendar')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray'),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->icon('heroicon-m-clock')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('gray')
                    ->since(),
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
            ->filters([])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->icon('heroicon-m-eye')
                    ->tooltip('View details')
                    ->color('info'),
                Tables\Actions\EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->tooltip('Edit review')
                    ->color('warning'),
                DeleteAction::make()
                    ->icon('heroicon-m-trash')
                    ->tooltip('Delete review')
                    ->color('danger'),
            ])
            ->emptyStateIcon('heroicon-o-document-text')
            ->emptyStateHeading('No Reviews yet')
            ->emptyStateDescription('Once reviews are created, they will appear here.')
            ->striped()
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100])
            ->poll('30s')
            ->defaultPaginationPageOption(25);
    }

    public static function getRelations(): array
    {
        return [];
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