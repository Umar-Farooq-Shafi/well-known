<?php

namespace App\Livewire;

use App\Models\CartElement;
use App\Models\EventDate;
use App\Models\EventDateTicket;
use App\Models\EventTranslation;
use App\Models\Organizer;
use App\Models\User;
use App\Traits\RatingTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Event extends Component
{
    use WireUiActions;
    use RatingTrait;

    public $eventDatePick;

    public $quantity = [];

    public $promotions = [];

    public $ccy;

    public $promoCode;

    public $couponType;

    public $couponDiscount;

    public $allowedDates = [];

    public $totalEventDates = 0;

    public ?EventTranslation $eventTranslation = null;

    public function mount(string $slug)
    {
        $this->eventTranslation = EventTranslation::whereSlug($slug)->firstOrFail();

        $isRecurrent = EventDate::whereEventId($this->eventTranslation->translatable_id)
            ->where('recurrent', true)
            ->exists();

        $eventDates = [];

        foreach ($this->eventTranslation->event->eventDates as $eventDate) {
            $startDate = $eventDate->startdate?->timezone($this->eventTranslation->event->eventtimezone)->toDateString();
            $endDate = $eventDate->enddate?->timezone($this->eventTranslation->event->eventtimezone)->toDateString();
            $this->totalEventDates++;

            if ($startDate || $endDate) {
                if (!in_array($startDate, $eventDates, true)) {
                    $eventDates[] = $startDate;
                }

                if (!in_array($endDate, $eventDates, true)) {
                    $eventDates[] = $endDate;
                }
            }
        }

        if ($this->totalEventDates > 1) {
            $this->allowedDates = $eventDates;
        } else if (!$isRecurrent) {
            $this->eventDatePick = EventDate::whereEventId($this->eventTranslation->translatable_id)
                ->first()->startdate;
        }

        $promotion = null;

        foreach ($this->eventTranslation->event->promotions as $eventPromotion) {
            $now = now()->timezone($eventPromotion->timezone);

            if ($eventPromotion->start_date->lessThanOrEqualTo($now) && $eventPromotion->end_date->greaterThanOrEqualTo($now)) {
                $promotion = $eventPromotion;
            }
        }

        $this->promotions = $promotion?->promotionQuantities?->pluck('discount', 'quantity')?->toArray() ?? [];
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
            ->whereDate('expire_date', '>=', now())
            ->where('code', $this->promoCode)
            ->first();

        if (!$coupon) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Promo code is invalid.',
            ]);
            return;
        }

        $this->couponType = $coupon->type;
        $this->couponDiscount = $coupon->discount;

        $this->reset('promoCode');

        $this->notification()->send([
            'icon' => 'success',
            'title' => 'Coupon applied',
        ]);
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
            if (!auth()->check()) {
                $email = fake()->unique()->safeEmail;
                $password = fake()->password;
                $username = fake()->userName;

                User::create([
                    'username' => $username,
                    'username_canonical' => $username,
                    'email' => $email,
                    'email_canonical' => $email,
                    'enabled' => 1,
                    'password' => Hash::make($password),
                    'roles' => 'a:1:{i:0;s:13:"ROLE_ATTENDEE";}',
                    'slug' => Str::slug($username),
                ]);

                Auth::attempt([
                    'email' => $email,
                    'password' => $password
                ]);
            }

            foreach ($this->quantity as $ticketId => $quantity) {
                $ticket = EventDateTicket::find($ticketId);

                CartElement::create([
                    'user_id' => auth()->id(),
                    'eventticket_id' => $ticketId,
                    'quantity' => $quantity,
                    'ticket_fee' => $ticket->ticket_fee,
                    'code' => $this->promoCode,
                    'chosen_event_date' => $this->eventDatePick,
                ]);
            }

            $this->reset('eventDatePick', 'quantity');

            $this->redirectRoute('event-checkout', ['slug' => $this->eventTranslation->slug]);
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
