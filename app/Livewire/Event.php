<?php

namespace App\Livewire;

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

    public $firstName;

    public $lastName;

    public $email;

    public $confirmEmail;

    public $phone;

    public ?EventTranslation $eventTranslation = null;

    public function mount(string $slug)
    {
        $this->eventTranslation = EventTranslation::whereSlug($slug)->firstOrFail();

        if (auth()->check()) {
            $this->firstName = auth()->user()->firstname;
            $this->lastName = auth()->user()->lastname;
            $this->email = auth()->user()->email;
            $this->confirmEmail = auth()->user()->email;
            $this->phone = auth()->user()->phone;
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

    public function submit()
    {
        if (count($this->quantity) === 0) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Please select a ticket!',
            ]);
        } else {
            $this->dispatch('openOrderModal');
        }
    }

    public function placeOrder()
    {
        if (empty($this->firstName)) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'First name is required',
            ]);
            return;
        }

        if (empty($this->lastName)) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Last name is required',
            ]);
            return;
        }

        if (empty($this->email)) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Email is required',
            ]);
            return;
        }

        if (empty($this->confirmEmail)) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Confirm email is required',
            ]);
            return;
        }

        if ($this->email !== $this->confirmEmail) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Confirm email is not same as confirmEmail',
            ]);
            return;
        }

        if (empty($this->phone)) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Phone is required',
            ]);
            return;
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
