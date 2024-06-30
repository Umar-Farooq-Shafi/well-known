<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class MailServerPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-envelope';

    protected static string $view = 'filament.pages.mail-server-page';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Mail Server';

    protected static ?int $navigationSort = 4;

    public array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'mail_server_transport' => Setting::query()->where('key', 'mail_server_transport')->first()?->value,
            'mail_server_host' => Setting::query()->where('key', 'mail_server_host')->first()?->value,
            'mail_server_port' => Setting::query()->where('key', 'mail_server_port')->first()?->value,
            'mail_server_encryption' => Setting::query()->where('key', 'mail_server_encryption')->first()?->value,
            'mail_server_username' => Setting::query()->where('key', 'mail_server_username')->first()?->value,
            'mail_server_password' => Setting::query()->where('key', 'mail_server_password')->first()?->value,
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Radio::make('mail_server_transport')
                    ->required()
                    ->label('Transport')
                    ->options([
                        'smtp' => 'SMTP',
                        'gmail' => 'Gmail',
                        'sendmail' => 'Sendmail',
                    ]),

                Forms\Components\TextInput::make('mail_server_host')
                    ->label('Host')
                    ->required(),

                Forms\Components\TextInput::make('mail_server_port')
                    ->label('Port')
                    ->integer(),

                Forms\Components\Radio::make('mail_server_encryption')
                    ->label('Encryption')
                    ->options([
                        'none' => 'None',
                        'ssl' => 'Ssl',
                        'tls' => 'TLS',
                    ])
                    ->required(),

                Forms\Components\TextInput::make('mail_server_username')
                    ->label('Username'),

                Forms\Components\TextInput::make('mail_server_password')
                    ->label('Password'),
            ]);
    }

    public function submit(): void
    {
        $this->validate();

        Setting::query()->where('key', 'mail_server_transport')
            ->update([
                'value' => $this->data['mail_server_transport']
            ]);

        Setting::query()->where('key', 'mail_server_host')
            ->update([
                'value' => $this->data['mail_server_host']
            ]);

        Setting::query()->where('key', 'mail_server_port')
            ->update([
                'value' => $this->data['mail_server_port']
            ]);

        Setting::query()->where('key', 'mail_server_encryption')
            ->update([
                'value' => $this->data['mail_server_encryption']
            ]);

        Setting::query()->where('key', 'mail_server_username')
            ->update([
                'value' => $this->data['mail_server_username']
            ]);

        Setting::query()->where('key', 'mail_server_password')
            ->update([
                'value' => $this->data['mail_server_password']
            ]);

        Notification::make()
            ->title('Saved')
            ->success()
            ->icon('heroicon-o-check-circle')
            ->send();
    }

}
