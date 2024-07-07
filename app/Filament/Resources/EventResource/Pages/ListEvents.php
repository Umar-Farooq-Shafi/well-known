<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\Pages\ListRecords;

class ListEvents extends ListRecords
{
    protected static string $resource = EventResource::class;

    public function getTabs(): array
    {
        return [
            'active' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('completed', false)),
            'expired' => Tab::make()
                ->modifyQueryUsing(fn (Builder $query) => $query->where('completed', true)),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
        ];
    }
}
