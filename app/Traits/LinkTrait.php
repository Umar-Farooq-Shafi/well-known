<?php

namespace App\Traits;

use App\Filament\Resources\EventResource\Pages\CreateEvent;
use App\Filament\Resources\OrderResource\Pages\ListOrders;
use App\Models\BlogPost;
use App\Models\BlogPostTranslation;
use App\Models\CategoryTranslation;
use App\Models\Event;
use App\Models\EventTranslation;
use App\Models\HelpCenterArticle;
use App\Models\HelpCenterArticleTranslation;
use App\Models\HelpCenterCategory;
use App\Models\HelpCenterCategoryTranslation;
use App\Models\PageTranslation;
use App\Models\User;
use App\Models\Venue;
use App\Models\VenueTranslation;

trait LinkTrait
{
    public static function getLinks(): array
    {
        $linksArray = [];

        // Add static pages
        $staticPagesArray = [
            route('home', [], false) => __('Homepage'),
            route('contact-us', [], false) => __('Contact Us')
        ];
        foreach (
            PageTranslation::query()->where('locale', app()->getLocale())
                ->select(['slug', 'title'])->get()
            as $staticPage
        ) {
            $staticPagesArray[route($staticPage->slug, [], false)] = $staticPage->title;
        }
        $linksArray[__('Static Pages')] = $staticPagesArray;

        // Authentication pages
        $linksArray[__('Authentication Pages')] = [
            route('filament.admin.auth.login', [], false) => __('Login'),
            route('filament.admin.auth.password-reset.request', [], false) => __('Password Resetting'),
            route('filament.admin.auth.register', [], false) => __('Attendee Registration'),
            route('filament.admin.auth.register', [], false) => __('Organizer Registration'),
        ];

        // Dashboard pages
        $linksArray[__('Dashboard Pages')] = [
            ListOrders::getUrl(isAbsolute: false) => __('Attendee tickets'),
            CreateEvent::getUrl(isAbsolute: false) => __('Create event'),
        ];

        // Event categories
        $categoryPagesArray = [
            'categories_dropdown' => __('Categories Dropdown'),
            'footer_categories_section' => __('Footer Categories Section')
        ];
        foreach (
            CategoryTranslation::query()->where('locale', app()->getLocale())
                ->select(['slug', 'name'])->get()
            as $category
        ) {
            $categoryPagesArray[route('events', ['category' => $category->slug], false)] = __('Category') . ' - ' . $category->name;
        }
        $linksArray[__('Event Categories')] = $categoryPagesArray;

        // Blog posts
        $blogPagesArray = [
            route('blog', [], false) => __('Blog page'),
        ];
        foreach (
            BlogPostTranslation::query()->where('locale', app()->getLocale())
                ->select(['slug', 'name'])->get()
            as $blogPost
        ) {
            $blogPagesArray[route('blog-article', ['slug' => $blogPost->slug], false)] = __('Blog post') . ' - ' . $blogPost->name;
        }
        $linksArray[__('Blog Pages')] = $blogPagesArray;

        // Events
        $eventPagesArray = [
            route('events', [], false) => __('Events page'),
        ];
        foreach (
            EventTranslation::query()->where('locale', app()->getLocale())
                ->select(['slug', 'name'])->get()
                as $event
        ) {
            $eventPagesArray[route('event', ['slug' => $event->slug], false)] = __('Event') . ' - ' . $event->name;
        }
        $linksArray[__('Events Pages')] = $eventPagesArray;

        // Help center
        $helpCenterPagesArray = [
            route('help-center', [], false) => __('Help Center page'),
        ];
        foreach (
            HelpCenterCategoryTranslation::query()->where('locale', app()->getLocale())
                ->select(['slug', 'name'])->get()
                as $category
        ) {
            $helpCenterPagesArray[route('help-center-category', ['slug' => $category->slug], false)] = __('Help Center Category') . ' - ' . $category->name;
        }
        foreach (
            HelpCenterArticleTranslation::query()->where('locale', app()->getLocale())
                ->select(['slug', 'title'])->get()
                as $article
        ) {
            $helpCenterPagesArray[route('help-center-article', ['slug' => $article->slug], false)] = __('Help Center Article') . ' - ' . $article->title;
        }
        $linksArray[__('Help Center Pages')] = $helpCenterPagesArray;

        // Organizers
        $organizersPagesArray = [];
        foreach (User::whereHas('organizer')->with('organizer')->get() as $organizer) {
            $organizersPagesArray[route('organizer-profile', ['slug' => $organizer->organizer->slug], false)] = __('Organizer Profile') . ' - ' . $organizer->organizer->name;
        }
        $linksArray[__('Organizers Pages')] = $organizersPagesArray;

        // Venues
        $venuesPagesArray = [
            route('venues', [], false) => __('Venues page'),
        ];
        foreach (
            VenueTranslation::query()->where('locale', app()->getLocale())
                ->select(['slug', 'name'])->get()
                as $venue
        ) {
            $venuesPagesArray[route('venue', ['slug' => $venue->slug], false)] = __('Venue') . ' - ' . $venue->name;
        }
        $linksArray[__('Venues Pages')] = $venuesPagesArray;

        return $linksArray;
    }

}
