<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\App;

class EventsTableOverview extends BaseWidget
{

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return !auth()->user()->hasRole('ROLE_ATTENDEE');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Event::query())
            ->modifyQueryUsing(fn () => $this->getTableQuery())
            ->columns([
                Tables\Columns\ImageColumn::make('image_name')
                    ->label('Image')
                    ->extraImgAttributes(['loading' => 'lazy'])
                    ->square()
                    ->getStateUsing(fn($record) => $record->image_name ? ['events/' . $record->image_name] : null)
                    ->disk('public'),

                Tables\Columns\TextColumn::make('id')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->eventTranslations()->where('locale', App::getLocale())->first()?->name)
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('organizer.name')
                    ->searchable(isIndividual: true)
                    ->sortable(),

                Tables\Columns\TextColumn::make('is_featured')
                    ->label('Is Feathered')
                    ->formatStateUsing(fn($state) => $state ? 'Yes' : 'No')
                    ->searchable(),
            ]);
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        return Event::query()
            ->latest('created_at')
            ->limit(3);
    }
}
