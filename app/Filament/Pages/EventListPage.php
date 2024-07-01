<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Pages\Page;

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
            ]);
    }

}
