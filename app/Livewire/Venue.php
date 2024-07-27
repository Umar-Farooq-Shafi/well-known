<?php

namespace App\Livewire;

use App\Models\VenueTranslation;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Venue extends Component
{
    public $venue;

    public $venueTranslation;

    #[Validate('required|email')]
    public $email;

    #[Validate('required')]
    public $phone;

    #[Validate('required')]
    public $guests;

    #[Validate('required')]
    public $note;

    public function mount(string $slug)
    {
        $this->venueTranslation = VenueTranslation::where('slug', $slug)->firstOrFail();

        $this->venue = $this->venueTranslation->venue;
    }

    /**
     * @return void
     */
    public function submit()
    {
        $this->validate();
    }

    public function render()
    {
        return view('livewire.venue')
            ->title($this->venueTranslation->name . " | 'Aafno Ticket Nepal'");
    }
}
