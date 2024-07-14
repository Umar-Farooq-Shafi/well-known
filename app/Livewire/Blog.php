<?php

namespace App\Livewire;

use App\Models\BlogPostTranslation;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    public $name;

    #[Title("Blog | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.blog', [
            'blogs' => BlogPostTranslation::with('blogPost')
                ->where('locale', app()->getLocale())
                ->when(
                    $this->name,
                    function ($query) {
                        return $query->where('name', 'like', '%' . $this->name . '%');
                    }
                )
                ->paginate(16)
        ]);
    }
}
