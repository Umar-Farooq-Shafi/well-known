<?php

use App\Http\Controllers\ESewaController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PrintTicket;
use App\Livewire\AddReview;
use App\Livewire\AllCategories;
use App\Livewire\Blog;
use App\Livewire\BlogArticle;
use App\Livewire\Cart;
use App\Livewire\Checkout;
use App\Livewire\ConcertMusic;
use App\Livewire\Event;
use App\Livewire\Events;
use App\Livewire\HelpCenter;
use App\Livewire\HelpCenterArticle;
use App\Livewire\HelpCenterCategory;
use App\Livewire\Home;
use App\Livewire\Movies;
use App\Livewire\OrderTransaction;
use App\Livewire\OrganizerProfile;
use App\Livewire\Pages;
use App\Livewire\ToursAndAdventure;
use App\Livewire\Venue;
use App\Livewire\Venues;
use App\Livewire\WorkshopTraining;
use Eluceo\iCal\Domain\Entity\Event as IEvent;
use Eluceo\iCal\Domain\Entity\Calendar;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Date;
use Eluceo\iCal\Domain\ValueObject\SingleDay;
use Eluceo\iCal\Presentation\Factory\CalendarFactory;
use Illuminate\Support\Facades\Route;

Route::feeds();
Route::impersonate();

Route::redirect('/login', '/admin/login')->name('login');

Route::get('/', Home::class)->name('home');

Route::get('/event/{slug}', Event::class)->name('event');

Route::get('/event/{slug}/checkout', Checkout::class)->name('event-checkout');

Route::get('/events', Events::class)->name('events');

Route::get('/organizer/{slug}', OrganizerProfile::class)->name('organizer-profile');

Route::get('/events/concert-music', ConcertMusic::class)->name('concert-music');

Route::get('/events/tours-and-adventures', ToursAndAdventure::class)->name('tours-and-adventure');

Route::get('/events/movies', Movies::class)->name('movies');

Route::get('/events/workshop-training', WorkshopTraining::class)->name('workshop-training');

Route::get('/events/categories', AllCategories::class)->name('all-categories');

Route::get('/help-center', HelpCenter::class)->name('help-center');

Route::get('/help-center/{slug}', HelpCenterCategory::class)->name('help-center-category');

Route::get('/help-center/{slug}/article', HelpCenterArticle::class)->name('help-center-article');

Route::prefix('page')->group(function () {
    Route::get('/about-us', Pages\AboutUs::class)->name('about-us');

    Route::get('/contact-us', Pages\ContactUs::class)->name('contact-us');

    Route::get('/payment-delivery-and-return', Pages\PaymentDeliveryAndReturn::class)->name('payment-delivery-and-return');

    Route::get('/terms-of-service', Pages\TermsOfService::class)->name('terms-of-service');

    Route::get('/privacy-policy', Pages\PrivancyPolicy::class)->name('privacy-policy');

    Route::get('/sell-tickets', Pages\SellTickets::class)->name('sell-tickets');

    Route::get('/ed-up', Pages\Ed::class)->name('ed');
});

Route::get('/blog', Blog::class)->name('blog');

Route::get('/blog-article/{slug}', BlogArticle::class)->name('blog-article');

Route::get('/order/print-ticket/{record}', PrintTicket::class)->name('print-ticket');

Route::get('/venues', Venues::class)->name('venues');

Route::get('/venue/{slug}', Venue::class)->name('venue');

Route::middleware('auth')->group(function () {
    Route::get('/event/my-reviews/{slug}/add', AddReview::class)->name('add-review');
});

Route::get('/cart', Cart::class)->name('cart');

Route::get('/calendar.ics', function () {
    $event = new IEvent();

    $location = new Location(request('location'));

    $event->setSummary(request('title'))
        ->setDescription(request('description'))
        ->setLocation($location)
        ->setOccurrence(
            new SingleDay(
                new Date(
                    DateTimeImmutable::createFromFormat('Y-m-d', request('start') ?? now())
                )
            )
        );

    $calendar = new Calendar([$event]);

    $componentFactory = new CalendarFactory();
    $calendarComponent = $componentFactory->createCalendar($calendar);

    header('Content-Type: text/calendar; charset=utf-8');
    header('Content-Disposition: attachment; filename="cal.ics"');

    echo $calendarComponent;
    exit;
})->name('calendar.ics');

Route::get('success-transaction', [PayPalController::class, 'successTransaction'])->name('successTransaction');
Route::get('cancel-transaction', [PayPalController::class, 'cancelTransaction'])->name('cancelTransaction');

Route::get('esewa-failure', [ESewaController::class, 'esewaError'])->name('esewa.failure');
Route::get('esewa-success', [ESewaController::class, 'esewaSuccess'])->name('esewa.success');
