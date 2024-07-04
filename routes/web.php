<?php

use App\Livewire\Event;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::get('/event/{slug}', Event::class)->name('event');
