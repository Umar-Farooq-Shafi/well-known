<?php

namespace App\Livewire\Components;

use Laravelcm\LivewireSlideOvers\SlideOverComponent;

class Navbar extends SlideOverComponent
{
    public function render()
    {
        return view('livewire.components.navbar');
    }
}
