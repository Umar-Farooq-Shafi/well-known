<?php

use App\Http\Controllers\API\EventsController;
use Illuminate\Support\Facades\Route;


Route::get('/events/categories', [EventsController::class, 'categories'])->name('api.events.categories');

Route::get('/events/country', [EventsController::class, 'country'])->name('api.events.country');
