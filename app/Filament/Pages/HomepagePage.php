<?php

namespace App\Filament\Pages;

use App\Models\HomepageHeroSetting;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Forms;
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
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();


        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
