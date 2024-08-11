<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class SellTickets extends Component
{
    #[Title("Sell Tickets | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.pages.sell-tickets');
    }
}
