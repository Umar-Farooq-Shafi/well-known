<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class PaymentDeliveryAndReturn extends Component
{
    #[Title("Payment Delivery And Return | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.pages.payment-delivery-and-return');
    }
}
