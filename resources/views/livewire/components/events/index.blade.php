@php use Illuminate\Support\Facades\Storage; @endphp

@push('styles')
    <style>
        input[type=range]::-webkit-slider-thumb {
            pointer-events: all;
            width: 24px;
            height: 24px;
            -webkit-appearance: none;
            /* @apply w-6 h-6 appearance-none pointer-events-auto; */
        }
    </style>
@endpush

<div class="container ml-36 mr-44 p-6">
    <div class="bg-gray-100 flex items-center justify-between p-4 mb-4">
        <p><span class="font-semibold">{{ $events_count }}</span> event(s) found</p>

        <a href="/rss" target="_blank" class="p-2 rounded-full bg-[#90ccf4]">
            <x-fas-rss class="h-4 w-4 text-blue-500"/>
        </a>
    </div>

    <div class="flex flex-col lg:flex-row justify-between mb-4">
        <!-- Sidebar -->
        <div class="w-full lg:w-1/4 mb-4 lg:mb-0 space-y-2 ml-4 mr-8">
            <form class="flex flex-col gap-y-4">
                <div class="flex flex-row gap-x-2">
                    <div wire:loading wire:target="query">
                        <x-heroicon-o-arrow-path class="animate-spin h-5 mt-8 w-5 text-blue-500"/>
                    </div>

                    <x-input
                        label="Keyword"
                        wire:model.live.debounce.500ms="query"
                        wire:loading.attr="disabled"
                        placeholder="Enter keywords"
                    />
                </div>

                <div class="flex flex-row gap-x-2">
                    <div wire:loading wire:target="category">
                        <x-heroicon-o-arrow-path class="animate-spin h-5 mt-8 w-5 text-blue-500"/>
                    </div>

                    <x-select
                        label="Categories"
                        placeholder="Select an option"
                        wire:model.live.debounce.500ms="category"
                        option-label="name"
                        option-value="id"
                        wire:loading.attr="disabled"
                        :async-data="route('api.events.categories')"
                    />
                </div>

                <div class="flex flex-col gap-y-2 p-4 border">
                    <x-checkbox
                        id="is_online"
                        wire:model.live.debounce.500ms="is_online"
                        wire:loading.attr="disabled"
                        label="Online events only"
                        md
                    />

                    <x-checkbox
                        id="is_local"
                        label="Local events only"
                        wire:model.live.debounce.500ms="is_local"
                        wire:loading.attr="disabled"
                        md
                    />

                    <div class="flex flex-row gap-x-2">
                        <div wire:loading wire:target="country">
                            <x-heroicon-o-arrow-path class="animate-spin h-5 mt-8 w-5 text-blue-500"/>
                        </div>

                        <x-select
                            label="Country"
                            placeholder="Select a country"
                            wire:model.live.debounce.500ms="country"
                            wire:loading.attr="disabled"
                            option-label="name"
                            option-value="id"
                            :async-data="route('api.events.country')"
                        />
                    </div>
                </div>

                <div class="flex flex-col gap-y-2 p-4 border">
                    <x-radio
                        id="day-today"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="Today" value="today" md
                    />

                    <x-radio
                        id="day-tomorrow"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="Tomorrow" value="tomorrow" md
                    />

                    <x-radio
                        id="day-this-weekend"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="This Weekend" value="this-weekend" md
                    />

                    <x-radio
                        id="day-this-week"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="This Week" value="this-week" md
                    />

                    <x-radio
                        id="day-next-week"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="Next Week" value="next-week" md
                    />

                    <x-radio
                        id="day-this-month"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="This Month" value="this-month" md
                    />

                    <x-radio
                        id="day-next-month"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="Next Month" value="next-month" md
                    />

                    <x-radio
                        id="day-pick-date"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="Pick a date" value="pick-date" md
                    />
                </div>

                @if($day === 'pick-date')
                    <div class="mt-2">
                        <x-datetime-picker
                            wire:model.live="customDate"
                            label=""
                            placeholder="Select date"
                            without-time
                        />
                    </div>
                @endif

                <div class="flex flex-col gap-y-2 p-4 border">
                    <x-checkbox
                        id="is_free"
                        label="Free events only"
                        wire:model.live.debounce.500ms="is_free"
                        wire:loading.attr="disabled"
                        md
                    />

                    <div class="p-4">
                        <div x-data="range()" x-init="mintrigger(); maxtrigger()" class="relative max-w-xl w-full">
                            <div>
                                <input type="range"
                                       step="100"
                                       x-bind:min="min" x-bind:max="max"
                                       x-on:input="mintrigger"
                                       wire:model.live.debounce.500ms="minPrice"
                                       wire:loading.attr="disabled"
                                       class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"/>

                                <input type="range"
                                       step="100"
                                       x-bind:min="min" x-bind:max="max"
                                       x-on:input="maxtrigger"
                                       wire:model.live.debounce.500ms="maxPrice"
                                       wire:loading.attr="disabled"
                                       class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"/>

                                <div class="relative h-2">

                                    <div
                                        class="absolute left-0 right-0 bottom-0 top-0 rounded-md bg-gray-200"></div>

                                    <div class="absolute top-0 bottom-0 rounded-md bg-blue-300"
                                         x-bind:style="'right:'+maxthumb+'%; left:'+minthumb+'%'"></div>

                                    <div class="absolute w-6 h-6 top-0 left-0 bg-blue-300 rounded-full -mt-2 -ml-1"
                                         x-bind:style="'left: '+minthumb+'%'"></div>

                                    <div
                                        class="absolute w-6 h-6 top-0 right-0 bg-blue-300 rounded-full -mt-2 -mr-3"
                                        x-bind:style="'right: '+maxthumb+'%'"></div>

                                </div>

                            </div>

                            <div class="flex justify-between items-center py-5">
                                <div>
                                    <input type="text" maxlength="5" x-on:input="mintrigger" x-model="minprice"
                                           class="px-3 py-2 border border-gray-200 rounded w-24 text-center">
                                </div>
                                <div>
                                    <input type="text" maxlength="5" x-on:input="maxtrigger" x-model="maxprice"
                                           class="px-3 py-2 border border-gray-200 rounded w-24 text-center">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Event Cards -->
        <div class="w-full lg:w-3/4 flex flex-col gap-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @foreach($events as $event)
                    @php
                        $country = $event->country;

                        if ($country) {
                            $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country->code);
                        } else {
                            $timezone[] = 'UTC';
                        }
                    @endphp

                    <div class="relative bg-white border border-gray-200 rounded shadow dark:bg-gray-800 dark:border-gray-700">
                        <a class="relative"
                           href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                            <img
                                class="w-full h-64 rounded"
                                src="{{ Storage::url('events/' . $event->image_name) }}"
                                loading="lazy"
                                alt="{{ $event->eventTranslations->first()?->name }}"
                            />

                            @if(auth()->check())
                                <span
                                    wire:click.prevent="eventFavourite({{ $event->id }})"
                                    class="absolute right-2 -bottom-2 z-10 bg-gray-50 shadow rounded-full p-1">
                                    @if($event->favourites()->where('User_id', auth()->id())->exists())
                                        <x-heroicon-s-heart class="w-4 h-4"/>
                                    @else
                                        <x-heroicon-o-heart class="w-4 h-4"/>
                                    @endif
                                </span>
                            @endif
                        </a>

                        <div class="p-5">
                            <a href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                                <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">
                                    {{ $event->category->categoryTranslations->first()?->name }}
                                </h5>

                                <p class="mb-3 font-normal text-xl text-gray-700 dark:text-gray-400">
                                    {{ $event->eventTranslations->first()?->name }}
                                </p>
                            </a>
                        </div>

                        @foreach($event->eventDates as $eventDate)
                            @if($eventDate->recurrent === 1)
                                <div
                                    class="absolute w-[70px] top-2.5 left-1 justify-center items-center shadow z-10 bg-white flex flex-col gap-y-2 text-gray-700">
                                    <p class="bg-sky-300 w-full text-center">
                                        Multiple Event Dates
                                    </p>
                                </div>

                                @break
                            @elseif($eventDate->isOnSale())
                                <div
                                    class="absolute w-[50px] top-2.5 left-1 justify-center items-center shadow z-10 bg-white flex flex-col gap-y-2 text-gray-700">
                                    <p class="bg-sky-300 w-full text-center">
                                        {{ \Carbon\Carbon::make($eventDate->startdate)->timezone($event->eventtimezone ?? $timezone[0])->format('M') }}
                                    </p>

                                    <p class="pb-1">
                                        {{ \Carbon\Carbon::make($eventDate->startdate)->timezone($event->eventtimezone ?? $timezone[0])->format('d') }}
                                    </p>
                                </div>

                                @break
                            @endif
                        @endforeach

                        <div class="flex justify-between items-center mb-2 mx-2">
                            <div class="flex flex-col gap-y-2">
                                <p class="ml-2 flex items-center gap-x-1">
                                    <x-fas-location-dot class="w-5 h-5 text-red-500"/>

                                    @if($venue = $event->eventDates?->first()?->venue)
                                        {{ $venue->name }}
                                    @endif
                                </p>

                                <p class="ml-2 flex items-center gap-x-1">
                                    <x-fas-clock class="w-4 h-4 text-red-500"/>

                                    @if($eventDate = $event->eventDates?->first())
                                        {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('l') }},
                                        Start {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a') }}
                                        (Timezone: {{ \Carbon\Carbon::now()->timezone($event->eventtimezone ?? $timezone[0])->format('T') }})
                                    @endif
                                </p>
                            </div>

                            @if($countEventDates = count($event->eventDates))
                                @php
                                    $ccy = $event->eventDates->first()->getCurrencyCode();
                                    $mixed = false;

                                    foreach ($event->eventDates as $ed) {
                                        if ($ccy !== $ed->getCurrencyCode()) {
                                            $mixed = true;
                                        }
                                    }
                                @endphp
                                @if($mixed)
                                    <p class="text-nowrap font-bold">Mixed Currency</p>
                                @else
                                    <p class="text-nowrap">

                                        @if($ed = $event->eventDates->first()->getCurrencyCode())
                                            <span
                                                class="font-bold">From</span> {{ $eventDate->getCurrencyCode() }}{{ $eventDate->getTotalTicketFees() }}
                                        @endif

                                        @if($countEventDates > 1)
                                            @foreach($event->eventDates as $eventDate)
                                                <span
                                                    class="font-bold">Lowest</span> {{ $eventDate->getCurrencyCode() }}{{ $eventDate->getTotalTicketFees() }}
                                            @endforeach
                                        @endif
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>

                @endforeach
            </div>

            {{ $events->links() }}
        </div>

    </div>
</div>

@push('scripts')
    <script>
        function range() {
            return {
                minprice: 0,
                maxprice: 10000,
                min: 100,
                max: 10000,
                minthumb: 0,
                maxthumb: 0,

                mintrigger() {
                    this.minprice = Math.min(this.minprice, this.maxprice - 500);
                    this.minthumb = ((this.minprice - this.min) / (this.max - this.min)) * 100;
                },

                maxtrigger() {
                    this.maxprice = Math.max(this.maxprice, this.minprice + 500);
                    this.maxthumb = 100 - (((this.maxprice - this.min) / (this.max - this.min)) * 100);
                },
            }
        }
    </script>
@endpush
