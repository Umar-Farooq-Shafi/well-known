<?php

namespace App\Listeners;

use Illuminate\Support\Carbon;

class UserLoginAt
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(object $event): void
    {
        $event->user->update([
            'last_login' => Carbon::now(),
        ]);
    }
}
