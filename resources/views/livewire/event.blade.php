@php
    use App\Models\CategoryTranslation;use Illuminate\Support\Carbon;use Illuminate\Support\Facades\DB;use Illuminate\Support\Facades\Storage;

    $eventCategoryTranslation = CategoryTranslation::whereTranslatableId($eventTranslation->event->category_id)
        ->where('locale', app()->getLocale())
        ->first();

    $event = $eventTranslation->event;
    $country = $event?->country?->code;

    $componentName = 'flag-4x3-' . strtolower($country);

    $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country);
@endphp

<div class="mt-36" x-data="{ selectedEventDateId: null }">
    <div class="w-full">
        <img src="{{ Storage::url('events/' . $event->image_name) }}" alt="Canyon Swing"
             loading="lazy"
             class="object-contain z-10 w-full relative opacity-100 p-8 h-[30rem]"/>

        <div class="absolute top-[13rem] opacity-75 bg-gradient-to-b blur-xl bg-cover h-[25rem] bg-no-repeat w-full"
             style="background-image: url({{ Storage::url('events/' . $event->image_name) }})"
        >
        </div>
    </div>

    <div
        class="flex flex-col divide-y-2 md:divide-x-2 md:flex-row justify-center rounded-lg shadow-lg bg-white container mx-auto">
        <div class="container mx-auto">
            <div class="mt-8 p-6">
                <div class="flex gap-x-4">
                    @foreach ($event->eventDates as $eventDate)
                        @if ($eventDate->isOnSale() && $event->eventDates->count() == 1)
                            <dl class="mb-4">
                                <dd>
                                    <div class="text-center">
                                        {{-- For the add to calendar link --}}
                                        @php
                                            $eventstartdate = '';
                                            $eventenddate = '';
                                            $eventlocation = $eventDate->venue ? $eventDate->venue->name . ': ' . $eventDate->venue->stringifyAddress : __('Online');

                                            // Use recurrent_startdate if recurrent is set
                                            $startDate = $eventDate->recurrent ? $eventDate->recurrent_startdate : $eventDate->startdate;
                                            $endDate = $eventDate->recurrent ? $eventDate->recurrent_enddate : $eventDate->enddate; // Check for recurrent_enddate
                                        @endphp

                                        @if ($startDate)
                                            <div class="inline-block">
                                                <div class="inline-block">
                                                    <span class="text-5xl">
                                                        {{ $startDate->timezone($event->eventtimezone ?? $timezone[0])->format('d') }}
                                                    </span>
                                                </div>
                                                <div class="inline-block mr-3">
                                                    <div>
                                                            <span
                                                                class="text-sm">{{ ucfirst($startDate->timezone($event->eventtimezone ?? $timezone[0])->format('M')) }}</span>
                                                    </div>
                                                    <div>
                                                            <span
                                                                class="text-sm">{{ $startDate->timezone($event->eventtimezone ?? $timezone[0])->format('Y') }}</span>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <span class="text-gray-500 font-bold">
                                                        {{ strtoupper($startDate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a')) }}
                                                        @if ($endDate && Carbon::make($endDate)->timezone($event->eventtimezone ?? $timezone[0])->equalTo($startDate->timezone($event->eventtimezone ?? $timezone[0])))
                                                            - {{ strtoupper($endDate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a')) }}
                                                        @endif
                                                    </span>
                                                </div>
                                            </div>
                                            @php
                                                $eventstartdate = $startDate->timezone($event->eventtimezone ?? $timezone[0])->format('F d, Y H:i');
                                            @endphp
                                        @endif

                                        @if ($endDate && !$endDate->timezone($event->eventtimezone ?? $timezone[0])->equalTo($startDate->timezone($event->eventtimezone ?? $timezone[0])))
                                            <div class="inline-block">
                                                <div class="inline-block">
                                                        <span
                                                            class="text-5xl">{{ $endDate->timezone($event->eventtimezone ?? $timezone[0])->format('d') }}</span>
                                                </div>
                                                <div class="inline-block">
                                                    <div><span
                                                            class="text-sm">{{ ucfirst($endDate->timezone($event->eventtimezone ?? $timezone[0])->format('M')) }}</span>
                                                    </div>
                                                    <div>
                                                            <span
                                                                class="text-sm">{{ $endDate->timezone($event->eventtimezone ?? $timezone[0])->format('Y') }}</span>
                                                    </div>
                                                </div>
                                                <div class="mb-2">
                                                    <span class="text-gray-500 font-bold">
                                                        {{ strtoupper($endDate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a')) }}
                                                    </span>
                                                </div>
                                            </div>
                                            @php
                                                $eventenddate = $endDate->timezone($event->eventtimezone ?? $timezone[0])->format('F d, Y H:i');
                                            @endphp
                                        @endif
                                    </div>
                                </dd>
                            </dl>

                        @elseif ($eventDate->isOnSale() && $event->eventDates->count() > 1 && $event->eventDates->every(fn($ed) => $ed->recurrent == 0))
                            <dl class="mb-4">
                                <dd>
                                    <div class="text-center flex flex-col gap-1">
                                        @if ($eventDate)
                                            @php
                                                $eventstartdate = '';
                                                $eventenddate = '';
                                                $eventlocation = $eventDate->venue ? $eventDate->venue->name . ': ' . $eventDate->venue->stringifyAddress : __('Online');
                                                $startDate = $eventDate->startdate;
                                                $endDate = $eventDate->enddate;
                                            @endphp

                                            @if ($startDate || $endDate)
                                                <div
                                                    class="inline-block cursor-pointer event-date"
                                                    @click="selectedEventDateId = selectedEventDateId === '{{ $eventDate->reference }}' ? null : '{{ $eventDate->reference }}'"
                                                    x-bind:class="selectedEventDateId == '{{ $eventDate->reference }}' ? 'bg-yellow-500' : 'bg-gray-50'"
                                                    wire:click="updateSelectedEventDate('{{ $eventDate->startdate }}', '{{ $eventDate->id }}')"
                                                    style="border: 1px solid #ccc; padding: 10px; border-radius: 5px;"
                                                >
                                                    <div class="flex space-x-4">
                                                        <div class="inline-block">
                                                            @if ($startDate)
                                                                <span class="text-5xl">
                                                                        {{ $startDate->timezone($event->eventtimezone ?? $timezone[0])->format('d') }}
                                                                    </span>
                                                                <div class="text-sm">
                                                                    {{ ucfirst($startDate->timezone($event->eventtimezone ?? $timezone[0])->format('M')) }}
                                                                </div>
                                                                <div class="text-sm">
                                                                    {{ $startDate->timezone($event->eventtimezone ?? $timezone[0])->format('Y') }}
                                                                </div>
                                                                <div class="mb-2 text-gray-500 font-bold">
                                                                    {{ strtoupper($startDate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a')) }}
                                                                    @if ($endDate && Carbon::make($endDate)->timezone($event->eventtimezone ?? $timezone[0])->equalTo($startDate->timezone($event->eventtimezone ?? $timezone[0])))
                                                                        - {{ strtoupper($endDate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a')) }}
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        </div>

                                                        @if ($endDate && !$endDate->timezone($event->eventtimezone ?? $timezone[0])->equalTo($startDate->timezone($event->eventtimezone ?? $timezone[0])))
                                                            <div class="inline-block">
                                                                    <span
                                                                        class="text-5xl">{{ $endDate->timezone($event->eventtimezone ?? $timezone[0])->format('d') }}</span>
                                                                <div>
                                                                        <span
                                                                            class="text-sm">{{ ucfirst($endDate->timezone($event->eventtimezone ?? $timezone[0])->format('M')) }}</span>
                                                                </div>
                                                                <div>
                                                                        <span
                                                                            class="text-sm">{{ $endDate->timezone($event->eventtimezone ?? $timezone[0])->format('Y') }}</span>
                                                                </div>
                                                                <div class="mb-2">
                                                                <span class="text-gray-500 font-bold">
                                                                    {{ strtoupper($endDate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a')) }}
                                                                </span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                            @endif
                                        @endif

                                    </div>
                                </dd>
                            </dl>
                        @endif
                    @endforeach
                    <div>
                        <h1 class="text-4xl font-bold mb-4">{{ $eventTranslation->name }}</h1>

                        <div class="flex items-center mb-4">
                            <div class="flex gap-x-1">
                                @for($i = 0; $i < $averageRating; $i++)
                                    <x-heroicon-s-star class="w-4 h-4 text-blue-600"/>
                                @endfor

                                @for($i = $averageRating; $i < 5; $i++)
                                    <x-heroicon-o-star class="w-4 h-4"/>
                                @endfor
                            </div>

                            <span class="ml-2 text-gray-600">({{ count($reviews) }} review)</span>
                        </div>
                        <div x-data="{modalIsOpen: false}">
                            <button @click="modalIsOpen = true" type="button"
                                    class="cursor-pointer bg-yellow-400 whitespace-nowrap rounded-md px-4 py-2 text-center text-sm font-medium tracking-wide text-white transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700 active:opacity-100 active:outline-offset-0 dark:bg-sky-600 dark:text-white dark:focus-visible:outline-sky-600">
                                {{ __('Add to calendar') }}
                            </button>

                            <div x-cloak x-show="modalIsOpen" x-transition.opacity.duration.200ms
                                 x-trap.inert.noscroll="modalIsOpen"
                                 @keydown.esc.window="modalIsOpen = false"
                                 @click.self="modalIsOpen = false"
                                 class="fixed inset-0 z-30 flex items-end justify-center bg-black/20 p-4 pb-8 backdrop-blur-md sm:items-center lg:p-8"
                                 role="dialog" aria-modal="true"
                                 aria-labelledby="defaultModalTitle">
                                <!-- Modal Dialog -->
                                <div x-show="modalIsOpen"
                                     x-transition:enter="transition ease-out duration-200 delay-100 motion-reduce:transition-opacity"
                                     x-transition:enter-start="opacity-0 scale-50"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="flex max-w-lg min-w-96 flex-col gap-4 overflow-hidden rounded-md border border-zinc-300 bg-red-50 text-neutral-600 dark:border-zinc-700 dark:bg-zinc-800 dark:text-zinc-200">
                                    <!-- Dialog Header -->
                                    <div
                                        class="flex items-center justify-between border-b border-zinc-300 bg-zinc-100/60 p-4 dark:border-zinc-700 dark:bg-zinc-900/20">
                                        <h3 id="defaultModalTitle"
                                            class="font-semibold flex gap-x-2 items-center tracking-wide text-neutral-900 dark:text-zinc-50">
                                            <x-fas-calendar-plus class="w-4 h-4"/>

                                            {{ __('Add to calendar') }}
                                        </h3>
                                        <button @click="modalIsOpen = false"
                                                aria-label="close modal">
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 24 24" aria-hidden="true"
                                                 stroke="currentColor" fill="none"
                                                 stroke-width="1.4" class="w-5 h-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <livewire:add-to-calendar :event="$event"/>

                                    <div
                                        class="flex flex-col-reverse justify-between gap-2 border-t border-zinc-300 bg-zinc-100/60 p-4 dark:border-zinc-700 dark:bg-zinc-900/20 sm:flex-row sm:items-center md:justify-end">
                                        <button @click="modalIsOpen = false" type="button"
                                                class="cursor-pointer whitespace-nowrap rounded-md bg-sky-700 px-4 py-2 text-center text-sm font-medium tracking-wide text-white transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-sky-700 active:opacity-100 active:outline-offset-0 dark:bg-sky-600 dark:text-white dark:focus-visible:outline-sky-600">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h1 class="my-4">Description</h1>

                <span class="text-gray-700">
                    {!! $eventTranslation->description !!}
                </span>

                <hr class="my-4 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10"/>

                @if($eventCategoryTranslation)
                    <div class="flex justify-between items-center">
                        <div class="text-gray-700 font-semibold">
                            Category:
                        </div>

                        <div>{{ $eventCategoryTranslation?->name }}</div>
                    </div>
                @endif

                @if($country)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-semibold mr-2">Country:</span>

                        <div class="flex items-center gap-x-2">
                            <x-dynamic-component :component="$componentName" class="inline-block h-8 w-8 rounded-md"/>

                            {{ $country }}
                        </div>
                    </div>
                @endif

                @if(count($event->languages))
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-semibold mr-2">Languages:</span>

                        <div class="flex items-center gap-x-2">
                            @foreach($event->languages as $language)
                                <p>{{ $language->name }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(count($event->subtitles))
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-semibold mr-2">Subtitles:</span>

                        <p class="text-gray-500">
                            {{ $event->displaySubtitles() }}
                        </p>
                    </div>
                @endif

                @if($event->artists)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-semibold mr-2">Artists:</span>

                        <p class="text-gray-500">
                            {{ $event->artists }}
                        </p>
                    </div>
                @endif

                @if($event->year)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-semibold mr-2">Year:</span>

                        <p class="text-gray-500">
                            {{ $event->year }}
                        </p>
                    </div>
                @endif

                @if(count($event->audiences))
                    <div class="flex justify-between items-center">
                        <span class="text-gray-700 font-semibold mr-2">Audiences:</span>

                        <div class="flex gap-x-1">
                            @foreach($event->audiences as $audience)
                                <img
                                    class="h-4 w-4"
                                    src="{{ Storage::url('audiences/' . $audience->image_name) }}"
                                    alt="{{ $audience->image_name }}"
                                />
                            @endforeach
                        </div>
                    </div>
                @endif

                <hr class="my-4 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10"/>
            </div>

            @if(count($event->eventImages))

            @endif

            @if($event->youtubeurl)
                <div class="mt-5">
                    <p class="text-muted">{{__('Video')}}</p>

                    <div class="mr-8">
                        <iframe class="w-full border-0" height="500"
                                src="https://www.youtube.com/embed/{{$event->youtubeurl}}"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                </div>
            @endif

            @if($event->hasContactAndSocialMedia())
                <div class="mt-8 p-6">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-bold mb-4">Contact & Social media</h2>
                    </div>

                    <ul class="flex flex-wrap">
                        @if ($event->externallink)
                            <li class="w-full md:w-1/2">
                                <a href="{{ Str::startsWith($event->externallink, ['http://', 'https://']) ? $event->externallink : 'http://' . $event->externallink }}"
                                   class="pl-4 inline-flex items-center" target="_blank">
                                    <x-fas-globe class="mr-2 w-4 h-4"/>
                                    <span>{{ $event->externallink }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($event->email)
                            <li class="w-full md:w-1/2">
                                <a href="mailto:{{ $event->email }}" class="pl-4 inline-flex items-center">
                                    <x-fas-at class="mr-2 w-4 h-4"/>
                                    <span>{{ $event->email }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($event->phonenumber)
                            <li class="w-full md:w-1/2">
                                <a href="tel:{{ $event->phonenumber }}" class="pl-4 inline-flex items-center">
                                    <x-fas-phone class="w-4 h-4 mr-2"/>
                                    <span>{{ $event->phonenumber }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($event->facebook)
                            <li class="w-full md:w-1/2">
                                <a href="{{ Str::startsWith($event->facebook, ['http://', 'https://']) ? $event->facebook : 'http://' . $event->facebook }}"
                                   class="pl-4 inline-flex items-center" target="_blank">
                                    <x-fab-facebook class="w-4 h-4 mr-2"/>
                                    <span>{{ $event->facebook }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($event->twitter)
                            <li class="w-full md:w-1/2">
                                <a href="{{ Str::startsWith($event->twitter, ['http://', 'https://']) ? $event->twitter : 'http://' . $event->twitter }}"
                                   class="pl-4 inline-flex items-center" target="_blank">
                                    <x-fab-twitter class="w-4 h-4 mr-2"/>
                                    <span>{{ $event->twitter }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($event->googleplus)
                            <li class="w-full md:w-1/2">
                                <a href="{{ Str::startsWith($event->googleplus, ['http://', 'https://']) ? $event->googleplus : 'http://' . $event->googleplus }}"
                                   class="pl-4 inline-flex items-center" target="_blank">
                                    <x-fab-google-plus class="w-4 h-4 mr-2"/>
                                    <span>{{ $event->googleplus }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($event->instagram)
                            <li class="w-full md:w-1/2">
                                <a href="{{ Str::startsWith($event->instagram, ['http://', 'https://']) ? $event->instagram : 'http://' . $event->instagram }}"
                                   class="pl-4 inline-flex items-center" target="_blank">
                                    <x-fab-instagram class="w-4 h-4 mr-2"/>
                                    <span>{{ $event->instagram }}</span>
                                </a>
                            </li>
                        @endif
                        @if ($event->linkedin)
                            <li class="w-full md:w-1/2">
                                <a href="{{ Str::startsWith($event->linkedin, ['http://', 'https://']) ? $event->linkedin : 'http://' . $event->linkedin }}"
                                   class="pl-4 inline-flex items-center" target="_blank">
                                    <x-fab-linkedin class="w-4 h-4 mr-2"/>
                                    <span>{{ $event->linkedin }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>

                </div>
            @endif

            <div class="mt-8 p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold mb-4">{{ count($reviews) }} review</h2>

                    @if(auth()->user()?->hasRole('ROLE_ATTENDEE'))
                        @if($event->reviews()->where('user_id', auth()->id())->exists())
                            <x-button
                                href="{{ route('filament.admin.resources.reviews.index') }}"
                                label="MY REVIEWS" icon="star"
                                teal
                            />
                        @else
                            <x-button
                                href="{{ route('add-review', ['slug' => $eventTranslation->slug]) }}"
                                label="ADD YOUR REVIEW" icon="star"
                                teal
                            />
                        @endif
                    @endif
                </div>

                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <div class="flex gap-x-1">
                            @for($i = 0; $i < $averageRating; $i++)
                                <x-heroicon-s-star class="w-4 h-4 text-blue-600"/>
                            @endfor

                            @for($i = $averageRating; $i < 5; $i++)
                                <x-heroicon-o-star class="w-4 h-4"/>
                            @endfor
                        </div>

                        <span class="ml-2 text-gray-600">{{ (int) $averageRating }} out of 5 stars</span>
                    </div>

                    <div class="w-full flex flex-col gap-y-2 my-8">
                        @foreach ([5, 4, 3, 2, 1] as $rating)
                            <div class="flex items-center gap-x-3 mb-2">
                                <span class="text-gray-600 mr-2 w-auto whitespace-nowrap">{{ $rating }} stars</span>
                                <div class="flex-1 bg-gray-200 h-4">
                                    <div class="bg-blue-500 h-4 rounded"
                                         style="width: {{ $ratingPercentages[$rating] }}%;"></div>
                                </div>
                                <span class="text-gray-600 ml-2 w-8 whitespace-nowrap">{{ $ratingPercentages[$rating] }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-y-4">
                    @foreach($reviews as $review)
                        <div class="flex flex-col gap-y-4">
                            <div class="flex items-center gap-x-2">
                                <x-avatar sm :src="$review->user->getFilamentAvatarUrl()"/>

                                <p class="text-lg">{{ $review->user->getFullNameAttribute() }}</p>
                            </div>

                            <div>
                                <div class="flex gap-x-2 items-center">
                                    <div class="flex gap-x-1">
                                        @for($i = 0; $i < $review->rating; $i++)
                                            <x-heroicon-s-star class="w-4 h-4 text-blue-600"/>
                                        @endfor

                                        @for($i = $review->rating; $i < 5; $i++)
                                            <x-heroicon-o-star class="w-4 h-4"/>
                                        @endfor
                                    </div>

                                    <p class="text-base text-muted">{{ $review->rating }} out of 5 stars</p>
                                </div>

                                <p class="text-sm text-muted">{{ $review->created_at }}</p>
                            </div>

                            <div class="flex flex-col gap-y-1">
                                <h1 class="font-semibold text-base">{{ $review->headline }}</h1>

                                <p class="text-sm">{{ $review->details }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="container md:w-[30%] p-8">
            @if($event->hasAnEventDateOnSale())
                @if ($event->hasTwoOrMoreEventDatesOnSale())
                    @php
                        $eventDatesCalendar = [];
                        $eventTimezone = $event->eventtimezone ?? $timezone[0];
                    @endphp

                    @foreach ($event->eventDates as $eventDate)
                        @if ($eventDate->isOnSale())
                            @php
                                $eventDatesCalendar[] = [
                                    'Date' => Carbon::make($eventDate->startdate)->timezone($eventTimezone)->format('Y-m-d H:i:s'),
                                    'Title' => Carbon::make($eventDate->startdate)->timezone($eventTimezone)->format('Y-m-d H:i:s'),
                                    'Link' => $eventDate->reference,
                                ];
                            @endphp
                        @endif
                    @endforeach

                    <div id="event-dates-calendar" class="mt-5"
                         data-event-dates="{{ json_encode($eventDatesCalendar) }}"></div>

                    @if(auth()->check() && auth()->user()?->hasRole('ROLE_ATTENDEE'))
                        <button id="add-to-cart-button" type="button" class="btn btn-primary w-full mt-3 mb-3"><i
                                class="fas fa-cart-plus"></i> {{ __('Add to cart') }}</button>
                    @endif
                @endif

                <div>
                    @if ($eventDate)
                        @if ($eventDate->isOnSale())
                            <dl class="mt-4">
                                <dt class="text-gray-500">{{ __('Tickets') }}</dt>

                                <dd class="mr-0 my-2">
                                    <div class="overflow-x-auto">
                                        @if ($event->eventDates->count() > 1)
                                            <div class="overflow-x-auto">
                                                <p id="default-message" x-show="!selectedEventDateId">
                                                    Please select your event date.
                                                </p>

                                                @foreach ($event->eventDates as $eventDate)
                                                    @php
                                                        $tickets = $eventDate->eventDateTickets->where('active', true); // Get active tickets for the event date
                                                    @endphp

                                                    <div class="inline-block cursor-pointer event-date"
                                                         x-show="selectedEventDateId === '{{ $eventDate->reference }}'">
                                                        Event
                                                        Date {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('d M Y') }}
                                                    </div>

                                                    <!-- Ticket Info for the event date -->
                                                    <div class="ticket-info"
                                                         x-show="@js($event->eventDates->count()) < 2">
                                                        @if ($tickets->isNotEmpty())
                                                            <table class="table-auto w-full">
                                                                <tbody>
                                                                @foreach ($tickets as $ticket)
                                                                    <tr class="bg-gray-200 flex w-full justify-between my-2 px-0.5">
                                                                        <td class="border-t-0">{{ $ticket->name }}</td>
                                                                        <td>
                                                                            @if($ticket->free)
                                                                                {{ 'Free' }}
                                                                            @else
                                                                                {{ $ticket->currency->ccy }}
                                                                            @endif
                                                                        </td>
                                                                        <td class="border-t-0 text-left">
                                                                            <p class="text-nowrap font-semibold">
                                                                                @if($ticket->free == 1)
                                                                                    Free
                                                                                @elseif($ticket->promotionalprice)
                                                                                    @php
                                                                                        $isStartDate = $ticket->salesstartdate?->timezone($event->eventtimezone ?? $timezone[0])?->lessThanOrEqualTo(now());
                                                                                        $isEndDate = $ticket->salesenddate?->timezone($event->eventtimezone ?? $timezone[0])?->greaterThanOrEqualTo(now());
                                                                                    @endphp

                                                                                    @if($isStartDate && $isEndDate)
                                                                                        <del
                                                                                            class="font-bold">{{ $ticket->price }}</del> {{ $ticket->promotionalprice }}
                                                                                    @else
                                                                                        {{ $ticket->price }}
                                                                                    @endif
                                                                                @else
                                                                                    {{ $ticket->price }}
                                                                                @endif
                                                                            </p>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        @else
                                                            <p>No available tickets for this date.</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>

                                        @else
                                            <table class="table-auto w-full">
                                                <tbody>
                                                @foreach ($eventDate->eventDateTickets as $ticket)
                                                    @if ($ticket->active)
                                                        <tr class="bg-gray-200 flex w-full justify-between my-2 px-0.5">
                                                            <td class="border-t-0">
                                                                {{ $ticket->name }}
                                                            </td>

                                                            <td>
                                                                @if($ticket->free)
                                                                    {{ 'Free' }}
                                                                @else
                                                                    {{ $ticket->currency->ccy }}
                                                                @endif
                                                            </td>

                                                            <td class="border-t-0 text-left">
                                                                <p class="text-nowrap font-semibold">
                                                                    @if($ticket->free == 1)
                                                                        Free
                                                                    @elseif($ticket->promotionalprice)
                                                                        @php
                                                                            $isStartDate = $ticket->salesstartdate?->timezone($event->eventtimezone ?? $timezone[0])?->lessThanOrEqualTo(now());
                                                                            $isEndDate = $ticket->salesenddate?->timezone($event->eventtimezone ?? $timezone[0])?->greaterThanOrEqualTo(now());
                                                                        @endphp

                                                                        @if($isStartDate && $isEndDate)
                                                                            <del
                                                                                class="font-bold">{{ $ticket->price }} </del>
                                                                            {{ $ticket->promotionalprice }}
                                                                        @else
                                                                            {{ $ticket->price }}
                                                                        @endif
                                                                    @else
                                                                        {{ $ticket->price }}
                                                                    @endif
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>

                                    @if ($eventDate->recurrent)
                                        <div class="form-group my-2">
                                            <x-datetime-picker
                                                wire:model.live="eventDatePick"
                                                label="Event Date"
                                                placeholder="Select Event Date"
                                                without-timezone
                                                without-time
                                                :min="$eventDate->recurrent_startdate?->timezone($event->eventtimezone ?? $timezone[0])->format('Y-m-d')"
                                                :max="$eventDate->recurrent_enddate?->timezone($event->eventtimezone ?? $timezone[0])->format('Y-m-d')"
                                            />
                                        </div>
                                    @endif

                                    <div class="items-center justify-center w-full py-2 hidden sm:flex">
                                        <x-button
                                            wire:click="buyTicket"
                                            spinner="buyTicket"
                                            yellow
                                            label="Buy Tickets"
                                        />
                                    </div>

                                    <div class="sm:hidden">
                                        <div class="fixed inset-x-0 bottom-0 p-4 bg-white">
                                            <div
                                                class="bg-orange-600 hover:bg-orange-700 text-white font-bold w-full rounded-full text-lg focus:outline-none focus:ring-4 focus:ring-orange-300"
                                            >
                                                <x-button
                                                    negative
                                                    wire:click="buyTicket"
                                                    spinner="buyTicket"
                                                    class="py-3 w-full text-center"
                                                >
                                                    Get tickets
                                                </x-button>
                                            </div>
                                        </div>
                                    </div>

                                </dd>
                            </dl>

                            <x-modal-card :title="$eventTranslation->name" name="cardModal" blur="md" width="5xl">
                                <div
                                    class="grid grid-cols-1 md:gap-2 lg:gap-4 lg:grid-cols-8 md:grid-cols-4 animate-in fade-in">
                                    <div class="md:col-span-2 lg:col-span-5">
                                        <div class="flex flex-col gap-y-1 font-medium text-base">
                                            @if($eventDateId)
                                                @php
                                                    // Attempt to fetch the selected event date where 'startdate' is less than or equal to the picked date
                                                    $selectedEventDate = $event->eventDates()->where('id', $eventDateId)->first();
                                                @endphp

                                                {{-- Check if $selectedEventDate is not null --}}
                                                @if($selectedEventDate)
                                                    @php
                                                        // Check if the event is recurrent and use the appropriate date fields
                                                        if ($selectedEventDate->recurrent) {
                                                            $startDate = Carbon::make($selectedEventDate->recurrent_startdate)->timezone($event->eventtimezone ?? $timezone[0]);
                                                            $endDate = Carbon::make($selectedEventDate->recurrent_enddate)->timezone($event->eventtimezone ?? $timezone[0]);
                                                        } else {
                                                            $startDate = Carbon::make($selectedEventDate->startdate)->timezone($event->eventtimezone ?? $timezone[0]);
                                                            $endDate = Carbon::make($selectedEventDate->enddate)->timezone($event->eventtimezone ?? $timezone[0]);
                                                        }
                                                    @endphp

                                                    {{-- Check if startDate and endDate are not null before trying to format them --}}
                                                    @if($startDate && $endDate)
                                                        <p>
                                                            {{ $startDate->format('F d Y') }}
                                                            - {{ $endDate->format('F d Y') }}
                                                            ({{ $startDate->format('h:i A') }}
                                                            - {{ $endDate->format('h:i A') }})
                                                        </p>
                                                    @else
                                                        <p>Start date or end date is missing for the selected
                                                            event.</p>
                                                    @endif
                                                @else
                                                    <p>No event found for the selected date ID.</p>
                                                @endif
                                            @else
                                                {{-- Handle case where no eventDateId is provided --}}
                                                @if($eventDate)
                                                    @php
                                                        // Check if the event is recurrent and use the appropriate date fields
                                                        if ($eventDate->recurrent) {
                                                            $startDate = Carbon::make($eventDate->recurrent_startdate)->timezone($event->eventtimezone ?? $timezone[0]);
                                                            $endDate = Carbon::make($eventDate->recurrent_enddate)->timezone($event->eventtimezone ?? $timezone[0]);
                                                        } else {
                                                            $startDate = Carbon::make($eventDate->startdate)->timezone($event->eventtimezone ?? $timezone[0]);
                                                            $endDate = Carbon::make($eventDate->enddate)->timezone($event->eventtimezone ?? $timezone[0]);
                                                        }
                                                    @endphp

                                                    {{-- Check if startDate and endDate are not null before trying to format them --}}
                                                    @if($startDate && $endDate)
                                                        <p>
                                                            {{ $startDate->format('F d Y') }}
                                                            - {{ $endDate->format('F d Y') }}
                                                            ({{ $startDate->format('h:i A') }}
                                                            - {{ $endDate->format('h:i A') }})
                                                        </p>
                                                    @else
                                                        <p>Start date or end date is missing for this event.</p>
                                                    @endif
                                                @else
                                                    <p>No event date available.</p>
                                                @endif
                                            @endif
                                            <p>
                                                Selected Date:
                                                @if($eventDatePick)
                                                    {{ Carbon::make($eventDatePick)->timezone($event->eventtimezone ?? $timezone[0])->format('F d Y') }}
                                                    {{-- Adjusting time display to use recurrent fields if necessary --}}
                                                    @if($eventDateId)
                                                        @php
                                                            $selectedEventDate = $event->eventDates()->where('id', $eventDateId)->first();
                                                        @endphp
                                                        @if($selectedEventDate)
                                                            @if($selectedEventDate->recurrent)
                                                                {{-- Use recurrent fields for time display --}}
                                                                ({{ Carbon::make($selectedEventDate->recurrent_startdate)->timezone($event->eventtimezone ?? $timezone[0])->format('h:i A') }}
                                                                - {{ Carbon::make($selectedEventDate->recurrent_enddate)->timezone($event->eventtimezone ?? $timezone[0])->format('h:i A') }}
                                                                )
                                                            @else
                                                                {{-- Use standard start and end dates for time display --}}
                                                                ({{ Carbon::make($selectedEventDate->startdate)->timezone($event->eventtimezone ?? $timezone[0])->format('h:i A') }}
                                                                - {{ Carbon::make($selectedEventDate->enddate)->timezone($event->eventtimezone ?? $timezone[0])->format('h:i A') }}
                                                                )
                                                            @endif
                                                        @endif
                                                    @else
                                                        {{-- Fallback to eventDate if eventDateId is not provided --}}
                                                        @if($eventDate)
                                                            @if($eventDate->recurrent)
                                                                {{-- Use recurrent fields for time display --}}
                                                                ({{ Carbon::make($eventDate->recurrent_startdate)->timezone($event->eventtimezone ?? $timezone[0])->format('h:i A') }}
                                                                - {{ Carbon::make($eventDate->recurrent_enddate)->timezone($event->eventtimezone ?? $timezone[0])->format('h:i A') }}
                                                                )
                                                            @else
                                                                {{-- Use standard start and end dates for time display --}}
                                                                ({{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('h:i A') }}
                                                                - {{ $eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('h:i A') }}
                                                                )
                                            @endif
                                            @else
                                                <p>No event date available.</p>
                                                @endif
                                                @endif
                                                @endif
                                                </p>

                                        </div>


                                        <div class="px-8 py-4">
                                            <x-input
                                                label="Promo Code"
                                                wire:model="promoCode"
                                                placeholder="Promo Code"
                                            >
                                                <x-slot name="append">
                                                    <x-button
                                                        class="h-full"
                                                        label="Apply"
                                                        rounded="rounded-r-md"
                                                        primary
                                                        spinner="promoApply"
                                                        wire:click="promoApply"
                                                        flat
                                                    />
                                                </x-slot>
                                            </x-input>

                                            @if($couponType)
                                                <x-badge flat red :label="$promoCode" class="mt-2">
                                                    <x-slot name="append" class="relative flex items-center w-2 h-2">
                                                        <badge type="badge" class="cursor-pointer" wire:click="clearPromoCode">
                                                            <x-icon name="x-mark" class="w-4 h-4" wire:loading.remove />
                                                            <x-icon name="arrow-path" wire:loading class="animate-spin h-4 w-4 text-white" />
                                                        </badge>
                                                    </x-slot>
                                                </x-badge>
                                            @endif

                                        </div>

                                        <div class="flex flex-col gap-y-2 divide-y-2 my-4 mx-2">
                                            @foreach($event->eventDates as $ed)
                                                @if($eventDateId)
                                                    @if($eventDateId == $ed->id)
                                                        @foreach ($ed->eventDateTickets as $eventTicket)
                                                            @if ($eventTicket->active)
                                                                <x-card
                                                                    class="{{ $ccy !== null && $ccy !== $eventTicket->currency->ccy ? '!bg-gray-50' : '' }}">
                                                                    <div
                                                                        class="p-1 flex justify-between"
                                                                    >
                                                                        <div class="flex flex-col gap-y-4 w-full">
                                                                            <p class="border-t-0 flex gap-x-2"
                                                                               style="width: 75%;">
                                                                                {{ $eventTicket->name }}

                                                                                @if($eventTicket->description)
                                                                                    <x-fas-info
                                                                                        x-tooltip.raw="{{ $eventTicket->description }}"
                                                                                        class="h-5 w-5 bg-blue-500 p-1 rounded-full text-white"
                                                                                    />
                                                                                @endif
                                                                            </p>

                                                                            <div class="font-semibold">
                                                                                @if (!$eventTicket->isOnSale())
                                                                                    <span
                                                                                        class="badge {{ $eventTicket->stringifyStatusClass() }}">No tickets on sales</span>
                                                                                @elseif($eventTicket->free)
                                                                                    {{ __('Free') }}
                                                                                @elseif($eventTicket->promotionalprice)
                                                                                    @php
                                                                                        $isStartDate = $eventTicket->salesstartdate?->timezone($event->eventtimezone ?? $timezone[0])?->lessThanOrEqualTo(now());
                                                                                        $isEndDate = $eventTicket->salesenddate?->timezone($event->eventtimezone ?? $timezone[0])?->greaterThanOrEqualTo(now());
                                                                                    @endphp

                                                                                    @if($isStartDate && $isEndDate)
                                                                                        <del class="text-gray-500">
                                                                                            {{ $eventTicket->currency->ccy }} {{ $eventTicket->price }}
                                                                                        </del>

                                                                                        <span>
                                                                                            @if($eventTicket->currency_symbol_position === 'left')
                                                                                                {{ $eventTicket->currency->ccy }}
                                                                                            @endif
                                                                                            {{ $eventTicket->price - $eventTicket->promotionalprice }}
                                                                                            @if($eventTicket->currency_symbol_position === 'right')
                                                                                                {{ $eventTicket->currency->ccy }}
                                                                                            @endif
                                                                                        </span>
                                                                                    @else
                                                                                        <span>
                                                                                          {{ $eventTicket->currency->ccy }} {{ $eventTicket->price }}
                                                                                        </span>
                                                                                    @endif
                                                                                @else
                                                                                    <p class="text-gray-500">
                                                                                        @if($eventTicket->currency_symbol_position === 'left')
                                                                                            {{ $eventTicket->currency->ccy }}
                                                                                        @endif
                                                                                        {{ $eventTicket->price }}
                                                                                        @if($eventTicket->currency_symbol_position === 'right')
                                                                                            {{ $eventTicket->currency->ccy }}
                                                                                        @endif
                                                                                    </p>
                                                                                @endif
                                                                            </div>
                                                                        </div>

                                                                        <div class="w-20">
                                                                            @php
                                                                                $options = $eventTicket->ticketsperattendee
                                                                                            ? range(0, $eventTicket->ticketsperattendee)
                                                                                            : ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
                                                                            @endphp

                                                                            <x-native-select
                                                                                label="Quantity"
                                                                                placeholder=""
                                                                                wire:model.live="quantity.{{ $eventTicket->id }}"
                                                                                :disabled="($ccy !== null && $ccy !== $eventTicket->currency->ccy) || !$eventTicket->isOnSale()"
                                                                                :options="$options"
                                                                            />
                                                                        </div>
                                                                    </div>
                                                                </x-card>
                                                            @endif
                                                        @endforeach
                                                    @endif
                                                @elseif ($ed->isOnSale())
                                                    @foreach ($ed->eventDateTickets as $eventTicket)
                                                        @if ($eventTicket->active)
                                                            <x-card
                                                                class="{{ $ccy !== null && $ccy !== $eventTicket->currency->ccy ? '!bg-gray-50' : '' }}">
                                                                <div
                                                                    class="p-1 flex justify-between"
                                                                >
                                                                    <div class="flex flex-col gap-y-4 w-full">
                                                                        <p class="border-t-0 flex gap-x-2"
                                                                           style="width: 75%;">
                                                                            {{ $eventTicket->name }}

                                                                            @if($eventTicket->description)
                                                                                <x-fas-info
                                                                                    x-tooltip.raw="{{ $eventTicket->description }}"
                                                                                    class="h-5 w-5 bg-blue-500 p-1 rounded-full text-white"
                                                                                />
                                                                            @endif
                                                                        </p>

                                                                        <div class="font-semibold">
                                                                            @if (!$eventTicket->isOnSale())
                                                                                <span
                                                                                    class="badge {{ $eventTicket->stringifyStatusClass() }}">{{ __($eventTicket->stringifyStatus()) }}</span>
                                                                            @elseif($eventTicket->free)
                                                                                {{ __('Free') }}
                                                                            @elseif($eventTicket->promotionalprice)
                                                                                @php
                                                                                    $isStartDate = $eventTicket->salesstartdate?->timezone($event->eventtimezone ?? $timezone[0])?->lessThanOrEqualTo(now());
                                                                                    $isEndDate = $eventTicket->salesenddate?->timezone($event->eventtimezone ?? $timezone[0])?->greaterThanOrEqualTo(now());
                                                                                @endphp

                                                                                @if($isStartDate && $isEndDate)
                                                                                    <del class="text-gray-500">
                                                                                        {{ $eventTicket->currency->ccy }} {{ $eventTicket->price }}
                                                                                    </del>
                                                                                    <span>
                                                                                            @if($eventTicket->currency_symbol_position === 'left')
                                                                                            {{ $eventTicket->currency->ccy }}
                                                                                        @endif
                                                                                        {{-- {{ $eventTicket->price - $eventTicket->promotionalprice }} --}} <!-- Display the discounted price-->
                                                                                        {{ $eventTicket->promotionalprice }} <!-- Display the promotional price -->
                                                                                        @if($eventTicket->currency_symbol_position === 'right')
                                                                                            {{ $eventTicket->currency->ccy }}
                                                                                        @endif
                                                                                        </span>
                                                                                @else
                                                                                    <span>
                                                                                          {{ $eventTicket->currency->ccy }} {{ $eventTicket->price }}
                                                                                        </span>
                                                                                @endif
                                                                            @else
                                                                                <p class="text-gray-500">
                                                                                    @if($eventTicket->currency_symbol_position === 'left')
                                                                                        {{ $eventTicket->currency->ccy }}
                                                                                    @endif
                                                                                    {{ $eventTicket->price }}
                                                                                    @if($eventTicket->currency_symbol_position === 'right')
                                                                                        {{ $eventTicket->currency->ccy }}
                                                                                    @endif
                                                                                </p>
                                                                            @endif
                                                                        </div>
                                                                    </div>

                                                                    <div class="w-20">
                                                                        @php
                                                                            $options = $eventTicket->ticketsperattendee
                                                                                        ? range(0, $eventTicket->ticketsperattendee)
                                                                                        : ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '10'];
                                                                        @endphp

                                                                        <x-native-select
                                                                            label="Quantity"
                                                                            placeholder=""
                                                                            wire:model.live="quantity.{{ $eventTicket->id }}"
                                                                            :disabled="$ccy !== null && $ccy !== $eventTicket->currency->ccy"
                                                                            :options="$options"
                                                                        />
                                                                    </div>
                                                                </div>
                                                            </x-card>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </div>
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
                                                @php
                                                    $subtotal = 0; $fee = 0;
                                                    $now = now()->timezone($event->eventtimezone ?? $timezone);
                                                @endphp

                                                @foreach($quantity as $id => $value)
                                                    @if($value > 0)
                                                        @php
                                                            $eventDateTicket = \App\Models\EventDateTicket::find($id);
                                                            $originalPrice = $eventDateTicket->free ? 0 : $eventDateTicket->price;
                                                            $price = $eventDateTicket->free ? 0 : $eventDateTicket->price;

                                                            if ($eventDateTicket->promotionalprice) {
                                                                $isStartDate = $eventDateTicket->salesstartdate?->timezone($event->eventtimezone ?? $timezone[0])?->lessThanOrEqualTo($now);
                                                                $isEndDate = $eventDateTicket->salesenddate?->timezone($event->eventtimezone ?? $timezone[0])?->greaterThanOrEqualTo($now);
                                                                if ($isStartDate && $isEndDate) {
                                                                    if ($eventDateTicket->promotionalprice) {
                                                                        $price = $eventDateTicket->promotionalprice;
                                                                        $originalPrice = $eventDateTicket->promotionalprice;
                                                                    } else {
                                                                        $price = $eventDateTicket->price;
                                                                        $originalPrice = $eventDateTicket->price;
                                                                    }
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
                                                            $fee += $eventDateTicket->ticket_fee * $value;

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
                                                            <p>{{ $value }} x {{ $eventDateTicket->name }}</p>

                                                            <p class="font-semibold">
                                                                @if($eventDateTicket->currency_symbol_position === 'left')
                                                                    {{ $eventDateTicket->currency->ccy }}
                                                                @endif
                                                                @if($ticketTotal !== $originalPrice * $value)
                                                                    <del>{{ $originalPrice * $value }}</del>
                                                                @endif
                                                                {{ max($ticketTotal, 0) }}
                                                                @if($eventDateTicket->currency_symbol_position === 'right')
                                                                    {{ $eventDateTicket->currency->ccy }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    @endif
                                                @endforeach

                                                @foreach($this->promotions as $quan => $promotion)
                                                    <x-badge warning label="Promo Buy '{{ $quan }} Get ${{ $promotion }} off' applied" />
                                                @endforeach

                                                @if($subtotal > 0)
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
                                                @endif

                                                @if($fee > 0)
                                                    <div class="flex justify-between items-center m-2">
                                                        <p>Fees</p>

                                                        <p class="font-semibold">
                                                            @if($eventDateTicket->currency_symbol_position === 'left')
                                                                {{ $ccy }}
                                                            @endif
                                                            {{ $fee }}
                                                            @if($eventDateTicket->currency_symbol_position === 'right')
                                                                {{ $ccy }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                @endif

                                                @if($subtotal > 0)
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
                                            @endif

                                        </div>
                                    </div>
                                </div>

                                <x-slot name="footer" class="flex justify-end gap-x-4">
                                    <x-button flat label="Cancel" x-on:click="close"/>

                                    <x-button primary label="Checkout" spinner="submit"
                                              wire:click="submit('{{ $eventDate->id }}')"/>
                                </x-slot>
                            </x-modal-card>

                            <hr class="border-gray-300">

                            @if ($eventDate->venue)
                                <div class="text-gray-500 mt-2">
                                            <span
                                                class="{{ app()->getLocale() == 'ar' ? 'float-right' : 'float-left' }}">{{ __('Venue') }}</span>
                                    @if ($eventDate->venue->listedondirectory)
                                        <a href="{{ route('venue', ['slug' => $eventDate->venue->slug]) }}"
                                           class="{{ app()->getLocale() == 'ar' ? 'float-left' : 'float-right' }} flex items-center gap-x-1">
                                            {{ __('More details') }}
                                            <x-fas-chevron-right class="w-3 h-3"/>
                                        </a>
                                    @endif
                                </div>

                                <div class="text-center">
                                    <a href="{{ $eventDate->venue->url }}" target="_blank">
                                        <p class="font-bold">{{ $eventDate->venue->name }}</p>
                                        <p>{{ $eventDate->venue->stringifyAddress }}</p>
                                    </a>

                                    @if ($eventDate->venue->listedondirectory)
                                        <p class="text-center">
                                            <a href="{{ route('venue', ['slug' => $eventDate->venue->slug]) }}"
                                               class="text-center">
                                                {{ __('More details') }}
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            @else
                                <dl>
                                    <dt class="text-gray-500">{{ __('Venue') }}</dt>
                                    <dd class="text-center">
                                        <p>{{ __('Online event') }}</p>
                                    </dd>
                                </dl>
                            @endif
                        @endif
                    @endif
                </div>

            @else
                <div class="flex gap-x-2 items-center bg-[#31708f] p-2 rounded text-white px-4">
                    <x-fas-info-circle class="w-4 h-4"/>

                    <p>No tickets on sale at this moment</p>
                </div>
            @endif

            <hr class="my-8 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10"/>

            <div class="flex flex-col gap-y-2">
                <div class="flex justify-between items-center text-sm">
                    <p class="font-semibold">Organizer</p>

                    <a href="{{ route('organizer-profile', ['slug' => $event->organizer->slug]) }}"
                       class="text-blue-400 flex gap-x-1 items-center">
                        <x-fas-id-card class="w-3 h-3"/>

                        Details
                    </a>
                </div>

                <div class="bg-gray-200 flex flex-col gap-y-2 justify-between items-center p-8">
                    <p class="text-blue-300 font-semibold">{{ $event->organizer->name }}</p>

                    @if(auth()->check())
                        <button type="button"
                                @if(!$event->organizer->followings()->where('User_id', auth()->id())->exists())
                                    wire:click="followOrganization"
                                @endif
                                class="text-white flex gap-x-1 items-center bg-blue-400 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-1 focus:outline-none">
                            <x-fas-folder-plus class="w-3 h-3"/>

                            @if($event->organizer->followings()->where('User_id', auth()->id())->exists())
                                Following
                            @else
                                Follow
                            @endif
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        $wire.on('openModal', () => {
            $openModal('cardModal');
        });
    </script>
    @endscript
</div>
