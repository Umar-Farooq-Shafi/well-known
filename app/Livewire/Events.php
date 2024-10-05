<?php

namespace App\Livewire;

use App\Models\CategoryTranslation;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Events extends Component
{
    use WireUiActions;

    public $categoryTrans;

    #[Url]
    public ?string $category = null;

    #[Url]
    public ?string $type = null;

    #[Url]
    public ?string $message = null;

    public function mount()
    {
        if ($this->type && $this->message) {
            $this->notification()->send([
                'icon' => $this->type,
                'title' => $this->message
            ]);
        }

        $this->categoryTrans = CategoryTranslation::whereName($this->category)->first();
    }

    #[Title("Events | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.events');
    }
}
