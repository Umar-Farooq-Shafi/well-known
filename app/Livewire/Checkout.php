<?php

namespace App\Livewire;

use App\Jobs\EmptyCard;
use App\Models\EventTranslation;
use App\Models\Setting;
use Livewire\Attributes\On;
use Livewire\Component;
use WireUi\Traits\WireUiActions;

class Checkout extends Component
{
    use WireUiActions;

    public $eventTranslation;

    public $eventDatePick;

    public $paymentGateways;

    public $tickets = [];

    public $firstName;

    public $lastName;

    public $email;

    public $confirmEmail;

    public $phone;

    public $paymentGateway;

    public $sessionTime;

    public function mount(string $slug): void
    {
        $this->eventTranslation = EventTranslation::whereSlug($slug)->firstOrFail();

        foreach ($this->eventTranslation->event->eventDates as $eventDate) {
            foreach ($eventDate->eventDateTickets as $ticket) {
                foreach ($ticket->cartElements as $cartElement) {
                    $this->eventDatePick = $cartElement->chosen_event_date;

                    $this->tickets[] = $ticket;
                }
            }
        }

        if (count($this->tickets) === 0) {
            abort(404);
        }

        $this->sessionTime = Setting::query()->where('key', 'checkout_timeleft')->first()?->value;

        EmptyCard::dispatch($this->tickets)->delay(now()->addSeconds($this->sessionTime));

        foreach ($this->eventTranslation->event->eventDates as $eventDate) {
            foreach ($eventDate->eventDateTickets as $eventDateTicket) {
                foreach ($eventDateTicket->paymentGateways as $paymentGateway) {
                    $found = false;

                    foreach ($this->paymentGateways ?? [] as $gt) {
                        if ($gt->id == $paymentGateway->id) {
                            $found = true;
                        }
                    }

                    if (!$found) {
                        $this->paymentGateways[] = $paymentGateway;
                    }
                }
            }
        }

        if (auth()->check()) {
            $this->firstName = auth()->user()->firstname;
            $this->lastName = auth()->user()->lastname;
            $this->email = auth()->user()->email;
            $this->confirmEmail = auth()->user()->email;
            $this->phone = auth()->user()->phone;
        }
    }

    public function returnToCart()
    {
        $this->redirect(route('event', ['slug' => $this->eventTranslation->slug]));
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

        if (empty($this->paymentGateway)) {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => 'Payment method is required',
            ]);
            return;
        }

        dd($this->paymentGateway);
    }

    #[On('clear-cart')]
    public function clearCart(): void
    {
        foreach ($this->tickets as $ticket) {
            $ticket->cartElements()->delete();
        }
    }

    public function purchase($paymentMethod) {}

    protected function updateDotEnv($key, $newValue, $delim = ''): void
    {
        $path = base_path('.env');
        // get old value from current env
        $oldValue = env($key);

        // was there any change?
        if ($oldValue === $newValue) {
            return;
        }

        // rewrite file content with changed data
        if (file_exists($path)) {
            // replace current value with new value
            file_put_contents(
                $path,
                str_replace(
                    $key . '=' . $delim . $oldValue . $delim,
                    $key . '=' . $delim . $newValue . $delim,
                    file_get_contents($path),
                ),
            );
        }
    }

    public function render()
    {
        return view('livewire.checkout');
    }
}
