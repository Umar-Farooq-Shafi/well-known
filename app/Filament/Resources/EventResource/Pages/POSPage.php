<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\CartElement;
use App\Models\EventDateTicket;
use Carbon\Carbon;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;

class POSPage extends Page implements HasForms
{
    use InteractsWithForms;
    use InteractsWithRecord;

    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.resources.event-resource.pages.p-o-s-page';

    protected static ?string $title = 'POS';

    public array $data = [];

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->hasRole('ROLE_POINTOFSALE');
    }

    public function form(Form $form): Form
    {
        $schema = [];
        $eventDateTickets = [];
        $country = auth()->user()->scanner->organizer->country;

        $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country->code);

        foreach ($this->record->eventDates as $eventDate) {
            if ($eventDate->pointOfSales()->where('user_id', auth()->id())->exists()) {
                $label = $eventDate->startdate;

                if ($venue = $eventDate->venue) {
                    $label .= " " . $venue->name . ": " . $venue->stringifyAddress;
                } else {
                    $label .= " Online";
                }

                foreach ($eventDate->eventDateTickets as $eventDateTicket) {
                    if ($eventDateTicket->active) {
                        $eventDateTickets[] = $eventDateTicket;
                    }
                }

                $schema[] = Forms\Components\Section::make($label)
                    ->schema(function () use ($eventDate) {
                        $form = [];

                        foreach ($eventDate->eventDateTickets as $eventDateTicket) {
                            if ($eventDateTicket->active) {
                                $form[] = Forms\Components\Section::make($eventDateTicket->name)
                                    ->schema([
                                        Forms\Components\TextInput::make('reference-' . $eventDateTicket->id)
                                            ->label(function () use ($eventDateTicket) {
                                                if ($eventDateTicket->free) {
                                                    return 'Free';
                                                }

                                                $currency = $eventDateTicket->currency?->ccy ?? '';

                                                return $currency . ' ' . $eventDateTicket->getSalePrice();
                                            })
                                            ->numeric()
//                                            ->disabled(!$eventDateTicket->isOnSale())
                                            ->helperText(function () use ($eventDateTicket) {
                                                return "Tickets left: " . $eventDateTicket->getTicketsLeftCount(
                                                    ) . ' / ' . $eventDateTicket->quantity;
                                            })
                                            ->required(),
                                    ]);
                            }
                        }

                        return $form;
                    });
            }
        }

        return $form
            ->statePath('data')
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Order Information')
                        ->schema($schema),

                    Forms\Components\Wizard\Step::make('Order Summary')
                        ->view(
                            'filament.resources.event-resource.pages.order-summary',
                            [
                                'data' => $this->data,
                                'eventDateTickets' => $eventDateTickets,
                                'timezone' => $timezone[0]
                            ],
                        )
                        ->schema([
                            Forms\Components\Section::make('Optional attendee information')
                                ->schema([
                                    Forms\Components\TextInput::make('firstname')
                                        ->label('First name'),

                                    Forms\Components\TextInput::make('lastname')
                                        ->label('Last name'),
                                ]),
                        ]),
                ])
                    ->submitAction(
                        new HtmlString(
                            Blade::render(
                                <<<BLADE
                                    <x-filament::button
                                        type="submit"
                                        size="sm"
                                    >
                                        Submit
                                    </x-filament::button>
                                    BLADE,
                            ),
                        ),
                    ),
            ]);
    }

    /**
     * @throws ValidationException
     */
    public function submit(): void
    {
        $this->validate();

        $data = $this->data;

        $chosenEventDate = $data['event-date-pick'] ?? null;
        unset($data['event-date-pick']);

        foreach ($data as $ticketReference => $ticketQuantity) {
            if (!empty($ticketQuantity) && intval($ticketQuantity) > 0) {
                $eventTicket = EventDateTicket::where('reference', $ticketReference)->first();

                if (!$eventTicket) {
                    Notification::make()
                        ->title('The event ticket cannot be found')
                        ->danger()
                        ->send();

                    return;
                }

                if (!$eventTicket->isOnSale()) {
                    Notification::make()
                        ->title($eventTicket->stringifyStatus())
                        ->danger()
                        ->send();


                    return;
                }

                $cartElement = new CartElement();
                $cartElement->user_id = Auth::id();
                $cartElement->eventticket_id = $eventTicket->id;
                $cartElement->quantity = intval($ticketQuantity);

                if ($chosenEventDate) {
                    $cartElement->chosen_event_date = Carbon::createFromFormat('Y-m-d', $chosenEventDate);
                }

                $cartElement->ticket_fee = 0;

//                                if (Auth::user()->hasRole('ROLE_ATTENDEE') && !$eventTicket->isFree()) {
////                                    $cartElement->ticket_fee = $services->getSetting('ticket_fee_online');
//                                } elseif (Auth::user()->hasRole('ROLE_POINTOFSALE') && !$eventTicket->isFree()) {
//                                }

                $cartElement->save();

                Notification::make()
                    ->title('The tickets has been successfully added to your cart')
                    ->success()
                    ->send();
            }
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('toggle-fullscreen')
                ->action(function ($livewire) {
                    $livewire->dispatch('toggleFullscreen');
                }),
        ];
    }

}
