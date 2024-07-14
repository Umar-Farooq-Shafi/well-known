<?php

use App\Livewire\AllCategories;
use App\Livewire\Blog;
use App\Livewire\BlogArticle;
use App\Livewire\ConcertMusic;
use App\Livewire\Event;
use App\Livewire\Events;
use App\Livewire\HelpCenter;
use App\Livewire\Home;
use App\Livewire\Movies;
use App\Livewire\OrganizerProfile;
use App\Livewire\ToursAndAdventure;
use App\Livewire\WorkshopTraining;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::get('/event/{slug}', Event::class)->name('event');

Route::get('/events', Events::class)->name('events');

Route::get('/organizer/{slug}', OrganizerProfile::class)->name('organizer-profile');

Route::get('/events/concert-music', ConcertMusic::class)->name('concert-music');

Route::get('/events/tours-and-adventures', ToursAndAdventure::class)->name('tours-and-adventure');

Route::get('/events/movies', Movies::class)->name('movies');

Route::get('/events/workshop-training', WorkshopTraining::class)->name('workshop-training');

Route::get('/events/categories', AllCategories::class)->name('all-categories');

Route::get('/help-center', HelpCenter::class)->name('help-center');

Route::prefix('page')->group(function () {
    Route::get('/about-us', App\Livewire\Pages\AboutUs::class)->name('about-us');
});

Route::get('/blog', Blog::class)->name('blog');

Route::get('/blog-article/{slug}', BlogArticle::class)->name('blog-article');
