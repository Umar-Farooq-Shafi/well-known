<?php

namespace App\Feeds;

use App\Models\Event as EventModel;

class Event
{
    public static function getFeedItems()
    {
        return EventModel::all();
    }
}
