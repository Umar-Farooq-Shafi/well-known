@php use Illuminate\Support\Facades\Storage; @endphp

<div>
    <livewire:side-panel/>

    <main class="mx-16">
        @if(count($categories))
            <section class="flex items-center overflow-auto gap-x-24 py-6 justify-center w-full">
                @foreach($categories as $cat)
                    <div class="flex items-center flex-col">
                        <a
                            href="{{ route('events', ['category' => str_replace('/', ' ', $cat->categoryTranslations->first()?->name)]) }}"
                            class="{{ $category === $cat->categoryTranslations->first()?->name ? 'bg-blue-300' : 'bg-white' }} border rounded-full border-gray-300 p-6 cursor-pointer hover:bg-gray-300 hover:border-blue-300">
                            <x-dynamic-component component="{{ $cat->icon }}" class="w-6 h-6"/>
                        </a>

                        <p>{{ $cat->categoryTranslations->first()?->name }}</p>
                    </div>
                @endforeach
            </section>
        @endif

        <section class="flex gap-x-2 px-4 items-center py-6">
            <h1 class="text-2xl font-semibold">Browse events in</h1>

            <div x-data="searchDropdown"
                 class="relative"
                 @keydown.esc.window="isOpen = false; openedWithKeyboard = false">

                <!-- Toggle Button -->
                <div @click="isOpen = !isOpen"
                     class="inline-flex cursor-pointer items-center gap-2 whitespace-nowrap p-2 text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-800 dark:border-slate-700 dark:bg-slate-800 dark:focus-visible:outline-slate-300"
                     aria-haspopup="true" @keydown.space.prevent="openedWithKeyboard = true"
                     @keydown.enter.prevent="openedWithKeyboard = true"
                     @keydown.down.prevent="openedWithKeyboard = true"
                     :class="isOpen || openedWithKeyboard ? 'text-black dark:text-white' : 'text-slate-700 dark:text-slate-300'"
                     :aria-expanded="isOpen || openedWithKeyboard">
                    <svg aria-hidden="true" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         stroke-width="2"
                         stroke="currentColor" class="size-4 totate-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>

                    <span class="text-blue-500">{{ $country }}</span>
                </div>

                <div x-cloak x-show="isOpen || openedWithKeyboard" x-transition
                     @click.outside="isOpen = false; openedWithKeyboard = false"
                     @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()"
                     class="absolute top-11 z-50 left-0 flex w-full min-w-[12rem] flex-col overflow-hidden rounded-xl border border-slate-300 bg-slate-100 py-1.5 dark:border-slate-700 dark:bg-slate-800"
                     role="menu">

                    <input x-model="search" type="text" placeholder="Search..."
                           class="px-4 py-2 mx-2 my-1 text-sm border-none text-slate-700 bg-white focus:outline-none"
                    />

                    <template
                        x-for="country in countries.filter(country => country.toLowerCase().includes(search.toLowerCase())).slice(0, 10)"
                        :key="country">
                        <div @click="$wire.updateCountry(country); isOpen = false"
                             class="bg-slate-100 px-4 cursor-pointer py-2 text-sm text-slate-700 hover:bg-slate-800/5 hover:text-black focus-visible:bg-slate-800/10 focus-visible:text-black focus-visible:outline-none dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-100/5 dark:hover:text-white dark:focus-visible:bg-slate-100/10 dark:focus-visible:text-white"
                             role="menuitem">
                            <span x-text="country"></span>
                        </div>
                    </template>

                </div>
            </div>

        </section>

        <nav>
            <div class="max-w-screen-xl px-4 py-3">
                <div class="flex items-center">
                    <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                        <li>
                            <a href="?country={{ $country }}"
                               class="{{ $category === '' ? 'text-blue-700 underline decoration-blue-500 decoration-2 underline-offset-8' : 'text-gray-900' }} text-blue-700 text-base  dark:text-white hover:underline"
                               aria-current="page">
                                All
                            </a>
                        </li>
                        <li>
                            <a href="?category=online&country={{ $country }}"
                               class="{{ $category === 'online' ? 'text-blue-700 underline decoration-blue-500 decoration-2 underline-offset-8' : 'text-gray-900' }} text-base dark:text-white hover:underline">
                                Online
                            </a>
                        </li>
                        <li>
                            <a href="?category=today&country={{ $country }}"
                               class="{{ $category === 'today' ? 'text-blue-700 underline decoration-blue-500 decoration-2 underline-offset-8' : 'text-gray-900' }} text-base dark:text-white hover:underline">
                                Today
                            </a>
                        </li>
                        <li>
                            <a href="?category=this-weekend&country={{ $country }}"
                               class="{{ $category === 'this-weekend' ? 'text-blue-700 underline decoration-blue-500 decoration-2 underline-offset-8' : 'text-gray-900' }} text-base dark:text-white hover:underline">
                                This Weekend
                            </a>
                        </li>
                        <li>
                            <a href="?category=free&country={{ $country }}"
                               class="{{ $category === 'free' ? 'text-blue-700 underline decoration-blue-500 decoration-2 underline-offset-8' : 'text-gray-900' }} text-base dark:text-white hover:underline">
                                Free
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        @if(count($events))
            <div class="swiper eventSlider my-4" wire:ignore>
                <div class="swiper-wrapper">
                    @foreach($events as $event)
                        @php
                            $country = $event->country;

                            if ($country) {
                                $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country->code);
                            } else {
                                $timezone[] = 'UTC';
                            }
                        @endphp

                        @if($event->eventTranslations->first())
                            <div class="inline-block px-3 swiper-slide">
                                <div
                                    class="relative overflow-hidden rounded-lg shadow-md bg-white hover:shadow-xl transition-shadow duration-300 ease-in-out"
                                >
                                    <a
                                        class="flex flex-col justify-between h-full"
                                        @if($slug = $event->eventTranslations->first()?->slug)
                                            href="{{ route('event', ['slug' => $slug]) }}"
                                        @endif>
                                        <span
                                            class="absolute top-2.5 shadow z-10 bg-white text-gray-700 rounded-l py-0.5 px-2 opacity-90 right-0">
                                            {{ $event->category->categoryTranslations->first()?->name ?? '' }}
                                        </span>

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
                                                        {{ \Carbon\Carbon::make($eventDate->startdate)->format('M') }}
                                                    </p>

                                                    <p class="pb-1">
                                                        {{ \Carbon\Carbon::make($eventDate->startdate)->format('d') }}
                                                    </p>
                                                </div>

                                                @break
                                            @endif
                                        @endforeach

                                        <img class="w-full h-44" loading="lazy"
                                             src="{{ Storage::url('events/' . $event->image_name) }}"
                                             alt="{{ $event->eventTranslations->first()->name }}"
                                        />

                                        <p class="p-2 font-normal text-lg text-gray-700 dark:text-gray-400">
                                            {{ $event->eventTranslations->first()?->name }}
                                        </p>

                                        <div class="px-4">
                                            @if($countEventDates = count($event->eventDates))
                                                @php
                                                    $ccy = $event->eventDates->first()->getCurrencyCode();
                                                    $mixed = false;
                                                    $lowest = $event->eventDates->first()?->getTotalTicketFees();
                                                    $isFree = DB::table('eventic_event_date_ticket')
                                                        ->whereIn('eventdate_id', $event->eventDates->pluck('id')->toArray())
                                                        ->where('free', true)
                                                        ->exists();
                                                    $isPartialFree = false;

                                                    foreach ($event->eventDates as $ed) {
                                                        if ($ccy !== $ed->getCurrencyCode()) {
                                                            $mixed = true;
                                                        }

                                                        if ($ed->getTotalTicketFees() < $lowest) {
                                                            $lowest = $ed->getTotalTicketFees();
                                                        }

                                                        if ($ed->free) {
                                                            $isPartialFree = true;
                                                        }
                                                    }
                                                @endphp

                                                <div class="mb-1 text-sm">
                                                    @if($mixed)
                                                        <p class="text-nowrap font-bold">Mixed Currency</p>
                                                    @elseif($isFree)
                                                        <p class="text-nowrap font-bold">Free</p>
                                                    @elseif($isPartialFree)
                                                        <p class="text-nowrap font-bold">Free Options Available</p>
                                                    @else
                                                        <p class="text-nowrap">

                                                            @if($ed = $event->eventDates->first()->getCurrencyCode())
                                                                @if($countEventDates > 1)
                                                                    <span class="font-bold">From</span>
                                                                @endif
                                                                {{ $eventDate->getCurrencyCode() }}{{ $eventDate->getTotalTicketFees() }}
                                                            @endif

                                                            @if($countEventDates > 1)
                                                                @foreach($event->eventDates as $eventDate)
                                                                    <span
                                                                        class="font-bold">Lowest</span> {{ $eventDate->getCurrencyCode() }}{{ $lowest }}
                                                                @endforeach
                                                            @endif
                                                        </p>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="flex flex-col gap-y-2">
                                                <div class="flex items-center gap-x-1">
                                                    <x-fas-location-dot class="w-5 h-5 text-red-500"/>

                                                    @if($event->eventDates?->first()?->online)
                                                        <p>{{ __('This is an online event') }}</p>
                                                    @elseif($venue = $event->eventDates?->first()?->venue)
                                                        <div class="flex flex-col gap-y-0.5">
                                                            <p class="truncate">{{ $venue->name }}</p>
                                                            <p class="truncate">{{ $venue->city }}
                                                                , {{ $venue->country->name }}</p>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="flex items-center gap-x-2">
                                                    <x-fas-clock class="w-4 h-4 text-red-500"/>

                                                    @if($eventDate = $event->eventDates?->first())
                                                        <div class="flex flex-col gap-y-0.5">
                                                            <p class="truncate">
                                                                {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('l') }}
                                                                ,
                                                                Start {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a') }}
                                                            </p>

                                                            <p class="truncate">
                                                                Timezone: {{ \Carbon\Carbon::now()->timezone($event->eventtimezone ?? $timezone[0])->format('T') }}
                                                            </p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-pagination"></div>
            </div>
        @else
            <div class="flex items-center justify-center text-blue-500 p-4 text-xl font-bold">
                {{ __('No event found') }}
            </div>
        @endif

        <div class="m-4">
            <div class="flex justify-center">
                <h1 class="font-semibold text-2xl">Featured Events</h1>
            </div>

            @if(count($featuredEvents))
                <div
                    class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-4 gap-y-8 py-10 overflow-x-auto hide-scroll-bar">
                    @foreach($featuredEvents as $event)
                        @php
                            $country = $event->country;

                            if ($country) {
                                $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country->code);
                            } else {
                                $timezone[] = 'UTC';
                            }
                        @endphp

                        <div class="inline-block">
                            <div
                                class="relative h-96 overflow-hidden rounded-lg shadow-md bg-white hover:shadow-xl transition-shadow duration-300 ease-in-out"
                            >
                                <a
                                    class="flex flex-col justify-between h-full"
                                    href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                                        <span
                                            class="absolute top-2.5 shadow z-10 bg-white text-gray-700 rounded-l py-0.5 px-2 opacity-90 right-0">
                                            {{ $event->category->categoryTranslations->first()?->name ?? '' }}
                                        </span>

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
                                                    {{ \Carbon\Carbon::make($eventDate->startdate)->format('M') }}
                                                </p>

                                                <p class="pb-1">
                                                    {{ \Carbon\Carbon::make($eventDate->startdate)->format('d') }}
                                                </p>
                                            </div>

                                            @break
                                        @endif
                                    @endforeach

                                    <img
                                        class="w-full h-48"
                                        loading="lazy"
                                        src="{{ Storage::url('events/' . $event->image_name) }}"
                                        alt="{{ $event->eventTranslations->first()->name }}"
                                    />

                                    <p class="p-2 font-normal text-lg text-gray-700 dark:text-gray-400">
                                        {{ $event->eventTranslations->first()?->name }}
                                    </p>

                                    <div class="px-4">
                                        @if($countEventDates = count($event->eventDates))
                                            @php
                                                $ccy = $event->eventDates->first()->getCurrencyCode();
                                                $mixed = false;
                                                $lowest = $event->eventDates->first()?->getTotalTicketFees();
                                                $isFree = DB::table('eventic_event_date_ticket')
                                                    ->whereIn('eventdate_id', $event->eventDates->pluck('id')->toArray())
                                                    ->where('free', true)
                                                    ->exists();
                                                $isPartialFree = false;

                                                foreach ($event->eventDates as $ed) {
                                                    if ($ccy !== $ed->getCurrencyCode()) {
                                                        $mixed = true;
                                                    }

                                                    if ($ed->getTotalTicketFees() < $lowest) {
                                                        $lowest = $ed->getTotalTicketFees();
                                                    }

                                                    if ($ed->free) {
                                                        $isPartialFree = true;
                                                    }
                                                }
                                            @endphp

                                            <div class="mb-1 text-sm">
                                                @if($mixed)
                                                    <p class="text-nowrap font-bold">Mixed Currency</p>
                                                @elseif($isFree)
                                                    <p class="text-nowrap font-bold">Free</p>
                                                @elseif($isPartialFree)
                                                    <p class="text-nowrap font-bold">Free Options Available</p>
                                                @else
                                                    <p class="text-nowrap">

                                                        @if($ed = $event->eventDates->first()->getCurrencyCode())
                                                            @if($countEventDates > 1)
                                                                <span class="font-bold">From</span>
                                                            @endif
                                                            {{ $eventDate->getCurrencyCode() }}{{ $eventDate->getTotalTicketFees() }}
                                                        @endif

                                                        @if($countEventDates > 1)
                                                            @foreach($event->eventDates as $eventDate)
                                                                <span
                                                                    class="font-bold">Lowest</span> {{ $eventDate->getCurrencyCode() }}{{ $lowest }}
                                                            @endforeach
                                                        @endif
                                                    </p>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="flex flex-col gap-y-2">
                                            <div class="flex items-center gap-x-1">
                                                <x-fas-location-dot class="w-5 h-5 text-red-500"/>

                                                @if($event->eventDates?->first()?->online)
                                                    <p>{{ __('This is an online event') }}</p>
                                                @elseif($venue = $event->eventDates?->first()?->venue)
                                                    <div class="flex flex-col gap-y-0.5">
                                                        <p class="truncate">{{ $venue->name }}</p>
                                                        <p class="truncate">{{ $venue->city }}
                                                            , {{ $venue->country->name }}</p>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="flex items-center gap-x-2">
                                                <x-fas-clock class="w-4 h-4 text-red-500"/>

                                                @if($eventDate = $event->eventDates?->first())
                                                    <div class="flex flex-col gap-y-0.5">
                                                        <p class="truncate">
                                                            {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('l') }}
                                                            ,
                                                            Start {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a') }}
                                                        </p>

                                                        <p class="truncate">
                                                            Timezone: {{ \Carbon\Carbon::now()->timezone($event->eventtimezone ?? $timezone[0])->format('T') }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($featuredEvents->hasMorePages())
                    <div class="flex w-full items-center justify-center">
                        <x-button
                            primary
                            label="Load more"
                            spinner
                            wire:click.prevent="loadMore"
                        />
                    </div>
                @endif
            @else
                <div class="flex items-center justify-center text-blue-500 p-4 text-xl font-bold">
                    {{ __('No event found') }}
                </div>
            @endif
        </div>
    </main>

    @push('scripts')
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('searchDropdown', () => {
                    return {
                        isOpen: false,
                        openedWithKeyboard: false,
                        search: '',
                        countries: Object.values(@js($countries))
                    }
                });
            });

            new Swiper(".eventSlider", {
                slidesPerView: 4,
                spaceBetween: 10,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                mousewheel: true,
                keyboard: true,
            });
        </script>
    @endpush

</div>
