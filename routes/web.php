<?php

use App\Http\Controllers\PrintTicket;
use App\Livewire\AddReview;
use App\Livewire\AllCategories;
use App\Livewire\Blog;
use App\Livewire\BlogArticle;
use App\Livewire\ConcertMusic;
use App\Livewire\Event;
use App\Livewire\Events;
use App\Livewire\HelpCenter;
use App\Livewire\Home;
use App\Livewire\Movies;
use App\Livewire\MyReviews;
use App\Livewire\OrganizerProfile;
use App\Livewire\Pages;
use App\Livewire\ToursAndAdventure;
use App\Livewire\Venue;
use App\Livewire\Venues;
use App\Livewire\WorkshopTraining;
use Illuminate\Support\Facades\Route;

Route::feeds();

Route::redirect('/login', '/admin/login')->name('login');

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
    Route::get('/about-us', Pages\AboutUs::class)->name('about-us');

    Route::get('/contact-us', Pages\ContactUs::class)->name('contact-us');

    Route::get('/payment-delivery-and-return', Pages\PaymentDeliveryAndReturn::class)->name('payment-delivery-and-return');
});

Route::get('/blog', Blog::class)->name('blog');

Route::get('/blog-article/{slug}', BlogArticle::class)->name('blog-article');

Route::get('/order/print-ticket/{record}', PrintTicket::class)->name('print-ticket');

Route::get('/venues', Venues::class)->name('venues');

Route::get('/venue/{slug}', Venue::class)->name('venue');

Route::middleware('auth')->group(function () {
    Route::get('/event/my-reviews/{slug}/add', AddReview::class)->name('add-review');
});
