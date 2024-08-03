<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderTicket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;

class OrdersOverview extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    public static function canView(): bool
    {
        return !auth()->user()->hasAnyRole(['ROLE_ATTENDEE', 'ROLE_POINTOFSALE', 'ROLE_SCANNER']);
    }

    protected function getStats(): array
    {
        $total = Order::when(
            auth()->user()->hasRole('ROLE_ORGANIZER'),
            function ($query) {
                $query->where('user_id', auth()->id());
            }
        )
            ->count();

        $paid = Order::whereNotNull('payment_id')
            ->when(
                auth()->user()->hasRole('ROLE_ORGANIZER'),
                function ($query) {
                    $query->where('user_id', auth()->id());
                }
            )
            ->count();

        $awaiting = Order::whereNull('payment_id')
            ->when(
                auth()->user()->hasRole('ROLE_ORGANIZER'),
                function ($query) {
                    $query->where('user_id', auth()->user()->organizer_id);
                }
            )
            ->count();

        $tickets = OrderTicket::when(
            auth()->user()->hasRole('ROLE_ORGANIZER'),
            function (Builder $query) {
                $query->whereHas(
                    'orderElement.order',
                    function (Builder $query) {
                        $query->where('user_id', auth()->id());
                    }
                );
            }
        )
            ->count();

        return [
            Stat::make('Orders Placed', "$total"),
            Stat::make('Paid Orders', "$paid"),
            Stat::make('Awaiting Payment', "$awaiting"),
            Stat::make('Tickets Sold', "$tickets"),
        ];
    }
}
