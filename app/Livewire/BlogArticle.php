<?php

namespace App\Livewire;

use App\Models\BlogPostTranslation;
use Livewire\Attributes\Title;
use Livewire\Component;

class BlogArticle extends Component
{
    public $blogPost;

    public function mount(string $slug)
    {
        $this->blogPost = BlogPostTranslation::whereSlug($slug)->firstOrFail();
    }

    #[Title("Blog Article | 'Aafno Ticket Nepal'")]
    public function render()
    {
        return view('livewire.blog-article');
    }
}
