<?php

namespace App\Livewire;

use Livewire\Component;

class Map extends Component
{
    public $lat;

    public $lng;

    public function mount($lat, $lng)
    {
        $this->lat = $lat;
        $this->lng = $lng;
    }

    public function render()
    {
        return view('livewire.map');
    }
}
