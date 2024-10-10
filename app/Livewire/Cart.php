<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class Cart extends Component
{

    #[Title("Cart | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.cart');
    }
}
