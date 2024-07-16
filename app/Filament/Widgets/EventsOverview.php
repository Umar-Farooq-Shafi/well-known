<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\EventDate;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventsOverview extends BaseWidget
{

    protected function getStats(): array
    {
        $total = Event::count();

        $published = Event::wherePublished(true)->count();

        $upcoming = EventDate::where('startdate', '>=', now()->startOfDay())->count();

        $eventDates = EventDate::count();

        return [
            Stat::make('Events Added', "$total"),
            Stat::make('Published Events', "$published"),
            Stat::make('Upcoming Events', "$upcoming"),
            Stat::make('Event Dates', "$eventDates"),
        ];
    }
}
