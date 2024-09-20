<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\CountryTranslation;
use App\Models\Event;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

class Home extends Component
{
    #[Url]
    public $country = 'All';

    #[Url]
    public $category = '';

    #[Url]
    public $page = 1;

    public $perPage = 12;

    public function updateCountry($country)
    {
        $this->country = $country;

        $this->redirect('?country=' . $country . '&category=' . $this->category);
    }

    public function loadMore()
    {
        $this->perPage += 12;
    }

    #[Title("Nepal's 1st event ticketing platform. We provide complete solution regarding your event tickets from selling to door verification. | 'Aafno Ticket Nepal'")]
    public function render()
    {
        $homepage_categories_number = Setting::where('key', 'homepage_categories_number')->first()?->value;
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
                                fn (Builder $query) => $query->where('free', true)
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
            ->where('is_featured', '=', true)
            ->where('completed', false)
            ->orderBy('created_at', 'desc')
            ->take($homepage_featured_events_nb)
            ->paginate($this->perPage);

        $countries = CountryTranslation::query()
            ->whereHas(
                'country',
                fn (Builder $query) => $query->whereIn(
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

        return view('livewire.home', [
            'categories' => $categories,
            'featuredEvents' => $featuredEvents,
            'events' => $events,
            'countries' => $countries,
        ]);
    }
}
