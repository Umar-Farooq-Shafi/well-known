<?php

namespace App\Livewire;

use App\Models\Category;
use Livewire\Attributes\Title;
use Livewire\Component;

class AllCategories extends Component
{

    #[Title("Event Categories | 'Aafno Ticket Nepal'")]
    public function render()
    {
        $categories = Category::with([
            'categoryTranslations' => function($query) {
                $query->where('locale', app()->getLocale());
            }
        ])->get();

        return view('livewire.all-categories', [
            'categories' => $categories
        ]);
    }
}
