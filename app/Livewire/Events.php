<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class Events extends Component
{
    public $day;

    #[Title("Events | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.events');
    }
}
