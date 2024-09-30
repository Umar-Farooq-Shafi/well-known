@php
    use Carbon\Carbon;

    $event = $eventTranslation->event;

    $subtotal = 0;
    $fee = 0;
    $ccy = null;
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

    <div class="mt-44">
        <div
            class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
            <div class="font-bold text-xl">{{ $eventTranslation->name }}</div>

            <x-breadcrumbs/>
        </div>

        <div class="flex items-center flex-col mt-8">
            <div class="grid w-2/3 grid-cols-1 md:gap-2 lg:gap-4 lg:grid-cols-8 md:grid-cols-3">
                <div class="md:col-span-2 lg:col-span-5">
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
                                                   wire:model="paymentGateway"
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

                <div class="lg:col-span-3 md:cols-span-2">
                    <img
                        src="{{ Storage::url('events/' . $event->image_name) }}"
                        alt="{{ $eventTranslation->name }}"
                        loading="lazy"
                        class="w-full opacity-100 h-40"
                    />

                    <div class="mt-1">
                        <h1 class="font-semibold">Order Summary</h1>

                        @foreach($tickets as $ticket)
                            @foreach ($ticket->cartElements as $cartElement)
                                @php
                                    $ccy = $ticket->currency->ccy;

                                    $subtotal += $ticket->price * $cartElement->quantity;
                                    $fee += $ticket->ticket_fee * $cartElement->quantity;
                                @endphp

                                <div class="flex justify-between items-center m-2">
                                    <p>{{ $cartElement->quantity }} x {{ $ticket->name }}</p>

                                    <p class="font-semibold">{{ $ccy }} {{ $ticket->price * $cartElement->quantity }}</p>
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
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-4 flex-row-reverse gap-x-4">
                <x-button flat label="Cancel" x-on:click="close"/>

                <x-button
                    primary
                    label="Place Order"
                    spinner="placeOrder"
                    wire:click="placeOrder"
                />
            </div>
        </div>

        @if($this->stripe)
            <x-modal-card :title="$eventTranslation->name" name="stripeModal" blur="md">
                <x-input
                    label="Name"
                    placeholder="Card Holder Name"
                    id="card-holder-name"
                />

                <div id="card-element" class="my-4">

                </div>

                <x-button spinner="purchase" label="Process Payment" id="card-button"/>
            </x-modal-card>
        @endif

    </div>

    @push('scripts')
        <script src="https://js.stripe.com/v3/"></script>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('checkoutData', () => {
                    return {
                        isExpired: false,

                        init() {
                            setTimeout(() => {
                                this.isExpired = true;

                                Livewire.dispatch('clear-cart');
                            }, parseInt(@js($sessionTime)) * 1000);
                        }
                    };
                });
            });

            document.addEventListener('livewire:init', function () {
                Livewire.on('openModal', () => {
                    $openModal('stripeModal');

                    setTimeout(() => {
                        const stripConfig = @js($stripe);

                        if (stripConfig?.config?.publishable_key) {
                            const stripe = Stripe(stripConfig.config.publishable_key);

                            const elements = stripe.elements();
                            const cardElement = elements.create('card', {
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
                                        paymentMethodId: paymentMethod.id, // Send the PaymentMethod ID
                                        amount: @js($subtotal),
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
