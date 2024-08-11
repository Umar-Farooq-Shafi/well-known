<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class ConcertMusic extends Component
{
    #[Title("Concert/Music | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.concert-music');
    }
}
