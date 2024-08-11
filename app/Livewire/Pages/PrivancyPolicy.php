<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class PrivancyPolicy extends Component
{
    #[Title("Privacy Policy | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.pages.privancy-policy');
    }
}
