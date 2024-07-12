<?php

namespace App\Livewire\Components\Events;

use App\Models\Event;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public $day = '';

    public $query = '';

    public $category = '';

    public function mount(?int $category = null)
    {
        $this->category = $category;
    }

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.components.events.index', [
            'events' => Event::whereHas('category', function ($query) {
                $query->when($this->category, function ($query) {
                    $query->where('id', $this->category);
                });
            })
                ->with([
                    'category.categoryTranslations' => function ($query) {
                        $query->where('locale', App::getLocale());
                    }
                ])
                ->paginate(16),
        ]);
    }
}
