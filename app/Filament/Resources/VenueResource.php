<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenueResource\Pages;
use App\Models\Venue;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Venues';

    protected static ?string $navigationLabel = 'Manage Venues';

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
                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->venueTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\ImageColumn::make('venueImages.image_name')
                    ->label('Image')
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->circular()
                    ->ring(5)
                    ->stacked()
                    ->getStateUsing(function ($record) {
                        $images = [];

                        foreach ($record->venueImages as $image) {
                            $images[] = Storage::disk('public')->url('venues/' . $image->image_name);
                        }

                        return $images;
                    })
                    ->disk('public'),

                Tables\Columns\TextColumn::make('organizer.name')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('events_count')
                    ->counts('events')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('hidden')
                    ->label('Status')
                    ->formatStateUsing(function ($state): string {
                        return match ($state) {
                            1 => 'Hidden',
                            0 => 'Visible',
                            default => $state
                        };
                    })
                    ->icon(fn($state) => $state ? 'heroicon-o-eye-slash' : 'heroicon-o-eye')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            1 => 'danger',
                            0 => 'success',
                            default => 'info'
                        };
                    }),

                Tables\Columns\TextColumn::make('listedondirectory')
                    ->label('Status')
                    ->formatStateUsing(function ($state): string {
                        return match ($state) {
                            1 => 'Listed on the directory',
                            0 => 'Not listed on the directory',
                            default => $state
                        };
                    })
                    ->icon(fn($state) => $state ? 'heroicon-o-eye' : 'heroicon-o-eye-slash')
                    ->badge()
                    ->color(function ($state) {
                        return match ($state) {
                            0 => 'danger',
                            1 => 'success',
                            default => 'info'
                        };
                    })
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('listedondirectory')
                    ->label('Listed on the directory')
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\Action::make('Hide')
                        ->icon('heroicon-o-eye-slash')
                        ->hidden(fn($record) => $record->hidden)
                        ->action(fn($record) => $record->update(['hidden' => true])),

                    Tables\Actions\Action::make('Show')
                        ->icon('heroicon-o-eye')
                        ->visible(fn($record) => $record->hidden)
                        ->action(fn($record) => $record->update(['hidden' => false])),

                    Tables\Actions\Action::make('List on the public directory')
                        ->icon('fas-square-plus')
                        ->hidden(fn($record) => $record->listedondirectory)
                        ->action(fn($record) => $record->update(['listedondirectory' => true])),

                    Tables\Actions\Action::make('Hide from the public directory')
                        ->icon('fas-minus-square')
                        ->visible(fn($record) => $record->listedondirectory)
                        ->action(fn($record) => $record->update(['listedondirectory' => false])),

                    Tables\Actions\DeleteAction::make(),
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'edit' => Pages\EditVenue::route('/{record}/edit'),
        ];
    }
}
