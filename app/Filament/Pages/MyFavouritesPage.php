<?php

namespace App\Filament\Pages;

use App\Models\Event;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\App;

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

    public function eventFavourite($id)
    {
        $event = Event::find($id);

        if ($event->favourites()->where('user_id', auth()->id())->exists()) {
            $event->favourites()->detach(auth()->id());
        } else {
            $event->favourites()->attach(auth()->id());
        }

        Notification::make()
            ->title('Saved!')
            ->success()
            ->send();
    }

     protected function getViewData(): array
     {
         return array_merge(
             parent::getViewData(),
             [
                 'events' => Event::query()
                     ->whereHas(
                         'favourites',
                         fn ($query) => $query->where('User_id', auth()->id())
                     )
                     ->with([
                         'category.categoryTranslations' => function ($query) {
                             $query->where('locale', App::getLocale());
                         }
                     ])
                     ->paginate(16)
             ]
         );
     }
}
