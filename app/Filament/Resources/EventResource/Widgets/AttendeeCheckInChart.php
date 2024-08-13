<?php

namespace App\Filament\Resources\EventResource\Widgets;

use Illuminate\Database\Eloquent\Model;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class AttendeeCheckInChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'attendeeCheckInChart';

    public ?Model $record = null;

    protected static bool $isLazy = true;

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'Total attendees check-in';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $eventData = $this->record->eventDates->first();

        return [
            'chart' => [
                'type' => 'radialBar',
                'height' => 250,
            ],
            'series' => [$eventData?->getTotalCheckInPercentage()],
            'plotOptions' => [
                'radialBar' => [
                    'hollow' => [
                        'size' => '70%',
                    ],
                    'dataLabels' => [
                        'show' => true,
                        'name' => [
                            'show' => true,
                            'fontFamily' => 'inherit'
                        ],
                        'value' => [
                            'show' => true,
                            'fontFamily' => 'inherit',
                            'fontWeight' => 600,
                            'fontSize' => '20px'
                        ],
                    ],

                ],
            ],
            'stroke' => [
                'lineCap' => 'round',
            ],
            'labels' => [$eventData?->getScannedTicketsCount() . ' / ' . $eventData?->getOrderElementsQuantitySum()],
            'colors' => ['#f59e0b'],
        ];
    }
}
