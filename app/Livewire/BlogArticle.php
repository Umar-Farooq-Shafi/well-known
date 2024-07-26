<?php

namespace App\Livewire;

use App\Models\BlogPost;
use App\Models\BlogPostCategory;
use App\Models\BlogPostTranslation;
use Illuminate\Support\Facades\App;
use Livewire\Component;

class BlogArticle extends Component
{
    public $blogPostTranslation;

    public $blogPost;

    public $keyword;

    public function mount(string $slug)
    {
        $this->blogPostTranslation = BlogPostTranslation::whereSlug($slug)->firstOrFail();
        $this->blogPost = $this->blogPostTranslation->blogPost;

        $this->blogPost->update(['views' => $this->blogPost->views + 1]);
    }

    public function search()
    {

    }

    public function render()
    {
        $blogPostCategories = BlogPostCategory::limit(5)
            ->with([
                'blogPostCategoryTranslations' => function ($query) {
                    $query->where('locale', App::getLocale());
                }
            ])
            ->get();

        $popularBlogPosts = BlogPost::whereHas('blogPostTranslations', function ($query) {
            $query->where('slug', '!=',  $this->blogPostTranslation->slug)
                ->where('locale', app()->getLocale());
        })
            ->with([
                'blogPostTranslations' => static function ($query) {
                    $query->where('locale', app()->getLocale());
                }
            ])
            ->orderBy('views', 'desc')
            ->take(4)
            ->get();

        $similarBlogPosts = BlogPost::whereHas('blogPostCategory', function ($query) {
            $query->whereHas('blogPostCategoryTranslations', function ($query) {
                $query->where('slug', $this->blogPost->blogPostCategory->slug);
            });
        })
            ->where('id', '!=', $this->blogPost->id)
            ->take(8)
            ->get();

        return view('livewire.blog-article', [
            'blogPost' => $this->blogPost,
            'blogPostTranslation' => $this->blogPostTranslation,
            'blogPostCategories' => $blogPostCategories,
            'popularBlogPosts' => $popularBlogPosts,
            'similarBlogPosts' => $similarBlogPosts,
        ])
            ->title($this->blogPost->name . " | 'Aafno Ticket Nepal'");
    }
}
