<?php

namespace App\Livewire;

use App\Models\Audience;
use App\Models\Event;
use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Title;
use Livewire\Component;

class Home extends Component
{
    #[Title("Nepal's 1st event ticketing platform. We provide complete solution regarding your event tickets from selling to door verification. | 'Aafno Ticket Nepal'")]
    public function render()
    {
        $homepage_categories_number = Setting::where('key', 'homepage_categories_number')->first()?->value;
        $homepage_events_number = Setting::where('key', 'homepage_events_number')->first()?->value;

        $audiences = Audience::with([
            'audienceTranslations' => function ($query) {
                $query->where('locale', App::getLocale());
            }
        ])
            ->take($homepage_categories_number)
            ->get();

        $events = Event::with([
            'eventTranslations' => function ($query) {
                $query->where('locale', App::getLocale());
            }
        ])
            ->take($homepage_events_number)
            ->get();

        return view('livewire.home', [
            'audiences' => $audiences,
            'events' => $events,
        ]);
    }
}
