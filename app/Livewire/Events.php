<?php

namespace App\Livewire;

use App\Models\CategoryTranslation;
use Livewire\Attributes\Title;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Events extends Component
{
    use WireUiActions;

    public $categoryTrans;

    public function mount(?string $category = null, ?string $type = null, ?string $message = null)
    {
        if ($type && $message) {
            $this->notification()->send([
                'icon' => $type,
                'title' => $message
            ]);
        }

        $this->categoryTrans = CategoryTranslation::whereName($category)->first();
    }

    #[Title("Events | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.events');
    }
}
