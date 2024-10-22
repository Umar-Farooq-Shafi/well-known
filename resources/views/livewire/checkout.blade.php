@php
    use App\Models\PaymentGateway;use Carbon\Carbon;

    $event = $eventTranslation->event;
    $country = $event?->country?->code;

    $subtotal = 0;
    $fee = 0;
    $ccy = null;

    $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country);
@endphp

<div x-data="checkoutData">
    <div
        class="fixed inset-0 z-50 flex flex-col items-center justify-center bg-gray-900 bg-opacity-80 text-white text-center"
        x-show="isExpired"
        style="pointer-events: none;">
        <div class="z-10" style="pointer-events: auto;">
            <h1 class="text-4xl font-bold mb-4">Time's up</h1>
            <p class="mb-6">The tickets have been released.</p>
            <x-button
                primary
                label="Return to Cart"
                wire:click="returnToCart"
            />
        </div>
    </div>

    <div class="mt-[25%] lg:mt-[12%] md:mt-[17%] container mx-auto">
        <div
            class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
            <div class="font-bold text-xl">{{ $eventTranslation->name }}</div>

            <x-breadcrumbs/>
        </div>

        <div
            class="fixed top-0 left-0 w-full bg-red-400 text-white text-center py-2 shadow-lg z-50">
            <div class="timer flex justify-center space-x-2">
                <h1 class="text-lg font-bold" x-text="time.days"></h1>
                <span class="text-lg font-bold">:</span>
                <h1 class="text-lg font-bold" x-text="time.hours"></h1>
                <span class="text-lg font-bold">:</span>
                <h1 class="text-lg font-bold" x-text="time.minutes"></h1>
                <span class="text-lg font-bold">:</span>
                <h1 class="text-lg font-bold" x-text="time.seconds"></h1>
            </div>
        </div>


        <div class="flex items-center flex-col mt-8">
            <div class="grid w-2/3 grid-cols-1 md:gap-2 lg:gap-4 lg:grid-cols-8 md:grid-cols-3">
                <div class="md:col-span-2 lg:col-span-5 order-last md:order-none">
                    <div class="flex flex-col gap-y-1 font-medium text-base pb-2">
                        <p>{{ $eventTranslation->name }}</p>

                        @foreach($tickets as $ticket)
                            @php $eventDate = $ticket->eventDate @endphp

                            <p>
                                {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('F d Y') }}
                                - {{ $eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('F d Y') }}
                                ({{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('H:i A') }}
                                - {{ $eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('H:i A') }}
                                )
                            </p>
                        @endforeach

                        @if($eventDatePick)
                            <p>Selected Date: {{ $eventDatePick->format('F d Y - H:i A') }}</p>
                        @endif
                    </div>

                    <x-card title="Billing Information" class="mt-2">
                        @guest
                            <h1>
                                <a href="{{ route('filament.admin.auth.login') }}"
                                   class="text-blue-500">Login</a> for
                                a fast experience.
                            </h1>
                        @endguest

                        <div class="flex gap-x-2 my-2">
                            <x-input
                                icon="user"
                                label="First Name"
                                wire:model="firstName"
                                placeholder="Enter First Name"
                            />

                            <x-input
                                icon="user"
                                label="Last Name"
                                wire:model="lastName"
                                placeholder="Enter Last Name"
                            />
                        </div>

                        <div class="flex gap-x-2 my-2">
                            <x-input
                                icon="envelope"
                                label="Email address"
                                wire:model="email"
                                placeholder="Enter Email Address"
                            />

                            <x-input
                                icon="envelope"
                                label="Confirm email"
                                wire:model="confirmEmail"
                                placeholder="Enter Confirm email"
                            />
                        </div>

                        <div class="flex gap-x-2 my-2">
                            <x-input
                                icon="phone"
                                label="Phone No"
                                wire:model="phone"
                                placeholder="Enter Phone No"
                            />

                            <div></div>
                        </div>
                    </x-card>

                    <x-card title="Pay With" class="mt-2">
                        <div class="flex flex-col gap-y-2">
                            @foreach($paymentGateways as $paymentGateway)
                                <div
                                    class="rounded-lg border border-gray-200 bg-gray-50 p-4 ps-4 dark:border-gray-700 dark:bg-gray-800">
                                    <div class="flex items-center">
                                        <div class="flex h-5 items-center">
                                            <input id="credit-card"
                                                   aria-describedby="credit-card-text"
                                                   type="radio"
                                                   value="{{ $paymentGateway->id }}"
                                                   wire:model.live="paymentGateway"
                                                   class="h-4 w-4 border-gray-300 bg-white text-primary-600 focus:ring-2 focus:ring-primary-600 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800 dark:focus:ring-primary-600"
                                            />
                                        </div>

                                        <div
                                            class="ms-4 text-sm flex justify-between items-center w-full">
                                            <label for="credit-card"
                                                   class="font-medium leading-none text-gray-900 dark:text-white">
                                                {{ $paymentGateway->name }}
                                            </label>

                                            <div>
                                                <img
                                                    src="{{ Storage::url('payment/gateways/' . $paymentGateway->gateway_logo_name) }}"
                                                    alt="{{ $paymentGateway->name }}"
                                                    class="w-8 h-8"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </x-card>
                </div>

                <div class="lg:col-span-3 md:col-span-2">
                    <img
                        src="{{ Storage::url('events/' . $event->image_name) }}"
                        alt="{{ $eventTranslation->name }}"
                        loading="lazy"
                        class="w-full opacity-100 h-40"
                    />

                    <div class="mt-1">
                        <h1 class="font-semibold">Order Summary</h1>

                        @foreach($tickets as $ticket)
                            @foreach ($ticket->cartElements()->where('user_id', auth()->id())->get() as $cartElement)
                                @php
                                    $ccy = $ticket->currency->ccy;
                                    $price = $ticket->price;
                                    $originalPrice = $ticket->free ? 0 : $ticket->price;
                                    $value = $ticket->free ? 0 : $cartElement->quantity;

                                    if ($ticket->promotionalprice) {
                                        $now = now()->timezone($event->eventtimezone ?? $timezone[0]);
                                        $isStartDate = $ticket->salesstartdate?->timezone($event->eventtimezone ?? $timezone[0])?->lessThanOrEqualTo($now);
                                        $isEndDate = $ticket->salesenddate?->timezone($event->eventtimezone ?? $timezone[0])?->greaterThanOrEqualTo($now);

                                        if ($isStartDate && $isEndDate) {
                                            $price = $ticket->price - $ticket->promotionalprice;
                                        }
                                    }

                                    $ticketTotal = $price * $value;

                                    if ($this->promotions) {
                                        $promoThreshold = array_key_first($this->promotions);
                                        $discountPerPromo = $this->promotions[$promoThreshold];

                                        if ($value >= $promoThreshold) {
                                            $eligiblePromos = floor($value / $promoThreshold);
                                            $totalDiscount = $eligiblePromos * $discountPerPromo;

                                            $ticketTotal -= $totalDiscount;
                                        }
                                    }

                                    $subtotal += max($ticketTotal, 0);
                                    $fee += $ticket->ticket_fee * $cartElement->quantity;

                                    if ($this->couponType === 'percentage') {
                                         $discount = ($subtotal * $this->couponDiscount) / 100;
                                         $subtotal -= $discount;
                                    }

                                    if ($this->couponType === 'fixed_amount') {
                                        $subtotal -= $this->couponDiscount;
                                    }

                                    $subtotal = max($subtotal, 0);
                                @endphp

                                <div class="flex justify-between items-center m-2">
                                    <p>{{ $value }} x {{ $ticket->name }}</p>

                                    <div class="font-semibold">
                                        <span>{{ $ccy }}</span>
                                        @if($ticketTotal !== $originalPrice * $value)
                                            <del>{{ $originalPrice * $value }}</del>
                                        @endif
                                        <span>{{ max($ticketTotal, 0) }}</span>
                                    </div>
                                </div>

                            @endforeach
                        @endforeach

                        <hr class="my-2 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10"/>

                        <div class="flex justify-between items-center m-2">
                            <p>Subtotal</p>

                            <p class="font-semibold">
                                @if($ticket->currency_symbol_position === 'left')
                                    {{ $ccy }}
                                @endif
                                {{ $subtotal }}
                                @if($ticket->currency_symbol_position === 'right')
                                    {{ $ccy }}
                                @endif
                            </p>
                        </div>

                        <div class="flex justify-between items-center m-2">
                            <p>Fees</p>

                            <p class="font-semibold">
                                @if($ticket->currency_symbol_position === 'left')
                                    {{ $ccy }}
                                @endif
                                {{ $fee }}
                                @if($ticket->currency_symbol_position === 'right')
                                    {{ $ccy }}
                                @endif
                            </p>
                        </div>

                        <hr class="my-2 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10"/>

                        <div class="flex justify-between items-center m-2">
                            <p class="font-bold">Total</p>

                            <p class="font-bold">
                                @if($ticket->currency_symbol_position === 'left')
                                    {{ $ccy }}
                                @endif
                                {{ $fee + $subtotal }}
                                @if($ticket->currency_symbol_position === 'right')
                                    {{ $ccy }}
                                @endif
                            </p>
                        </div>

                        @if($this->paymentGateway)
                            @php
                                $paymentGateway = PaymentGateway::find($this->paymentGateway);
                            @endphp

                            @if($this->stripe && $paymentGateway->factory_name === 'stripe_checkout')
                                <div class="mt-8">
                                    <x-input
                                        label="Name"
                                        placeholder="Card Holder Name"
                                        id="card-holder-name"
                                    />

                                    <div id="card-element" class="my-4">

                                    </div>

                                    <x-button
                                        spinner="placeOrder"
                                        wire:target="placeOrder"
                                        wire:click="placeOrder"
                                        label="Place Order"
                                        id="card-button"
                                    />
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            @if($this->paymentGateway)
                @php
                    $paymentGateway = PaymentGateway::find($this->paymentGateway);
                @endphp

                @if($paymentGateway->factory_name !== 'stripe_checkout')
                    <div class="flex justify-end mt-4 flex-row-reverse gap-x-4">
                        <x-button flat label="Cancel" x-on:click="close"/>

                        <x-button
                            primary
                            label="Place Order"
                            spinner="placeOrder"
                            wire:click="placeOrder"
                        />
                    </div>
                @endif
            @endif
        </div>

    </div>

    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>

        <script>
            document.addEventListener('alpine:init', () => {

                Alpine.data('checkoutData', () => {
                    return {
                        expiry: Alpine.$persist(new Date().getTime() + (parseInt(@js($sessionTime)) * 1000)).as('session_left_time'),
                        remaining: null,
                        time: {},
                        isExpired: false,

                        init() {
                            const storedExpiry = localStorage.getItem('session_left_time');
                            if (storedExpiry && new Date().getTime() > parseInt(storedExpiry)) {
                                localStorage.removeItem('session_left_time');
                            }

                            this.setRemaining();
                            this.updateTime();
                            setInterval(() => {
                                this.setRemaining();
                                this.updateTime();
                            }, 1000);

                            setTimeout(() => {
                                if (!this.isExpired) {
                                    this.isExpired = true;
                                    localStorage.removeItem('session_left_time');
                                    Livewire.dispatch('clear-cart');
                                }
                            }, parseInt(@js($sessionTime)) * 1000);
                        },

                        setRemaining() {
                            const diff = this.expiry - new Date().getTime();
                            this.remaining = parseInt(diff / 1000);

                            if (this.remaining <= 0) {
                                this.remaining = 0;  // Ensure it doesn't go negative
                                this.isExpired = true;
                                localStorage.removeItem('session_left_time');
                                Livewire.dispatch('clear-cart');
                            }
                        },
                        days() {
                            return {
                                value: this.remaining / 86400,
                                remaining: this.remaining % 86400
                            };
                        },
                        hours() {
                            return {
                                value: this.days().remaining / 3600,
                                remaining: this.days().remaining % 3600
                            };
                        },
                        minutes() {
                            return {
                                value: this.hours().remaining / 60,
                                remaining: this.hours().remaining % 60
                            };
                        },
                        seconds() {
                            return {
                                value: this.minutes().remaining
                            };
                        },
                        format(value) {
                            return ("0" + parseInt(value)).slice(-2);
                        },
                        updateTime() {
                            this.time = {
                                days: this.format(this.days().value),
                                hours: this.format(this.hours().value),
                                minutes: this.format(this.minutes().value),
                                seconds: this.format(this.seconds().value),
                            };
                        }
                    };
                });

            });

            document.addEventListener('livewire:init', function () {
                Livewire.on('showStrip', () => {
                    setTimeout(() => {
                        const stripConfig = @js($stripe);

                        if (stripConfig?.config?.publishable_key) {
                            const stripe = Stripe(stripConfig.config.publishable_key);

                            const elements = stripe.elements();
                            const cardElement = elements.create('card', {
                                hidePostalCode: true,
                                style: {
                                    base: {
                                        fontSize: '16px',
                                        color: '#32325d',
                                        '::placeholder': {
                                            color: '#aab7c4',
                                        },
                                    },
                                    invalid: {
                                        color: '#fa755a',
                                        iconColor: '#fa755a',
                                    },
                                },
                            });

                            cardElement.mount('#card-element');

                            const cardHolderName = document.getElementById('card-holder-name');
                            const cardButton = document.getElementById('card-button');

                            cardButton.addEventListener('click', async (e) => {
                                const {paymentMethod, error} = await stripe.createPaymentMethod(
                                    'card', cardElement, {
                                        billing_details: {name: cardHolderName.value}
                                    }
                                );

                                if (error) {
                                    window.$wireui.notify({
                                        'icon': 'error',
                                        'title': 'Error',
                                        'description': error?.message || 'Unexpected error',
                                    });
                                } else {
                                    Livewire.dispatch('purchase', {
                                        paymentMethodId: paymentMethod.id,
                                        amount: @js($subtotal + $fee),
                                        ccy: @js($ccy)
                                    });
                                }
                            });
                        }
                    }, 1000);
                });

                Livewire.on('redirectToEsewaPage', function (data) {
                    data = data[0];
                    const path = "https://rc-epay.esewa.com.np/api/epay/main/v2/form";
                    const form = document.createElement("form")
                    form.setAttribute("method", "POST")
                    form.setAttribute("action", path)

                    const formData = new FormData();
                    for (const key in data) {
                        const hiddenField = document.createElement("input")
                        hiddenField.setAttribute("type", "hidden")
                        hiddenField.setAttribute("name", key)
                        hiddenField.setAttribute("value", data[key])
                        form.append(hiddenField)
                    }

                    document.body.appendChild(form)
                    form.submit()
                });
            });

        </script>
    @endpush
</div>
