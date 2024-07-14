<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class Movies extends Component
{

    #[Title("Movies | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.movies');
    }
}
