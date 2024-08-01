<?php

namespace App\Filament\Widgets;

use App\Models\OrderTicket;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class TicketsOverview extends ChartWidget
{
    protected static ?string $heading = 'Tickets Sold This Month';

    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return !auth()->user()->hasRole('ROLE_ATTENDEE');
    }

    protected function getData(): array
    {
        $trend = Trend::model(OrderTicket::class)
            ->between(
                start: now()->startOfMonth(),
                end: now()->endOfMonth(),
            )
            ->perDay()
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Ticket Sold',
                    'data' => $trend->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                    ],
                    'borderColor' => [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                    ],
                    'borderWidth' => 1,
                ],
            ],
            'labels' =>  $trend
                ->map(fn (TrendValue $value) => Carbon::make($value->date)->format('m-D'))
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
