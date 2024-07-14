<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class HelpCenter extends Component
{

    #[Title("Help Center | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.help-center');
    }
}
