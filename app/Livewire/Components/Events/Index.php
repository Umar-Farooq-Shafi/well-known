<?php

namespace App\Livewire\Components\Events;

use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $day = '';

    public $query = '';

    public $category = '';

    public $is_online;

    public $is_local;

    public $country;

    public function mount(?int $category = null)
    {
        $this->category = $category;
    }

    public function search()
    {
        $this->resetPage();
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
                $this->category,
                function (Builder $query, $category) {
                    $query
                        ->whereHas('category', function ($query) use ($category) {
                            $query->where('id', $category);
                        });
                }
            )
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
