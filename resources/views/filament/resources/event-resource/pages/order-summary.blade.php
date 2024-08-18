@php
    $id = $getId();
    $isContained = $getContainer()->getParentComponent()->isContained();

    $activeStepClasses = \Illuminate\Support\Arr::toCssClasses([
        'fi-active',
        'p-6' => $isContained,
        'mt-6' => ! $isContained,
    ]);

    $inactiveStepClasses = 'invisible absolute h-0 overflow-hidden p-0';
@endphp

<div
    x-bind:class="{
        @js($activeStepClasses): step === @js($id),
        @js($inactiveStepClasses): step !== @js($id),
    }"
    x-on:expand="
        if (! isStepAccessible(@js($id))) {
            return
        }

        step = @js($id)
    "
    x-ref="step-{{ $id }}"
    {{
        $attributes
            ->merge([
                'aria-labelledby' => $id,
                'id' => $id,
                'role' => 'tabpanel',
                'tabindex' => '0',
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-fo-wizard-step outline-none']),
    }}
>

    @if(count($data))
        <div class="mx-auto p-4 bg-white rounded-lg mb-4">
            <h2 class="text-xl font-semibold mb-4">Order summary</h2>
            <div class="space-y-4">
                @foreach($eventDateTickets as $eventDateTicket)
                    @php
                        $eventDate = $eventDateTicket->eventDate;
                        $event = $eventDate->event;
                        $venue = $eventDate->venue;
                        $currency = $eventDateTicket->currency?->ccy ?? '';
                        $countryTrans = $venue ? $venue->country->countryTranslations()->where('locale', App::getLocale())->first() : null;
                        $quantity = $data['reference-' . $eventDateTicket->id];
                    @endphp

                    <div class="flex items-center gap-x-4">
                        <img src="{{ \Illuminate\Support\Facades\Storage::url('events/' . $event->image_name) }}"
                             loading="lazy"
                             alt="{{ $event->eventTranslations->first()?->name }}"
                             class="w-16 h-16 object-cover"
                        />

                        <div class="flex-1">
                            <h3 class="text-lg font-semibold">{{ $event->eventTranslations->first()?->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $eventDateTicket->name }}</p>
                            <p class="text-sm text-gray-500">When: <span
                                    class="font-semibold">
                                    {{ $eventDate?->startdate ? \Carbon\Carbon::make($eventDate->startdate)->timezone($timezone)->format('l jS F Y, h:i A') : '' }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-500">Where: <span class="font-semibold">
                                    {{ $venue?->street }} {{ $venue?->street2 }} {{ $venue?->city }} {{ $venue?->state }} {{ $countryTrans?->name }}
                                </span>
                            </p>
                            <p class="text-sm text-gray-500">Organizer <span
                                    class="font-semibold text-blue-500">{{ $event?->organizer?->name }}</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-semibold">{{ $currency }}: {{ $eventDateTicket->getSalePrice() }}</p>
                            <p class="text-sm text-gray-500">Quantity: {{ $quantity }}</p>
                            <p class="text-lg font-semibold text-blue-500">{{ $currency }} {{ $quantity * $eventDateTicket->getSalePrice() }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{ $getChildComponentContainer() }}
</div>
