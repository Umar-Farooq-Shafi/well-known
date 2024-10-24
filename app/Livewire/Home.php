<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\CountryTranslation;
use App\Models\Event;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Home extends Component
{
    use WithPagination;

    #[Url]
    public $country = 'All';

    #[Url]
    public $category = '';

    public $perPage = 12;

    public $hasMorePages;

    public function mount()
    {
        $homepageFeaturedEventsNb = (int)Setting::where('key', 'homepage_featured_events_nb')->first()?->value;

        if ($homepageFeaturedEventsNb && $this->perPage > $homepageFeaturedEventsNb) {
            $this->perPage = $homepageFeaturedEventsNb;
        }
    }

    public function updateCountry($country)
    {
        $this->country = $country;

        return redirect()->to('?country=' . $country . '&category=' . $this->category . '&scroll-to-category=true');
    }

    public function loadMore()
    {
        $homepageFeaturedEventsNb = (int)Setting::where('key', 'homepage_featured_events_nb')->first()?->value;

        if ($homepageFeaturedEventsNb && ($this->perPage + 12) <= $homepageFeaturedEventsNb) {
            $this->perPage += 12;
        } else if ($homepageFeaturedEventsNb && $this->perPage < $homepageFeaturedEventsNb) {
            $this->perPage = $homepageFeaturedEventsNb;
        }
    }

    #[Computed()]
    public function paginate()
    {
        // Fetch the featured events based on the current perPage value
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
            ->where('is_featured', true)
            ->where('completed', false)
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage, total: (int)Setting::where('key', 'homepage_featured_events_nb')->first()?->value);

        $this->hasMorePages = $featuredEvents->hasMorePages();

        return $featuredEvents->items();
    }

    #[Title("Nepal's 1st event ticketing platform. We provide complete solution regarding your event tickets from selling to door verification. | 'Aafno Ticket Nepal'")]
    public function render()
    {
        $homepage_categories_number = Setting::where('key', 'homepage_categories_number')->first()?->value;
        $homepage_events_number = Setting::where('key', 'homepage_events_number')->first()?->value;

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
            'category' => function ($query) {
                $query->with([
                    'categoryTranslations' => function ($query) {
                        $query->where('locale', App::getLocale());
                    },
                ]);
            },
        ])
            ->when(
                $this->country !== 'All',
                function (Builder $query) {
                    $query->where(
                        'country_id',
                        CountryTranslation::where('name', 'like', '%' . $this->country . '%')
                            ->first()?->translatable_id
                    );
                }
            )
            ->when(
                $this->category === 'online',
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function ($query) {
                            $query->where('online', true);
                        }
                    );
                }
            )
            ->when(
                $this->category === 'free',
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function (Builder $query) {
                            $query->whereHas(
                                'eventDateTickets',
                                fn(Builder $query) => $query->where('free', true)
                            );
                        }
                    );
                }
            )
            ->when(
                $this->category === 'today',
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function (Builder $query) {
                            $query->whereDate('startdate', '=', now());
                        }
                    );
                }
            )
            ->when(
                $this->category === 'this-weekend',
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function (Builder $query) {
                            $query->whereDate('startdate', '>=', now()->startOfWeek())
                                ->whereDate('startdate', '<=', now()->endOfWeek());
                        }
                    );
                }
            )
            ->where('completed', false)
            ->orderBy('created_at', 'desc')
            ->take(20)
            ->get();

        $countries = CountryTranslation::query()
            ->whereHas(
                'country',
                fn(Builder $query) => $query->whereIn(
                    'id',
                    Event::query()->select('country_id')
                        ->pluck('country_id')
                        ->toArray()
                )
            )
            ->where('locale', App::getLocale())
            ->get()
            ->mapWithKeys(function ($translation) {
                return [$translation->country->id => $translation->name];
            })
            ->toArray();

        $upcomingEvents = Event::with([
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
        ])->where('completed', false)
            ->orderBy('created_at', 'desc')
            ->take((int)$homepage_events_number)
            ->get();

        return view('livewire.home', [
            'categories' => $categories,
            'featuredEvents' => $this->paginate(),
            'events' => $events,
            'countries' => $countries,
            'upcomingEvents' => $upcomingEvents
        ]);
    }
}
