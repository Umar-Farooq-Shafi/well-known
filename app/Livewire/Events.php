<?php

namespace App\Livewire;

use App\Models\CategoryTranslation;
use Livewire\Attributes\Title;
use Livewire\Component;

class Events extends Component
{
    public $categoryTrans;

    public function mount(?string $category = null)
    {
        $this->categoryTrans = CategoryTranslation::whereName($category)->first();
    }

    #[Title("Events | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.events');
    }
}
