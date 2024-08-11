<?php

namespace App\Traits;

use App\Filament\Resources\EventResource\Pages\CreateEvent;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Event;
use App\Models\HelpCenterArticle;
use App\Models\HelpCenterCategory;
use App\Models\Page;
use App\Models\User;
use App\Models\Venue;

trait LinkTrait
{
    public static function getLinks(): array
    {
        $linksArray = [];

        $staticPages = Page::all();
        $staticPagesArray = [
            route('home', [], false) => __('Homepage'),
        ];

        foreach ($staticPages as $staticPage) {
            $staticPagesArray[route($staticPage->slug, [], false)] = $staticPage->title;
        }

        $staticPagesArray[route('contact-us', [], false)] = __('Contact');
        $linksArray[__('Static Pages')] = $staticPagesArray;

        // Add authentication pages urls
        $authentificationPagesArray = [
            route('filament.admin.auth.login', [], false) => __('Login'),
            route('filament.admin.auth.password-reset.request', [], false) => __('Password Resetting'),
            route('filament.admin.auth.register', [], false) => __('Attendee Registration'),
            route('filament.admin.auth.register', [], false) => __('Organizer Registration'),
        ];
        $linksArray[__('Authentication Pages')] = $authentificationPagesArray;

        // Add dashboard pages urls
        $dashboardPagesArray = [
            __('Attendee tickets') => ListOrders::getUrl(isAbsolute: false),
            __('Create event') => CreateEvent::getUrl(isAbsolute: false),
        ];
        $linksArray[__('Dashboard Pages')] = $dashboardPagesArray;

        // Add category pages urls
        $categoryPagesArray = [];

        $categories = Category::all(); // Replace with your actual method to retrieve categories
        foreach ($categories as $category) {
            $categoryPagesArray[__('Category') . ' - ' . $category->name] = route('events', ['category' => $category->slug], false);
        }
        $linksArray[__('Event Categories')] = $categoryPagesArray;

        // Add blog post pages urls
        $blogPagesArray = [
            __('Blog page') => route('blog', [], false),
        ];

        $blogPosts = BlogPost::all(); // Replace with your actual method to retrieve blog posts
        foreach ($blogPosts as $blogPost) {
            $blogPagesArray[__('Blog post') . ' - ' . $blogPost->title] = route('blog-article', ['slug' => $blogPost->slug], false);
        }
        $linksArray[__('Blog Pages')] = $blogPagesArray;

        // Add event pages urls
        $eventPagesArray = [
            __('Events page') => route('events', [], false),
        ];

        $events = Event::all(); // Replace with your actual method to retrieve events
        foreach ($events as $event) {
            $eventPagesArray[__('Event') . ' - ' . $event->name] = route('event', ['slug' => $event->slug], false);
        }
        $linksArray[__('Events Pages')] = $eventPagesArray;

        // Add help center pages urls
        $helpCenterPagesArray = [
            __('Help Center page') => route('help-center', [], false),
        ];

        $helpCenterCategories = HelpCenterCategory::all(); // Replace with your actual method to retrieve help center categories
        $helpCenterArticles = HelpCenterArticle::all(); // Replace with your actual method to retrieve help center articles
        foreach ($helpCenterCategories as $helpCenterCategory) {
            $helpCenterPagesArray[__('Help Center Category') . ' - ' . $helpCenterCategory->name] = route('help-center-category', ['slug' => $helpCenterCategory->slug], false);
        }
        foreach ($helpCenterArticles as $helpCenterArticle) {
            $helpCenterPagesArray[__('Help Center Article') . ' - ' . $helpCenterArticle->title] = route('help-center-article', ['slug' => $helpCenterArticle->slug], false);
        }
        $linksArray[__('Help Center Pages')] = $helpCenterPagesArray;

        // Add organizers pages urls
        $organizersPagesArray = [];
        $organizers = User::query()->whereHas('organizer')->get();
        foreach ($organizers as $organizer) {
            $organizersPagesArray[__('Organizer Profile') . ' - ' . $organizer->organizer->name] = route('organizer-profile', ['slug' => $organizer->organizer->slug], false);
        }
        $linksArray[__('Organizers Pages')] = $organizersPagesArray;

        // Add venues pages urls
        $venuesPagesArray = [
            __('Venues page') => route('venues', [], false),
        ];
        $venues = Venue::all(); // Replace with your actual method to retrieve venues
        foreach ($venues as $venue) {
            $venuesPagesArray[__('Venue') . ' - ' . $venue->name] = route('venue', ['slug' => $venue->slug], false);
        }
        $linksArray[__('Venues Pages')] = $venuesPagesArray;

        return $linksArray;
    }

}
