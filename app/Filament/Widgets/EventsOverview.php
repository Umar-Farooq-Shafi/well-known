<?php

namespace App\Filament\Widgets;

use App\Models\Event;
use App\Models\EventDate;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class EventsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return !auth()->user()->hasAnyRole(['ROLE_ATTENDEE', 'ROLE_POINTOFSALE', 'ROLE_SCANNER']);
    }

    protected function getStats(): array
    {
        $total = Event::when(
            auth()->user()->hasRole('ROLE_ORGANIZER'),
            function ($query) {
                $query->where('organizer_id', auth()->user()->organizer_id);
            }
        )
            ->count();

        $published = Event::wherePublished(true)
            ->when(
                auth()->user()->hasRole('ROLE_ORGANIZER'),
                function ($query) {
                    $query->where('organizer_id', auth()->user()->organizer_id);
                }
            )
            ->count();

        $upcoming = EventDate::where('startdate', '>=', now()->startOfDay())
            ->when(
                auth()->user()->hasRole('ROLE_ORGANIZER'),
                function ($query) {
                    $query->whereHas(
                        'event',
                        fn ($query) => $query->where('organizer_id', auth()->user()->organizer_id)
                    );
                }
            )
            ->count();

        $eventDates = EventDate::when(
            auth()->user()->hasRole('ROLE_ORGANIZER'),
            function ($query) {
                $query->whereHas(
                    'event',
                    fn ($query) => $query->where('organizer_id', auth()->user()->organizer_id)
                );
            }
        )
            ->count();

        return [
            Stat::make('Events Added', "$total"),
            Stat::make('Published Events', "$published"),
            Stat::make('Upcoming Events', "$upcoming"),
            Stat::make('Event Dates', "$eventDates"),
        ];
    }
}
