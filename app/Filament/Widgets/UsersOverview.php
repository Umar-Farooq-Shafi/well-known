<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $attendees = User::whereRoles('a:1:{i:0;s:13:"ROLE_ATTENDEE";}')
            ->where('enabled', true)
            ->count();

        $organizers = User::whereRoles('a:1:{i:0;s:14:"ROLE_ORGANIZER";}')
            ->where('enabled', true)
            ->count();

        $scanners = User::whereRoles('a:1:{i:0;s:12:"ROLE_SCANNER";}')
            ->count();

        $pointOfSales = User::whereRoles('a:1:{i:0;s:16:"ROLE_POINTOFSALE";}')
            ->count();

        return [
            Stat::make('Active Attendees', "$attendees"),
            Stat::make('Active Organizers', "$organizers"),
            Stat::make('Scanners', "$scanners"),
            Stat::make('Point Of Sales', "$pointOfSales"),
        ];
    }
}
