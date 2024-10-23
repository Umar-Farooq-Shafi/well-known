<?php

namespace App\Livewire\Components;

use App\Models\CartElement;
use App\Models\EventTranslation;
use App\Models\HomepageHeroSetting;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class SearchEvents extends Component
{
    public $query;

    public $events;

    public $country;

    public $dates;

    public $cartElements;

    public $homepage;

    public function mount()
    {
        $this->cartElements = CartElement::query()
            ->where('user_id', auth()->id())
            ->count();

        $this->homepage = HomepageHeroSetting::query()->first();
    }

    public function submit()
    {
        $query = EventTranslation::query();

        if ($this->dates) {
            $pattern = '/(\w{3}-\d{2})/';

            preg_match_all($pattern, $this->dates, $matches);

            if (count($matches) > 0 && count($matches[0]) > 0) {
                $start = Carbon::make($matches[0][0]);
                $end = Carbon::make($matches[0][1]);

                $query->whereHas(
                    'event.eventDates',
                    fn (Builder $query) => $query
                        ->whereDate('startdate', '>=', $start)
                        ->whereDate('enddate', '<=', $end)
                );
            }
        }

        $this->events = $query->when(
            $this->query,
            fn (Builder $query) => $query->where('name', 'like', '%' . $this->query . '%')
        )
            ->when(
                $this->country,
                fn (Builder $query) => $query
                    ->whereHas(
                        'event',
                        fn (Builder $query) => $query->where('country_id', $this->country)
                    )
            )
            ->whereHas(
                'event',
                fn (Builder $query) => $query->where('completed', false)
            )
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.components.search-events');
    }
}
