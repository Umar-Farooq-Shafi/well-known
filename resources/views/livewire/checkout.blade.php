@php
    $event = $eventTranslation->event;
@endphp

<div class="mt-40">
    <div
        class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
        <div class="font-bold text-xl">{{ $eventTranslation->name }}</div>

        <x-breadcrumbs/>
    </div>

    <div class="flex items-center flex-col mt-8">
        <div class="grid w-2/3 grid-cols-1 md:gap-2 lg:gap-4 lg:grid-cols-8 md:grid-cols-4">
            <div class="md:col-span-2 lg:col-span-5">
                <div class="flex flex-col gap-y-1 font-medium text-base">
                    <p>{{ $eventTranslation->name }}</p>

                    <p>{{ $eventDate->startdate->format('F d, Y H:i') }}
                        - {{ $eventDate->enddate->format('F d, Y H:i') }}</p>
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

                    @if(count($quantity))
                        @php $subtotal = 0; $fee = 0; @endphp

                        @foreach($quantity as $id => $value)
                            @php
                                $eventDateTicket = \App\Models\EventDateTicket::find($id);

                                $subtotal += $eventDateTicket->price * $value;
                                $fee += $eventDateTicket->ticket_fee * $value;
                            @endphp

                            <div class="flex justify-between items-center m-2">
                                <p>{{ $value }} x {{ $eventDateTicket->name }}</p>

                                <p class="font-semibold">{{ $eventDateTicket->currency->ccy }} {{ $eventDateTicket->price * $value }}</p>
                            </div>
                        @endforeach

                        <hr class="my-2 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10"/>

                        <div class="flex justify-between items-center m-2">
                            <p>Subtotal</p>

                            <p class="font-semibold">
                                @if($eventDateTicket->currency_symbol_position === 'left')
                                    {{ $ccy }}
                                @endif
                                {{ $subtotal }}
                                @if($eventDateTicket->currency_symbol_position === 'right')
                                    {{ $ccy }}
                                @endif
                            </p>
                        </div>

                        <div class="flex justify-between items-center m-2">
                            <p>Fees</p>

                            <p class="font-semibold">
                                @if($eventDateTicket->currency_symbol_position === 'left')
                                    {{ $ccy }}
                                @endif
                                {{ $ccy }}
                                @if($eventDateTicket->currency_symbol_position === 'right')
                                    {{ $ccy }}
                                @endif
                            </p>
                        </div>

                        <hr class="my-2 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10"/>

                        <div class="flex justify-between items-center m-2">
                            <p class="font-bold">Total</p>

                            <p class="font-bold">
                                @if($eventDateTicket->currency_symbol_position === 'left')
                                    {{ $ccy }}
                                @endif
                                {{ $fee + $subtotal }}
                                @if($eventDateTicket->currency_symbol_position === 'right')
                                    {{ $ccy }}
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-4 flex-row-reverse gap-x-4">
            <x-button flat label="Cancel" x-on:click="close"/>

            <x-button primary label="Place Order" spinner="placeOrder"
                      wire:click="placeOrder"/>
        </div>
    </div>

    <x-modal-card :title="$eventTranslation->name" name="cardModal" blur="md" width="5xl">
        <input id="card-holder-name" type="text">

        <div id="card-element">

        </div>

        <button id="card-button">
            Process Payment
        </button>
    </x-modal-card>

    @script
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        const stripe = Stripe('stripe-public-key');

        const elements = stripe.elements();
        const cardElement = elements.create('card');

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
                    'description': typeof error === 'string' ? error : 'Unexpected error',
                });
            } else {
                Livewire.dispatch('purchase', {paymentMethod});
            }
        });
    </script>
    @endscript
</div>
