<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderTicket;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrdersOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $total = Order::count();

        $paid = Order::whereNotNull('payment_id')->count();

        $awaiting = Order::whereNull('payment_id')->count();

        $tickets = OrderTicket::count();

        return [
            Stat::make('Orders Placed', "$total"),
            Stat::make('Paid Orders', "$paid"),
            Stat::make('Awaiting Payment', "$awaiting"),
            Stat::make('Tickets Sold', "$tickets"),
        ];
    }
}
