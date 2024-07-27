<?php

use Illuminate\Http\Request;
use WireUi\Breadcrumbs\Breadcrumbs;
use WireUi\Breadcrumbs\Trail;

Breadcrumbs::for('events')
    ->push('Events');

Breadcrumbs::for('event')
    ->push('Events', route('events'))
    ->push('Event Detail');

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

Breadcrumbs::for('venue')
    ->push('venue');
