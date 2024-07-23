<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Mail\OrderConfirmation;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Infolists;
use Filament\Forms;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Mail;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        $orderElements = [];

        foreach ($this->record->orderElements as $orderElement) {
            $orderElements[] = Infolists\Components\Section::make('Event / Ticket')
                ->columns()
                ->schema([
                    Infolists\Components\TextEntry::make('chosen_event_date')
                        ->state($orderElement->chosen_event_date)
                        ->label('Captured'),

                    Infolists\Components\TextEntry::make('name')
                        ->state($orderElement->eventDateTicket->eventDate->event->name)
                        ->label('Name'),

                    Infolists\Components\TextEntry::make('quantity')
                        ->state($orderElement->quantity)
                        ->label('Quantity'),

                    Infolists\Components\TextEntry::make('unitprice')
                        ->state($orderElement->unitprice)
                        ->label('Price'),

                    Infolists\Components\TextEntry::make('sub_total')
                        ->state($orderElement->unitprice * $orderElement->quantity)
                        ->label('Subtotal')
                ]);
        }

        return $infolist
            ->schema([
                Infolists\Components\Section::make(function () {
                    $state = $this->record->status;

                    return match ($state) {
                        1 => 'Paid',
                        0 => 'Awaiting payment',
                        -1 => 'Cancel',
                        -2 => 'Failed',
                        default => $state
                    };
                })
                    ->description(function () {
                        $eventDate = $this->record?->orderElements()?->first()->eventDateTicket;


                        return "Order #" . $this->record->reference . " placed on " . $eventDate?->sakesstartdate;
                    })
                    ->schema([
                        Infolists\Components\Section::make('Payment')
                            ->schema([
                                Infolists\Components\Section::make('Attendee / Point of Sale')
                                    ->columns()
                                    ->schema([
                                        Infolists\Components\TextEntry::make('user.full_name')
                                            ->label('Full Name'),

                                        Infolists\Components\TextEntry::make('user.email')
                                            ->label('Email'),
                                    ]),

                                ...$orderElements
                            ])
                            ->columns(2)
                    ])
                    ->columns(2)
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('paid')
                ->visible($this->record->status === 0)
                ->requiresConfirmation()
                ->action(function () {
                    $this->record->update(['status' => 1]);


                })
                ->modalDescription('You are about to change order status to PAID (this action cannot be undone)'),

            Action::make('resend-confirmation-email')
                ->visible($this->record->status === 1)
                ->requiresConfirmation()
                ->modalDescription('If you need to send the confirmation email to a different email address, you can change it before submitting')
                ->form([
                    Forms\Components\TextInput::make('email')
                        ->formatStateUsing(fn () => $this->record->user?->email)
                ])
                ->action(function ($data) {
                    Mail::to($data['email'])->send(new OrderConfirmation($this->record));

                    Notification::make()
                        ->title("The confirmation email has been resent to " . $data['email'])
                        ->success()
                        ->send();
                })
                ->icon('heroicon-o-envelope'),

            Action::make('payment-details')
                ->visible($this->record->status === 1)
                ->modalHeading(fn () => "Order payment details - " . $this->record->reference)
                ->modalContent(view('filament.resources.order-resource.payment-detail', [
                    'payment' => $this->record->payment,
                ]))
                ->modalSubmitAction('')
                ->modalCancelActionLabel('Close')
                ->icon('heroicon-o-clipboard-document-list'),

            Action::make('print-tickets')
                ->visible($this->record->status === 1)
                ->url(route('print-ticket', ['record' => $this->record->id]), true)
                ->icon('heroicon-o-printer'),

            Action::make('cancel')
                ->action(fn () => $this->record->update(['status' => -1]))
                ->icon('heroicon-o-x-mark'),

            ForceDeleteAction::make(),

            RestoreAction::make(),

            DeleteAction::make()
                ->icon('heroicon-o-trash'),
        ];
    }
}
