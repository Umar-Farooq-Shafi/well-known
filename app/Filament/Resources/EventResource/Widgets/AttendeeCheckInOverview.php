<?php

namespace App\Filament\Resources\EventResource\Widgets;

use Filament\Widgets\Widget;

class AttendeeCheckInOverview extends Widget
{
    public $widgetData;

    protected static string $view = 'filament.resources.event-resource.widgets.attendee-check-in-overview';

    public function mount($record): void
    {
        $this->widgetData = [
            'record' => $record
        ];
    }
}
