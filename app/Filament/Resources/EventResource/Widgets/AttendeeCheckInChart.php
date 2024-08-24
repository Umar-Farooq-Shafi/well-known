<?php

namespace App\Filament\Resources\EventResource\Widgets;

use Illuminate\Contracts\View\View;
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
                'height' => 200,
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

    protected function getFooter(): null|string|View
    {
        $eventDate = $this->record->eventDates->first();

        if ($venue = $eventDate?->venue) {
            $countryAndTimeZone = $venue->getLocalTimezoneBasedOnCountry();

        } else {
            $country = auth()->user()->scanner->organizer->country;

            $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country->code);

            $countryAndTimeZone = [
                'country' => $country->name,
                'timezone' => $timezone[0],
            ];
        }

        return view('filament.resources.event-resource.pages.footer', $countryAndTimeZone);
    }

}
