<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Support\Facades\App;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Events extends Component
{
    use WithPagination;

    public $day = '';

    public $query = '';

    public $category = '';

    public function search()
    {
        $this->resetPage();
    }

    #[Title("Events | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.events', [
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
