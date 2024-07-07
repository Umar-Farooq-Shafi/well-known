<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Forms;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class ViewStatsPage extends Page implements HasForms
{
    use InteractsWithRecord;
    use InteractsWithForms;

    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.resources.event-resource.pages.view-stats-page';

    public array $data = [];

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $venue = $this->record->eventDates()->first()->venue;
        $scanners = [];

        foreach ($this->record->eventDates as $eventDate) {
            foreach ($eventDate->scanners as $scanner) {
                $scanners[] = $scanner->name;
            }
        }

        $this->form->fill([
            'reference' => $this->record->reference,
            'venue' => $venue->venueTranslations()->first()?->name,
            'address' => $venue->street . " " . $venue->street2 . " " . $venue->city . " " . $venue->state,
            'start_date' => $this->record->eventDates()->first()->startdate,
            'end_date' => $this->record->eventDates()->first()->enddate,
            'scanners' => implode(', ', $scanners)
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Section::make('Details')
                    ->schema([
                        Forms\Components\TextInput::make('reference'),

                        Forms\Components\TextInput::make('venue'),

                        Forms\Components\TextInput::make('address'),

                        Forms\Components\TextInput::make('start_date'),

                        Forms\Components\TextInput::make('end_date'),

                        Forms\Components\TextInput::make('scanners'),
                    ])
                    ->collapsible()
                    ->collapsed()
            ]);
    }

}
