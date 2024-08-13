<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Exports\OrderTicketExporter;
use App\Filament\Resources\EventResource;
use App\Models\OrderTicket;
use App\Models\User;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Filament\Tables;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;

class AttendeeCheckInPage extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithRecord;

    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.resources.event-resource.pages.attendee-check-in-page';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                OrderTicket::query()
                    ->whereHas(
                        'eventDateTicket.eventDate',
                        fn(Builder $query) => $query->where(
                            'reference',
                            $this->record->eventDates()->first()->reference,
                        ),
                    )
                    ->orderByDesc('created_at'),
            )
            ->columns([
                Tables\Columns\TextColumn::make('reference')
                    ->label('#')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('orderElement.order.payment.firstname')
                    ->label('Attendee First Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('orderElement.order.payment.lastname')
                    ->label('Attendee Last Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('orderElement.order.payment.client_email')
                    ->label('Attendee Email')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('orderElement.eventDateTicket.name')
                    ->label('Ticket Type')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Bought on')
                    ->date(),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(OrderTicketExporter::class)
                    ->color('primary')
                    ->icon('heroicon-o-arrow-down-tray'),

                Tables\Actions\Action::make('reset')
                    ->label('Reset')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->form([
                        Forms\Components\TextInput::make('username')
                            ->label(__('Username'))
                            ->required()
                            ->autocomplete()
                            ->autofocus()
                            ->extraInputAttributes(['tabindex' => 1]),

                        Forms\Components\TextInput::make('password')
                            ->label(__('filament-panels::pages/auth/login.form.password.label'))
                            ->hint(
                                filament()->hasPasswordReset() ? new HtmlString(
                                    Blade::render(
                                        '<x-filament::link :href="filament()->getRequestPasswordResetUrl()"> {{ __(\'filament-panels::pages/auth/login.actions.request_password_reset.label\') }}</x-filament::link>',
                                    ),
                                ) : null,
                            )
                            ->password()
                            ->revealable(filament()->arePasswordsRevealable())
                            ->autocomplete('current-password')
                            ->required()
                            ->extraInputAttributes(['tabindex' => 2]),
                    ])
                    ->action(function (array $data, Tables\Actions\Action $component) {
                        $user = User::whereUsername($data['username'])->first();

                        if (!$user || $user->id === auth()->id() || !Hash::check($data['password'], $user->password)) {
                            Notification::make()
                                ->title('Credential does not match')
                                ->danger()
                                ->send();

                            $component->halt();
                        } else {
                            OrderTicket::query()
                                ->whereHas(
                                    'eventDateTicket.eventDate',
                                    fn(Builder $query) => $query->where(
                                        'reference',
                                        $this->record->eventDates()->first()->reference,
                                    ),
                                )
                                ->update([
                                    'scanned' => false,
                                ]);

                            Notification::make()
                                ->title('Reset successfully')
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('id')
                    ->label('Checked In')
                    ->button()
                    ->icon(fn($record) => $record->scanned ? 'heroicon-o-check' : '')
                    ->tooltip(function ($record) {
                        if ($record->scanned) {
                            return "Checked In At " . $record->created_at;
                        }

                        return null;
                    })
                    ->action(function ($record) {
                        if (!$record->scanned) {
                            $record->update(['scanned' => true]);

                            Notification::make()
                                ->title('Ticket has successfully scanned')
                                ->success()
                                ->send();
                        }
                    }),
            ]);
    }

    protected function getHeaderWidgets(): array
    {
        return [
            EventResource\Widgets\AttendeeCheckInOverview::class,
            EventResource\Widgets\AttendeeCheckInChart::class,
        ];
    }

}
