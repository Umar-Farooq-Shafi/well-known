<?php

namespace App\Filament\Resources\PointsOfSaleResource\Pages;

use App\Filament\Resources\PointsOfSaleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPointsOfSales extends ListRecords
{
    protected static string $resource = PointsOfSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
