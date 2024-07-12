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

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.components.events.index', [
            'events' => Event::with([
                'eventTranslations' => function ($query) {
                    $query->where('name', 'LIKE', '%' . $this->query . '%')
                        ->where('locale', App::getLocale());
                },
                'category' => function ($query) {
                    $query->with([
                        'categoryTranslations' => function ($query) {
                            $query->where('name', 'LIKE', '%' . $this->category . '%')
                                ->where('locale', App::getLocale());
                        }
                    ]);
                }
            ])->paginate(16),
        ]);
    }
}
