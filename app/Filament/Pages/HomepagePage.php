<?php

namespace App\Filament\Pages;

use App\Models\Event;
use App\Models\HomepageHeroSetting;
use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\App;
use Illuminate\Validation\ValidationException;

class HomepagePage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';

    protected static string $view = 'filament.pages.homepage-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Homepage';

    protected static ?int $navigationSort = 2;

    public array $data = [];

    public function mount(): void
    {
        $homepage = HomepageHeroSetting::query()->first();

        $this->form->fill([
            'content' => $homepage->content,
            'show_search_box' => $homepage->show_search_box,
            'homepage_events_number' => Setting::where('key', 'homepage_events_number')->first()?->value,
            'events' => Event::whereIsonhomepagesliderId($homepage->id)->pluck('id'),
            'homepage_categories_number' => Setting::where('key', 'homepage_categories_number')->first()?->value,
            'homepage_blogposts_number' => Setting::where('key', 'homepage_blogposts_number')->first()?->value,
            'homepage_featured_events_nb' => Setting::where('key', 'homepage_featured_events_nb')->first()?->value,
            'homepage_show_call_to_action' => Setting::where('key', 'homepage_show_call_to_action')->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Radio::make('content')
                    ->label('What to show in the homepage hero?')
                    ->inlineLabel()
                    ->options([
                        'none' => 'Hide slider',
                        'events' => 'Events slider',
                        'organizers' => 'Organizers slider',
                        'custom' => 'Custom hero'
                    ])
                    ->required(),

                Forms\Components\Select::make('events')
                    ->label('Events')
                    ->options(function () {
                        $events = Event::with([
                            'eventTranslations' => function ($query) {
                                $query->where('locale', App::getLocale());
                            }
                        ])->get();

                        $options = [];

                        foreach ($events as $event) {
                            $options[$event->id] = $event->eventTranslations->first()->name;
                        }

                        return $options;
                    })
                    ->multiple(),

                Forms\Components\Radio::make('show_search_box')
                    ->label('Show the search box')
                    ->required()
                    ->boolean(),

                Forms\Components\TextInput::make('homepage_events_number')
                    ->label('Number of events to show')
                    ->integer()
                    ->required(),

                Forms\Components\TextInput::make('homepage_categories_number')
                    ->label('Number of featured categories to show')
                    ->integer()
                    ->required(),

                Forms\Components\TextInput::make('homepage_blogposts_number')
                    ->label('Number of blog posts to show')
                    ->integer()
                    ->required(),

                Forms\Components\TextInput::make('homepage_featured_events_nb')
                    ->label('Number of Featured events to show')
                    ->integer()
                    ->required(),

                Forms\Components\Radio::make('homepage_show_call_to_action')
                    ->label('Show the call to action block')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required()
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();

        $homepage = HomepageHeroSetting::query()->first();

        Event::whereIn('id', $this->data['events'])->update(['isonhomepageslider_id' => $homepage->id]);

        Event::whereNotIn('id', $this->data['events'])->update(['isonhomepageslider_id' => null]);

        $homepage->update([
            'content' => $this->data['content'],
            'show_search_box' => $this->data['show_search_box'],
        ]);

        Setting::where('key', 'homepage_events_number')->update([
            'value' => $this->data['homepage_events_number']
        ]);

        Setting::where('key', 'homepage_categories_number')->update([
            'value' => $this->data['homepage_categories_number']
        ]);

        Setting::where('key', 'homepage_blogposts_number')->update([
            'value' => $this->data['homepage_blogposts_number']
        ]);

        Setting::where('key', 'homepage_featured_events_nb')->update([
            'value' => $this->data['homepage_featured_events_nb']
        ]);

        Setting::where('key', 'homepage_show_call_to_action')->update([
            'value' => $this->data['homepage_show_call_to_action']
        ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
