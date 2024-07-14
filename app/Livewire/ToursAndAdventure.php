<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class ToursAndAdventure extends Component
{
    #[Title("Tours And Adventure | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.tours-and-adventure');
    }
}
