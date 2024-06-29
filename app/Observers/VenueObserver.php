<?php

namespace App\Observers;

use App\Models\Venue;

class VenueObserver
{
    public function creating(Venue $venue): void
    {
        $venue->hidden = false;
        $venue->listedondirectory = true;
    }
}
