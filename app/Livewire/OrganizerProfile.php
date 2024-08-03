<?php

namespace App\Livewire;

use App\Models\Event;
use App\Models\Organizer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class OrganizerProfile extends Component
{
    use WireUiActions;

    public $organizer;

    public $activeTab = 1;

    public function mount(string $slug)
    {
        $this->organizer = Organizer::whereSlug($slug)->firstOrFail();
    }

    public function followOrganization()
    {
        if ($this->organizer->followings()->where('User_id', auth()->id())->exists()) {
            $this->organizer->followings()->detach(auth()->id());
        } else {
            $this->organizer->followings()->attach(auth()->id());
        }

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Saved!',
        ]);
    }

    public function setActiveTab($active)
    {
        $this->activeTab = $active;
    }

    public function eventFavourite($eventId)
    {
        $event = Event::findOrFail($eventId);

        if ($event->favourites()->where('User_id', auth()->id())->exists()) {
            $event->favourites()->detach(auth()->id());
        } else {
            $event->favourites()->attach(auth()->id());
        }

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Saved!',
        ]);
    }

    public function render()
    {
        $events = Event::query()
            ->with([
                'eventTranslations' => function ($query) {
                    $query->where('locale', App::getLocale());
                }
            ])
            ->with([
                'category.categoryTranslations' => function ($query) {
                    $query->where('locale', App::getLocale());
                }
            ])
            ->when(
                $this->activeTab === 1,
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function ($query) {
                            $query->whereDate('enddate', '>=', now());
                        }
                    );
                }
            )
            ->when(
                $this->activeTab === 2,
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function ($query) {
                            $query->whereDate('enddate', '<', now());
                        }
                    );
                }
            )
            ->where('organizer_id', $this->organizer->id)
            ->get();

        $eventsOnSale = Event::query()
            ->whereHas(
                'eventDates',
                function ($query) {
                    $query->whereDate('enddate', '>=', now());
                }
            )
            ->where('organizer_id', $this->organizer->id)
            ->count();

        $pastEvent = Event::query()
            ->whereHas(
                'eventDates',
                function ($query) {
                    $query->whereDate('enddate', '<', now());
                }
            )
            ->where('organizer_id', $this->organizer->id)
            ->count();

        return view('livewire.organizer-profile', [
            'events' => $events,
            'eventsOnSale' => $eventsOnSale,
            'pastEvent' => $pastEvent
        ]);
    }
}
