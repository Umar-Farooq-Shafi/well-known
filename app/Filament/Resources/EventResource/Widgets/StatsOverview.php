<?php

namespace App\Filament\Resources\EventResource\Widgets;

use App\Models\Event;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected ?Event $event = null;

    public function mount($event)
    {
        $this->event = $event;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Total Gross Sales', "45"),
            Stat::make('Ticket Sold', "22"),
            Stat::make('Event Views', "86"),
            Stat::make('Attendee Reviews', "84"),
        ];
    }
}
