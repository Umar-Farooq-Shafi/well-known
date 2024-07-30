<?php

namespace App\Livewire;

use App\Models\EventTranslation;
use Livewire\Component;

class AddReview extends Component
{
    public ?EventTranslation $eventTranslation = null;

    public $event;

    public function mount(string $slug)
    {
        $this->eventTranslation = EventTranslation::whereSlug($slug)->firstOrFail();

        $this->event = $this->eventTranslation->event;
    }

    public function render()
    {
        return view('livewire.add-review');
    }
}
