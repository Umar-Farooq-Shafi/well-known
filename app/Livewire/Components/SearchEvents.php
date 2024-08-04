<?php

namespace App\Livewire\Components;

use App\Models\Event;
use App\Models\EventTranslation;
use Livewire\Component;

class SearchEvents extends Component
{
    public $query;

    public $events;

    public function updatedQuery()
    {
        $this->events = EventTranslation::query()
            ->where('name', 'like', '%' . $this->query . '%')
            ->take(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.components.search-events');
    }
}
