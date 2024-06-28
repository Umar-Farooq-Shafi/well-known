<?php

use App\Livewire\Events;
use App\Livewire\Home;
use Illuminate\Support\Facades\Route;

Route::get('/', Home::class)->name('home');

Route::get('/events', Events::class)->name('events');
