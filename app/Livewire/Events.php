<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class Events extends Component
{
    #[Title("Events | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.events');
    }
}
