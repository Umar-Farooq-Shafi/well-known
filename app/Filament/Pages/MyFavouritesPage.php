<?php

namespace App\Filament\Pages;

use App\Models\Event;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;

class MyFavouritesPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-heart';

    protected static string $view = 'filament.pages.my-favourites-page';

    protected static ?string $navigationLabel = 'My favourites';

    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        $role = ucwords(str_replace('ROLE_', '', implode(', ', unserialize(auth()->user()->roles))));

        return str_contains($role, 'ATTENDEE');
    }

     protected function getViewData(): array
     {
         return array_merge(
             parent::getViewData(),
             [
                 'events' => Event::with([
                     'favourites' => function ($query) {
                         $query->where('User_id', auth()->id());
                     }
                 ])->paginate(16)
             ]
         );
     }
}
