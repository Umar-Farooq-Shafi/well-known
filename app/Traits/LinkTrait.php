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

        $staticPagesArray[route('contact-us', [], false)] = __('Contact Us');
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
            ListOrders::getUrl(isAbsolute: false) => __('Attendee tickets'),
            CreateEvent::getUrl(isAbsolute: false) => __('Create event'),
        ];
        $linksArray[__('Dashboard Pages')] = $dashboardPagesArray;

        // Add category pages urls
        $categoryPagesArray = [];

        $categories = Category::all(); // Replace with your actual method to retrieve categories
        foreach ($categories as $category) {
            $categoryPagesArray[route('events', ['category' => $category->slug], false)] = __('Category') . ' - ' . $category->name;
        }
        $linksArray[__('Event Categories')] = $categoryPagesArray;

        // Add blog post pages urls
        $blogPagesArray = [
            route('blog', [], false) => __('Blog page'),
        ];

        $blogPosts = BlogPost::all(); // Replace with your actual method to retrieve blog posts
        foreach ($blogPosts as $blogPost) {
            $blogPagesArray[route('blog-article', ['slug' => $blogPost->slug], false)] = __('Blog post') . ' - ' . $blogPost->name;
        }
        $linksArray[__('Blog Pages')] = $blogPagesArray;

        // Add event pages urls
        $eventPagesArray = [
            route('events', [], false) => __('Events page'),
        ];

        $events = Event::all(); // Replace with your actual method to retrieve events
        foreach ($events as $event) {
            $eventPagesArray[route('event', ['slug' => $event->slug], false)] = __('Event') . ' - ' . $event->name;
        }
        $linksArray[__('Events Pages')] = $eventPagesArray;

        // Add help center pages urls
        $helpCenterPagesArray = [
            route('help-center', [], false) => __('Help Center page'),
        ];

        $helpCenterCategories = HelpCenterCategory::all(); // Replace with your actual method to retrieve help center categories
        $helpCenterArticles = HelpCenterArticle::all(); // Replace with your actual method to retrieve help center articles
        foreach ($helpCenterCategories as $helpCenterCategory) {
            $helpCenterPagesArray[route('help-center-category', ['slug' => $helpCenterCategory->slug], false)] = __('Help Center Category') . ' - ' . $helpCenterCategory->name;
        }
        foreach ($helpCenterArticles as $helpCenterArticle) {
            $helpCenterPagesArray[route('help-center-article', ['slug' => $helpCenterArticle->slug], false)] = __('Help Center Article') . ' - ' . $helpCenterArticle->title;
        }
        $linksArray[__('Help Center Pages')] = $helpCenterPagesArray;

        // Add organizers pages urls
        $organizersPagesArray = [];
        $organizers = User::query()->whereHas('organizer')->get();
        foreach ($organizers as $organizer) {
            $organizersPagesArray[route('organizer-profile', ['slug' => $organizer->organizer->slug], false)] = __('Organizer Profile') . ' - ' . $organizer->organizer->name;
        }
        $linksArray[__('Organizers Pages')] = $organizersPagesArray;

        // Add venues pages urls
        $venuesPagesArray = [
            route('venues', [], false) => __('Venues page'),
        ];
        $venues = Venue::all(); // Replace with your actual method to retrieve venues
        foreach ($venues as $venue) {
            $venuesPagesArray[route('venue', ['slug' => $venue->slug], false)] = __('Venue') . ' - ' . $venue->name;
        }
        $linksArray[__('Venues Pages')] = $venuesPagesArray;

        return $linksArray;
    }

}
