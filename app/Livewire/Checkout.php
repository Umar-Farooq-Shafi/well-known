<?php

namespace App\Livewire;

use App\Jobs\EmptyCard;
use App\Models\CartElement;
use App\Models\EventTranslation;
use App\Models\PaymentGateway;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;
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

        if (!auth()->check()) {
            $userId = Session::get('user_id');
        } else {
            $userId = auth()->id();
        }

        if (!$userId) {
            abort(404);
        }

        foreach ($this->eventTranslation->event->eventDates as $eventDate) {
            foreach ($eventDate->eventDateTickets as $ticket) {

                $cartElements = $ticket->cartElements()
                    ->where('user_id', $userId)
                    ->get();

                if (count($cartElements)) {
                    $this->tickets[] = $ticket;
                }

                foreach ($cartElements as $cartElement) {
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

            foreach ($this->tickets as $ticket) {
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

        if ('eseva' === $paymentGateway->factory_name) {
            $eseva = $this->eventTranslation->event->organizer->paymentGateways()
                ->where('factory_name', 'eseva')
                ->first();

            if (!$eseva) {
                $eseva = PaymentGateway::query()
                    ->where('factory_name', 'eseva')
                    ->whereNull('organizer_id')
                    ->first();
            }

            $subtotal = 0;
            $fee = 0;
            $ccy = null;

            foreach ($this->tickets as $ticket) {
                foreach ($ticket->cartElements as $cartElement) {
                    $ccy = $ticket->currency->ccy;

                    $subtotal += $ticket->price * $cartElement->quantity;
                    $fee += $ticket->ticket_fee * $cartElement->quantity;
                }
            }

            if (app()->isProduction()) {
                $key = $eseva?->config['secret_key'];
            } else {
                $key = '8gBm/:&EnhH.1/q';
            }

            $tuid = now()->timestamp;
            $message = "total_amount=" . ($subtotal + $fee) . ",transaction_uuid=$tuid,product_code=" . $eseva?->config['merchant_code'];
            $s = hash_hmac('sha256', $message, $key, true);
            $signature = base64_encode($s);
            $dataArray = [
                "amount" => $subtotal + $fee,
                "failure_url" => route('esewa.failure'),
                "product_delivery_charge" => "0",
                "product_service_charge" => "0",
                "product_code" => $eseva?->config['merchant_code'],
                "signature" => $signature,
                "signed_field_names" => "total_amount,transaction_uuid,product_code",
                "success_url" => route('esewa.success'),
                "tax_amount" => "0",
                "total_amount" => $subtotal + $fee,
                "transaction_uuid" => $tuid,
                "currency" => $ccy
            ];

            $this->dispatch("redirectToEsewaPage", $dataArray);
            return;
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

            $this->clearCart();
            $this->returnToCart();
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
