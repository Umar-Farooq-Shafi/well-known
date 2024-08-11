<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class Ed extends Component
{
    #[Title("Ed | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.pages.ed');
    }
}
