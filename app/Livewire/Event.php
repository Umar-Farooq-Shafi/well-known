<?php

namespace App\Livewire;

use App\Models\EventTranslation;
use Livewire\Component;

class Event extends Component
{
    public ?EventTranslation $eventTranslation = null;

    public function mount(string $slug)
    {
        $this->eventTranslation = EventTranslation::whereSlug($slug)->firstOrFail();
    }

    public function render()
    {
        return view('livewire.event');
    }
}
