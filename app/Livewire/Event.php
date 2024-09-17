<?php

namespace App\Livewire;

use App\Models\CartElement;
use App\Models\EventDate;
use App\Models\EventDateTicket;
use App\Models\EventTranslation;
use App\Models\Organizer;
use App\Traits\RatingTrait;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Event extends Component
{
    use WireUiActions;
    use RatingTrait;

    public $eventDatePick;

    public $quantity = [];

    public $ccy;

    public $promoCode;

    public ?EventTranslation $eventTranslation = null;

    public function mount(string $slug)
    {
        $this->eventTranslation = EventTranslation::whereSlug($slug)->firstOrFail();

        $isRecurrent = EventDate::whereEventId($this->eventTranslation->translatable_id)
            ->where('recurrent', true)
            ->exists();

        if (!$isRecurrent) {
            $this->eventDatePick = EventDate::whereEventId($this->eventTranslation->translatable_id)
                ->first()->startdate;
        }

        $promotion = null;

        foreach ($this->eventTranslation->event->promotions as $eventPromotion) {
            $now = now()->timezone($eventPromotion->timezone);

            if ($eventPromotion->start_date->greaterThanOrEqualTo($now) && $eventPromotion->end_date->lessThanOrEqualTo($now)) {
                $promotion = $eventPromotion;
            }
        }
    }

    public function promoApply()
    {
        if ($this->promoCode === null || $this->promoCode === '') {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Promo code cannot be blank.',
            ]);
            return;
        }

        $event = $this->eventTranslation->event;

        $coupon = $event->coupons()
            ->whereDate('expire_date', '>', now())
            ->first();

        if ($coupon && $coupon->code !== $this->promoCode) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Promo code is invalid.',
            ]);
            return;
        }


    }

    public function updatedQuantity($value, $key)
    {
        if ($key) {
            $this->ccy = EventDateTicket::find($key)?->currency?->ccy;
        }

        if ($value === '0') {
            $this->reset('ccy');
        }
    }

    public function buyTicket()
    {
        if (empty($this->eventDatePick)) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Please select an event date before proceeding.',
            ]);
        } else {
            $this->dispatch('openModal');
        }
    }

    public function submit($eventDate)
    {
        if (count($this->quantity) === 0) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Please select a ticket!',
            ]);
        } else {
            foreach ($this->quantity as $ticketId => $quantity) {
                $ticket = EventDateTicket::find($ticketId);

                CartElement::create([
                    'user_id' => auth()->id(),
                    'eventticket_id' => $ticketId,
                    'quantity' => $quantity,
                    'ticket_fee' => $ticket->ticket_fee,
                    'chosen_event_date' => $this->eventDatePick,
                ]);
            }

            $this->redirectRoute('event-checkout');
        }
    }

    public function followOrganization(): void
    {
        Organizer::find($this->eventTranslation->event->organizer_id)
            ->followings()->attach(auth()->id());

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Saved!',
        ]);
    }

    public function render()
    {
        $reviews = $this->eventTranslation->event->reviews;

        $averageRating = $this->calculateAverageRating();
        $ratingPercentages = $this->calculateRatingPercentages();

        return view('livewire.event', [
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'ratingPercentages' => $ratingPercentages,
        ]);
    }
}
