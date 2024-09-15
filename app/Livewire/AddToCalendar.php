<?php

namespace App\Livewire;

use App\Models\Event;
use Livewire\Component;

class AddToCalendar extends Component
{
    public $event;

    public $eventStart;

    public $eventEnd;

    public $eventTitle;

    public $eventDescription;

    public $eventLocation;

    public function mount(Event $event)
    {
        $this->event = $event;

        $this->eventTitle = $this->event->name;
        $this->eventDescription = $this->event->description;

        $eventDate = $this->event->eventDates->first();

        if ($eventDate) {
            $this->eventLocation = $eventDate->venue ? $eventDate->venue->name . ': ' . $eventDate->venue->stringifyAddress : __(
                'Online'
            );

            $this->eventStart = $eventDate->startdate;
            $this->eventEnd = $eventDate->enddate;
        }
    }

    public function loadData()
    {

    }

    public function googleCalendarLink()
    {
        $start = urlencode($this->eventStart);
        $end = urlencode($this->eventEnd);
        $title = urlencode($this->eventTitle);
        $description = urlencode($this->eventDescription);
        $location = urlencode($this->eventLocation);

        return "https://calendar.google.com/calendar/render?action=TEMPLATE&text={$title}&details={$description}&location={$location}&dates={$start}/{$end}";
    }

    public function yahooCalendarLink()
    {
        $start = urlencode($this->eventStart);
        $end = urlencode($this->eventEnd);
        $title = urlencode($this->eventTitle);
        $description = urlencode($this->eventDescription);
        $location = urlencode($this->eventLocation);

        return "https://calendar.yahoo.com/?v=60&view=d&type=20&title={$title}&st={$start}&et={$end}&desc={$description}&in_loc={$location}";
    }

    public function outlookCalendarLink()
    {
        return route('calendar.ics', [
            'title' => $this->eventTitle,
            'description' => $this->eventDescription,
            'start' => $this->eventStart,
            'end' => $this->eventEnd,
            'location' => $this->eventLocation,
        ]);
    }

    public function render()
    {
        return view('livewire.add-to-calendar');
    }
}
