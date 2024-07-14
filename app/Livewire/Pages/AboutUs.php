<?php

namespace App\Livewire\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;

class AboutUs extends Component
{
    #[Title("About Us | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.pages.about-us');
    }
}
