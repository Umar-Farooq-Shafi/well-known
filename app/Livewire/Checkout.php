<?php

namespace App\Livewire;

use App\Jobs\EmptyCard;
use App\Models\EventTranslation;
use App\Models\PaymentGateway;
use App\Models\Setting;
use Livewire\Attributes\On;
use Livewire\Component;
use Stripe\Customer;
use Stripe\Exception\ApiErrorException;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Stripe;
use WireUi\Traits\WireUiActions;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

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

    public $stripe;

    public function mount(string $slug): void
    {
        $this->eventTranslation = EventTranslation::whereSlug($slug)->firstOrFail();

        foreach ($this->eventTranslation->event->eventDates as $eventDate) {
            foreach ($eventDate->eventDateTickets as $ticket) {
                $this->tickets[] = $ticket;

                foreach ($ticket->cartElements as $cartElement) {
                    $this->eventDatePick = $cartElement->chosen_event_date;
                }

                foreach ($ticket->paymentGateways as $paymentGateway) {
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

        if (count($this->tickets) === 0) {
            abort(404);
        }

        $this->sessionTime = Setting::query()->where('key', 'checkout_timeleft')->first()?->value;

        EmptyCard::dispatch($this->tickets)->delay(now()->addSeconds((int)$this->sessionTime));

        $this->stripe = $this->eventTranslation->event->organizer->paymentGateways()
            ->where('factory_name', 'stripe_checkout')
            ->first();

        if (!$this->stripe) {
            $this->stripe = PaymentGateway::query()
                ->where('factory_name', 'stripe_checkout')
                ->whereNull('organizer_id')
                ->first();
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

    /**
     * @throws \Throwable
     */
    public function placeOrder(): void
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

        $paymentGateway = PaymentGateway::find($this->paymentGateway);

        if ($paymentGateway->factory_name === 'stripe_checkout') {
            $this->dispatch('openModal');

            return;
        }

        if ($paymentGateway->factory_name === 'paypal_express_checkout') {
            $paypal = $this->eventTranslation->event->organizer->paymentGateways()
                ->where('factory_name', 'paypal_express_checkout')
                ->first();

            if (!$paypal) {
                $paypal = PaymentGateway::query()
                    ->where('factory_name', 'paypal_express_checkout')
                    ->whereNull('organizer_id')
                    ->first();
            }

            $subtotal = 0;
            $fee = 0;
            $ccy = null;

            foreach($this->tickets as $ticket) {
                foreach ($ticket->cartElements as $cartElement) {
                    $ccy = $ticket->currency->ccy;

                    $subtotal += $ticket->price * $cartElement->quantity;
                    $fee += $ticket->ticket_fee * $cartElement->quantity;
                }
            }

            $provider = new PayPalClient;
            $provider->setApiCredentials(credentials: [
                'mode' => app()->isProduction() ? 'live' : 'sandbox',

                'sandbox' => [
                    'client_id' => $paypal->config['secret_key'],
                    'client_secret' => $paypal->config['signature'],
                    'app_id' => 'APP-80W284485P519543T',
                ],

                'live' => [
                    'client_id' => env('PAYPAL_LIVE_CLIENT_ID', ''),
                    'client_secret' => $paypal->config['signature'],
                    'app_id' => env('PAYPAL_LIVE_APP_ID', ''),
                ],

                'payment_action' => 'Sale',
                'currency' => $ccy,
                'notify_url' => 'https://your-site.com/paypal/notify',
                'locale' => 'en_US',
                'validate_ssl' => true,
            ]);

            $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('successTransaction'),
                    "cancel_url" => route('cancelTransaction'),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => $ccy,
                            "value" => $subtotal + $fee
                        ]
                    ]
                ]
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                // redirect to approve href
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        $this->redirect($links['href']);
                    }
                }

                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error',
                    'description' => 'Something went wrong.',
                ]);
            } else {
                $this->notification()->send([
                    'icon' => 'error',
                    'title' => 'Error',
                    'description' => $response['message'] ?? 'Something went wrong.',
                ]);
            }
        }

        dd($paymentGateway);
    }

    #[On('clear-cart')]
    public function clearCart(): void
    {
        foreach ($this->tickets as $ticket) {
            $ticket->cartElements()->delete();
        }
    }

    /**
     * @throws ApiErrorException
     */
    #[On('purchase')]
    public function purchase($paymentMethodId, $amount, $ccy): void
    {
        Stripe::setApiKey($this->stripe->config['secret_key']);

        $paymentMethod = PaymentMethod::retrieve($paymentMethodId);

        // Create a new Stripe customer.
        $customer = Customer::create([
            'email' => $this->email,
            'payment_method' => $paymentMethodId, // Use the payment method ID from the client
            'invoice_settings' => [
                'default_payment_method' => $paymentMethodId, // Set as default for future invoices
            ],
        ]);

        $paymentMethod->attach([
            'customer' => $customer->id,
        ]);

        $paymentIntent = PaymentIntent::create([
            'amount' => $amount,
            'currency' => $ccy,
            'customer' => $customer->id,
            'payment_method' => $paymentMethod->id,
            'off_session' => true,  // for automatic payments without user interaction
            'confirm' => true,      // immediately confirm the payment
        ]);

        if ($paymentIntent->status === 'succeeded') {
            $this->notification()->send([
                'icon' => 'success',
                'title' => 'Payment successful!',
            ]);
        } else {
            $this->notification()->send([
                'icon' => 'error',
                'title' => 'Error',
                'description' => $paymentIntent->status,
            ]);
        }

    }

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
