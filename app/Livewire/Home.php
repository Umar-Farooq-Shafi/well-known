<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class Home extends Component
{
    #[Title("Nepal's 1st event ticketing platform. We provide complete solution regarding your event tickets from selling to door verification. | 'Aafno Ticket Nepal'")]
    public function render()
    {


        return view('livewire.home');
    }
}
