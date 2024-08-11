<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class TermsOfService extends Component
{
    #[Title("Terms Of Service | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.pages.terms-of-service');
    }
}
