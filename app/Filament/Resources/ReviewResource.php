<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReviewResource\Pages;
use App\Models\Event;
use App\Models\Review;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
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
