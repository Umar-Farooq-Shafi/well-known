<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

class WorkshopTraining extends Component
{
    #[Title("Workshop / Training | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.workshop-training');
    }
}
