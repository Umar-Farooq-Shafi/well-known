<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Event;
use App\Models\Review;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('event.image_name')
                    ->label('Image')
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->square()
                    ->getStateUsing(fn($record) => $record->event->image_name ? ['events/' . $record->event->image_name] : null)
                    ->disk('public'),

                Tables\Columns\TextColumn::make('event.name')
                    ->label('Event Name')
                    ->sortable(),

                Tables\Columns\TextColumn::make('rating')
                    ->sortable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('headline')
                    ->wrap()
                    ->sortable(),

                Tables\Columns\TextColumn::make('details')
                    ->wrap()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_id')
                    ->label('Events')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->getSearchResultsUsing(
                        function (string $query) {
                            $options = [];

                            $events = Event::with([
                                'eventTranslations' => fn($builder) => $builder
                                    ->where('name', 'LIKE', "%{$query}%")
                            ])
                                ->get();

                            foreach ($events as $event) {
                                $options[$event->id] = $event->eventTranslations()
                                    ->first()?->name ?? '';
                            }

                            return $options;
                        }
                    )
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->when(
                auth()->user()->hasRole('ROLE_ATTENDEE'),
                fn ($query) => $query->where('user_id', auth()->id())
            );
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviews::route('/'),
            'create' => Pages\CreateReview::route('/create'),
            'edit' => Pages\EditReview::route('/{record}/edit'),
        ];
    }
}
