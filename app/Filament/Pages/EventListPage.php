<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Validation\ValidationException;

class EventListPage extends Page
{
    protected static ?string $navigationIcon = 'fas-th-list';

    protected static string $view = 'filament.pages.event-list-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Event list page';

    protected static ?int $navigationSort = 8;

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'events_per_page' => Setting::query()->where('key', 'events_per_page')->first()?->value,
            'show_map_button' => Setting::query()->where('key', 'show_map_button')->first()?->value,
            'show_calendar_button' => Setting::query()->where('key', 'show_calendar_button')->first()?->value,
            'show_rss_feed_button' => Setting::query()->where('key', 'show_rss_feed_button')->first()?->value,
            'show_category_filter' => Setting::query()->where('key', 'show_category_filter')->first()?->value,
            'show_location_filter' => Setting::query()->where('key', 'show_location_filter')->first()?->value,
            'show_date_filter' => Setting::query()->where('key', 'show_date_filter')->first()?->value,
            'show_ticket_price_filter' => Setting::query()->where('key', 'show_ticket_price_filter')->first()?->value,
            'show_audience_filter' => Setting::query()->where('key', 'show_audience_filter')->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\TextInput::make('events_per_page')
                    ->label('Number of events per page')
                    ->integer()
                    ->required(),

                Forms\Components\Radio::make('show_map_button')
                    ->label('Show map button')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),

                Forms\Components\Radio::make('show_calendar_button')
                    ->label('Show calendar button')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),

                Forms\Components\Radio::make('show_rss_feed_button')
                    ->label('Show RSS feed button')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),

                Forms\Components\Radio::make('show_category_filter')
                    ->label('Show category filter')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),

                Forms\Components\Radio::make('show_location_filter')
                    ->label('Show location filter')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),

                Forms\Components\Radio::make('show_date_filter')
                    ->label('Show date filter')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),

                Forms\Components\Radio::make('show_ticket_price_filter')
                    ->label('Show ticket price filter')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),

                Forms\Components\Radio::make('show_audience_filter')
                    ->label('Show audience filter')
                    ->options([
                        'yes' => 'Yes',
                        'no' => 'No',
                    ])
                    ->required(),
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();

        Setting::query()->where('key', 'events_per_page')
            ->update([
                'value' => $this->data['events_per_page']
            ]);

        Setting::query()->where('key', 'show_map_button')
            ->update([
                'value' => $this->data['show_map_button']
            ]);

        Setting::query()->where('key', 'show_calendar_button')
            ->update([
                'value' => $this->data['show_calendar_button']
            ]);

        Setting::query()->where('key', 'show_rss_feed_button')
            ->update([
                'value' => $this->data['show_rss_feed_button']
            ]);

        Setting::query()->where('key', 'show_category_filter')
            ->update([
                'value' => $this->data['show_category_filter']
            ]);

        Setting::query()->where('key', 'show_location_filter')
            ->update([
                'value' => $this->data['show_location_filter']
            ]);

        Setting::query()->where('key', 'show_ticket_price_filter')
            ->update([
                'value' => $this->data['show_ticket_price_filter']
            ]);

        Setting::query()->where('key', 'show_date_filter')
            ->update([
                'value' => $this->data['show_date_filter']
            ]);

        Setting::query()->where('key', 'show_audience_filter')
            ->update([
                'value' => $this->data['show_audience_filter']
            ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
