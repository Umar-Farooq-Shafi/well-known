<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Actions\Action;
use Filament\Infolists;
use Filament\Actions\DeleteAction;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make(function () {
                    $state = $this->record->status;

                    return match ($state) {
                        1 => 'Paid',
                        0 => 'Awaiting payment',
                        -1 => 'Cancel',
                        default => $state
                    };
                })
                ->description(function () {
                    $eventDate = $this->record?->orderElement?->eventDateTicket;


                    return "Order #" . $this->record->reference . " placed on " . $eventDate?->sakesstartdate;
                })
                ->schema([
                    Infolists\Components\Section::make('Payment')
                        ->schema([
                            Infolists\Components\TextEntry::make('orderElement.chosen_event_date')
                                ->label('Captured')
                        ])
                        ->columns(2)
                ])
                ->columns(2)
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('cancel')
                ->icon('heroicon-o-x-mark'),

            DeleteAction::make()->icon('heroicon-o-trash'),

            Action::make('resend-confirmation-email')
                ->icon('heroicon-o-envelope'),

            Action::make('payment-details')
                ->icon('heroicon-o-clipboard-document-list'),

            Action::make('print-tickets')
                ->icon('heroicon-o-printer'),
        ];
    }
}
