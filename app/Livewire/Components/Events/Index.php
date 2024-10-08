<?php

namespace App\Livewire\Components\Events;

use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;
use WireUi\Traits\WireUiActions;

class Index extends Component
{
    use WithPagination;
    use WireUiActions;

    public $day = '';

    public $query = '';

    public $category = '';

    public $is_online;

    public $is_local;

    public $country;

    public $customDate;

    public $is_free;

    public $minPrice;

    public $maxPrice;

    public function mount(?int $category = null)
    {
        $this->category = $category;
    }

    public function search()
    {
        $this->resetPage();
    }

    public function eventFavourite($id)
    {
        $event = Event::find($id);

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
            ->when(
                $this->country,
                function (Builder $query) {
                    $query->where('country_id', $this->country);
                }
            )
            ->when(
                $this->query,
                function (Builder $query, $name) {
                    $query->whereHas(
                        'eventTranslations',
                        function (Builder $query) use ($name) {
                            $query->where('name', 'LIKE', "%$name%");
                        }
                    );
                }
            )
            ->when(
                $this->is_online,
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function ($query) {
                            $query->where('online', $this->is_online);
                        }
                    );
                }
            )
            ->when(
                $this->is_free,
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function (Builder $query) {
                            $query->whereHas(
                                'eventDateTickets',
                                fn (Builder $query) => $query->where('free', $this->is_free)
                            );
                        }
                    );
                }
            )
            ->when(
                $this->minPrice,
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function (Builder $query) {
                            $query->whereHas(
                                'eventDateTickets',
                                fn (Builder $query) => $query->where('price', '>=', $this->minPrice)
                            );
                        }
                    );
                }
            )
            ->when(
                $this->maxPrice,
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function (Builder $query) {
                            $query->whereHas(
                                'eventDateTickets',
                                fn (Builder $query) => $query->where('price', '<=', $this->maxPrice)
                            );
                        }
                    );
                }
            )
            ->when(
                $this->is_local,
                function (Builder $query) {
                    $query->whereHas(
                        'eventDates',
                        function ($query) {
                            $query->where('online', !$this->is_local);
                        }
                    );
                }
            )
            ->when(
                $this->day,
                function (Builder $query, string $day) {
                    $query->whereHas(
                        'eventDates',
                        function (Builder $query) use ($day) {
                            if ($day === 'today') {
                                $query->whereDate('startdate', '=', now());
                            }

                            if ($day === 'tomorrow') {
                                $query->whereDate('startdate', '=', now()->subDay());
                            }

                            if ($day === 'this-weekend') {
                                $query->whereDate('startdate', '>=', now()->startOfWeek())
                                    ->whereDate('startdate', '<=', now()->endOfWeek());
                            }

                            if ($day === 'this-week') {
                                $query->whereDate('startdate', '>=', now()->endOfWeek()->subDays(2))
                                    ->whereDate('startdate', '<=', now()->endOfWeek());
                            }

                            if ($day === 'next-week') {
                                $query->whereDate('startdate', '>=', now()->startOfWeek())
                                    ->whereDate('startdate', '<=', now()->endOfWeek()->addDays(7));
                            }

                            if ($day === 'this-month') {
                                $query->whereDate('startdate', '>=', now()->startOfMonth())
                                    ->whereDate('startdate', '<=', now()->endOfMonth());
                            }

                            if ($day === 'next-month') {
                                $query->whereDate('startdate', '>=', now()->startOfMonth()->addDays(1))
                                    ->whereDate('startdate', '<=', now()->endOfMonth()->addMonth());
                            }
                        }
                    );
                }
            )
            ->when(
                $this->customDate,
                function (Builder $query, string $customDate) {
                    $query->whereHas(
                        'eventDates',
                        function (Builder $query) use ($customDate) {
                            $query->whereDate('startdate', '=', $customDate);
                        }
                    );
                }
            )
            ->when(
                $this->category,
                function (Builder $query, $category) {
                    $query
                        ->whereHas('category', function ($query) use ($category) {
                            $query->where('id', $category);
                        });
                }
            )
            ->where('completed', false)
            ->with([
                'category.categoryTranslations' => function ($query) {
                    $query->where('locale', App::getLocale());
                }
            ]);

        return view('livewire.components.events.index', [
            'events' => $events->paginate(16),
            'events_count' => $events->count(),
        ]);
    }
}
