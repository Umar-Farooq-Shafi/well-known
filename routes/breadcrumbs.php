<?php

use WireUi\Breadcrumbs\Breadcrumbs;
use WireUi\Breadcrumbs\Trail;

Breadcrumbs::for('events')
    ->push('Events');

Breadcrumbs::for('event')
    ->push('Events', route('events'))
    ->push('Event Detail');

Breadcrumbs::for('event-checkout')
    ->callback(function ($slug) {
        return Trail::make()
            ->push('Events', route('events'))
            ->push('Event Detail', route('event', ['slug' => $slug]))
            ->push('Checkout');
    });

Breadcrumbs::for('concert-music')
    ->push('Events', route('events'))
    ->push('Concerts');

Breadcrumbs::for('tours-and-adventure')
    ->push('Events', route('events'))
    ->push('Concerts');

Breadcrumbs::for('workshop-training')
    ->push('Events', route('events'))
    ->push('Concerts');

Breadcrumbs::for('all-categories')
    ->push('Events', route('events'))
    ->push('Concerts');

Breadcrumbs::for('about-us')
    ->push('About Us');

Breadcrumbs::for('blog')
    ->push('Blog');

Breadcrumbs::for('blog-article')
    ->push('Blog', route('blog'))
    ->push('Blog Article');

Breadcrumbs::for('contact-us')
    ->push('Contact Us');

Breadcrumbs::for('payment-delivery-and-return')
    ->push('Payment Delivery and Return');

Breadcrumbs::for('venues')
    ->push('Venues');

Breadcrumbs::for('venue')
    ->push('Venues', route('venues'))
    ->push('Venue Detail');

Breadcrumbs::for('add-review')
    ->push('Events', route('events'))
    ->push('Add Review');

Breadcrumbs::for('terms-of-service')
    ->push('Terms of Service');

Breadcrumbs::for('privacy-policy')
    ->push('Privacy Policy');
