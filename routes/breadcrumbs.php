<?php

use Illuminate\Http\Request;
use WireUi\Breadcrumbs\Breadcrumbs;
use WireUi\Breadcrumbs\Trail;

Breadcrumbs::for('events')
    ->push('Events');

Breadcrumbs::for('concert-music')
    ->push('Events', route('events'))
    ->push('Concerts');
