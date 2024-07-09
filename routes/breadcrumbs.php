<?php

use Illuminate\Http\Request;
use WireUi\Breadcrumbs\Breadcrumbs;
use WireUi\Breadcrumbs\Trail;

Breadcrumbs::for('events')
    ->push('Users')
    ->push('View')
    ->callback(function (Trail $trail, Request $request): Trail {
        return $trail->push('Final');
    });
