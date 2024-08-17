<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\CountryTranslation;
use App\Models\Event;
use App\Models\Setting;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Title;
use Livewire\Component;

class Home extends Component
{
    public $country = 'All';

    public function updateCountry($country)
    {
        $this->country = $country;
    }

    #[Title("Nepal's 1st event ticketing platform. We provide complete solution regarding your event tickets from selling to door verification. | 'Aafno Ticket Nepal'")]
    public function render()
    {
        $homepage_categories_number = Setting::where('key', 'homepage_categories_number')->first()?->value;
        $homepage_events_number = Setting::where('key', 'homepage_events_number')->first()?->value;
        $homepage_featured_events_nb = Setting::where('key', 'homepage_featured_events_nb')->first()?->value;

        $categories = Category::with([
            'categoryTranslations' => function ($query) {
                $query->where('locale', App::getLocale());
            },
        ])
            ->take($homepage_categories_number)
            ->get();

        $events = Event::with([
            'eventTranslations' => function ($query) {
                $query->where('locale', App::getLocale());
            },
        ])
            ->where('completed', false)
            ->take($homepage_events_number)
            ->get();

        $featuredEvents = Event::with([
            'eventTranslations' => function ($query) {
                $query->where('locale', App::getLocale());
            },
            'category' => function ($query) {
                $query->with([
                    'categoryTranslations' => function ($query) {
                        $query->where('locale', App::getLocale());
                    },
                ]);
            },
        ])
            ->where('is_featured', 1)
            ->take($homepage_featured_events_nb)
            ->get();

        $countries = CountryTranslation::with('country')
            ->get()
            ->where('locale', App::getLocale())
            ->mapWithKeys(function ($translation) {
                return [$translation->country->id => $translation->name];
            })
            ->toArray();

        return view('livewire.home', [
            'categories' => $categories,
            'events' => $events,
            'featuredEvents' => $featuredEvents,
            'countries' => $countries,
        ]);
    }
}
