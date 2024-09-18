@php
    use App\Models\CategoryTranslation;use Illuminate\Support\Carbon;use Illuminate\Support\Facades\Storage;

    $eventCategoryTranslation = CategoryTranslation::whereTranslatableId($eventTranslation->event->category_id)
        ->where('locale', app()->getLocale())
        ->first();

    $event = $eventTranslation->event;
    $country = $event?->country?->code;

    $componentName = 'flag-4x3-' . strtolower($country);

    $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country);
@endphp

<div class="mt-36">
    <div class="w-full">
        <img src="{{ Storage::url('events/' . $event->image_name) }}" alt="Canyon Swing"
             loading="lazy"
             class="object-contain z-10 w-full relative opacity-100 p-8 h-[30rem]"/>

        <div class="absolute top-[13rem] opacity-75 bg-gradient-to-b blur-xl bg-cover h-[25rem] bg-no-repeat w-full"
             style="background-image: url({{ Storage::url('events/' . $event->image_name) }})"
        >
        </div>
    </div>

    <div class="flex justify-center rounded-lg shadow-lg bg-white mx-8 md:mx-16 lg:mx-44 my-8">
        <div class="container w-[65%]">
            <div class="mt-8 p-6">
                <div class="flex gap-x-4">
                    @foreach ($event->eventDates as $eventDate)
                        @if ($eventDate->isOnSale())
                            <div id="eventDate-{{ $eventDate->reference }}-wrapper" class="event-eventDate-wrapper">
                                <dl class="mb-4">
                                    <dd>
                                        <div class="text-center">
                                            {{-- For the add to calendar link --}}
                                            @php
                                                $eventstartdate = '';
                                                $eventenddate = '';
                                                $eventlocation = $eventDate->venue ? $eventDate->venue->name . ': ' . $eventDate->venue->stringifyAddress : __('Online');
                                            @endphp
                                            @if ($eventDate->startdate)
                                                <div class="inline-block">
                                                    <div class="inline-block">
                                                        <span class="text-5xl">
                                                            {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('d') }}
                                                        </span>
                                                    </div>
                                                    <div class="inline-block mr-3">
                                                        <div>
                                                            <span
                                                                class="text-sm">{{ ucfirst($eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('M')) }}</span>
                                                        </div>
                                                        <div>
                                                            <span class="text-sm">
                                                                {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('Y') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-2">
                                                    <span class="text-gray-500 font-bold">
                                                        {{ strtoupper($eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a')) }}
                                                        @if ($eventDate->enddate && Carbon::make($eventDate->enddate)->timezone($event->eventtimezone ?? $timezone[0])->equalTo($eventDate->startdate->timezone($timezone[0])))
                                                            - {{ strtoupper($eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a')) }}
                                                        @endif
                                                    </span>
                                                    </div>
                                                </div>
                                                @php
                                                    $eventstartdate = $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('F d, Y H:i');
                                                @endphp
                                            @endif

                                            @if ($eventDate->enddate && !$eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->equalTo($eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])))
                                                <div class="inline-block">
                                                    <div class="inline-block">
                                                        <span
                                                            class="text-5xl">{{ $eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('d') }}</span>
                                                    </div>
                                                    <div class="inline-block">
                                                        <div><span
                                                                class="text-sm">{{ ucfirst($eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('M')) }}</span>
                                                        </div>
                                                        <div>
                                                            <span
                                                                class="text-sm">{{ $eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('Y') }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="mb-2">
                                                        <span
                                                            class="text-gray-500 font-bold">
                                                            {{ strtoupper($eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('g:i a')) }}
                                                        </span>
                                                    </div>
                                                </div>
                                                @php
                                                    $eventenddate = $eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('F d, Y H:i');
                                                @endphp
                                            @endif

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
                                    </dd>
                                </dl>

                            </div>
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

        <div
            class="inline-block h-screen w-0.5 self-stretch bg-neutral-100 dark:bg-white/10"></div>

        <div class="w-[30%] p-8">
            @if($event->hasAnEventDateOnSale())
                @if ($event->hasTwoOrMoreEventDatesOnSale())
                    @php
                        $eventDatesCalendar = [];
                    @endphp

                    @foreach ($event->eventDates as $eventDate)
                        @if ($eventDate->isOnSale())
                            @php
                                $eventDatesCalendar[] = [
                                    'Date' => $eventDate->timezone($event->eventtimezone ?? $timezone[0])->startdate,
                                    'Title' => $eventDate->timezone($event->eventtimezone ?? $timezone[0])->startdate,
                                    'Link' => $eventDate->timezone($event->eventtimezone ?? $timezone[0])->reference,
                                ];
                            @endphp
                        @endif
                    @endforeach

                    <div class="flex gap-x-2 items-center bg-[#31708f] p-2 rounded text-white px-4">
                        <x-fas-info-circle class="w-4 h-4"/>

                        <p>Click on a date to view tickets</p>
                    </div>

                    <div id="event-dates-calendar" class="mt-5"
                         data-event-dates="{{ json_encode($eventDatesCalendar) }}"></div>

                    @if(auth()->check() && auth()->user()?->hasRole('ROLE_ATTENDEE'))
                        <button id="add-to-cart-button" type="button" class="btn btn-primary w-full mt-3 mb-3"><i
                                class="fas fa-cart-plus"></i> {{ __('Add to cart') }}</button>
                    @endif
                @endif

                <div>

                    @foreach ($event->eventDates as $eventDate)
                        @if ($eventDate->isOnSale())
                            <div id="eventDate-{{ $eventDate->reference }}-wrapper" class="event-eventDate-wrapper">
                                <dl class="mt-4">
                                    <dt class="text-gray-500">{{ __('Tickets') }}</dt>

                                    <dd class="mr-0 my-2">
                                        <div class="overflow-x-auto">
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
                                                                @if (!$ticket->isOnSale())
                                                                    <span
                                                                        class="badge {{ $ticket->stringifyStatusClass() }}">{{ __($ticket->stringifyStatus()) }}</span>
                                                                @else
                                                                    @if($ticket->free)
                                                                        <span>Free</span>
                                                                    @elseif($ticket->promotionalprice)
                                                                        @php
                                                                            $isStartDate = $ticket->salesstartdate?->timezone($event->eventtimezone ?? $timezone[0])?->lessThanOrEqualTo(now());
                                                                            $isEndDate = $ticket->salesenddate?->timezone($event->eventtimezone ?? $timezone[0])?->greaterThanOrEqualTo(now());
                                                                        @endphp

                                                                        @if($isStartDate && $isEndDate)
                                                                            <del class="text-gray-500">
                                                                                {{ $ticket->price }}
                                                                            </del>

                                                                            <span>
                                                                                @if($ticket->currency_symbol_position === 'left')
                                                                                    {{ $ticket->currency->symbol }}
                                                                                @endif
                                                                                {{ $ticket->price - $ticket->promotionalprice }}
                                                                                @if($ticket->currency_symbol_position === 'right')
                                                                                    {{ $ticket->currency->symbol }}
                                                                                @endif
                                                                            </span>
                                                                        @else
                                                                            <span>
                                                                                {{ $ticket->price }}
                                                                            </span>
                                                                        @endif
                                                                    @else
                                                                        <span>{{ $ticket->price }}</span>
                                                                    @endif
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        @if ($eventDate->recurrent)
                                            <div class="form-group my-2">
                                                <x-datetime-picker
                                                    wire:model.live="eventDatePick"
                                                    label="Event Date"
                                                    placeholder="Select Event Date"
                                                    without-timezone
                                                    without-time
                                                    :min="$eventDate->recurrent_startdate->timezone($event->eventtimezone ?? $timezone[0])->format('Y-m-d')"
                                                    :max="$eventDate->recurrent_enddate->timezone($event->eventtimezone ?? $timezone[0])->format('Y-m-d')"
                                                />
                                            </div>
                                        @endif

                                        <div class="flex items-center justify-center w-full py-2">
                                            <x-button
                                                wire:click="buyTicket"
                                                spinner="buyTicket"
                                                yellow
                                                label="Buy Tickets"
                                            />
                                        </div>

                                    </dd>
                                </dl>

                                <x-modal-card :title="$eventTranslation->name" name="cardModal" blur="md" width="5xl">
                                    <div class="grid grid-cols-1 md:gap-2 lg:gap-4 lg:grid-cols-8 md:grid-cols-4">
                                        <div class="md:col-span-2 lg:col-span-5">
                                            <div class="flex flex-col gap-y-1 font-medium text-base">
                                                <p>
                                                    {{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('F d Y') }}
                                                    - {{ $eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('F d Y') }}
                                                    ({{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('H:i A') }}
                                                    - {{ $eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('H:i A') }}
                                                    )
                                                </p>

                                                <p>
                                                    Selected Date:
                                                    @if($eventDatePick)
                                                        {{  Carbon::make($eventDatePick)->timezone($event->eventtimezone ?? $timezone[0])->format('F d Y') }}
                                                        ({{ $eventDate->startdate->timezone($event->eventtimezone ?? $timezone[0])->format('H:i A') }}
                                                        - {{ $eventDate->enddate->timezone($event->eventtimezone ?? $timezone[0])->format('H:i A') }}
                                                        )
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
                                            </div>

                                            <div class="flex flex-col gap-y-2 divide-y-2 my-4 mx-2">
                                                @foreach($event->eventDates as $ed)
                                                    @if ($ed->isOnSale())
                                                        @foreach ($ed->eventDateTickets as $eventTicket)
                                                            @if ($eventTicket->active)
                                                                <x-card class="{{ $ccy !== null && $ccy !== $eventTicket->currency->ccy ? '!bg-gray-50' : '' }}">
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
                                                                                            {{ $eventTicket->price }}
                                                                                        </del>

                                                                                        <span>
                                                                                @if($eventTicket->currency_symbol_position === 'left')
                                                                                                {{ $eventTicket->currency->symbol }}
                                                                                            @endif
                                                                                            {{ $eventTicket->price - $eventTicket->promotionalprice }}
                                                                                            @if($eventTicket->currency_symbol_position === 'right')
                                                                                                {{ $eventTicket->currency->symbol }}
                                                                                            @endif
                                                                            </span>
                                                                                    @else
                                                                                        <span>
                                                                                        {{ $eventTicket->price }}
                                                                                    </span>
                                                                                    @endif
                                                                                @else
                                                                                    <p class="text-gray-500">
                                                                                        @if($eventTicket->currency_symbol_position === 'left')
                                                                                            {{ $eventTicket->currency->ccy }}
                                                                                        @endif
                                                                                        {{ $eventTicket->price }}
                                                                                        @if($eventTicket->currency_symbol_position === 'right')
                                                                                            {{ $eventTicket->currency->symbol }}
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
                                                    @php $subtotal = 0; $fee = 0; @endphp

                                                    @foreach($quantity as $id => $value)
                                                        @if($value > 0)
                                                            @php
                                                                $eventDateTicket = \App\Models\EventDateTicket::find($id);
                                                                $price = $eventDateTicket->price;

                                                                if ($eventDateTicket->promotionalprice) {
                                                                    $isStartDate = $eventDateTicket->salesstartdate?->timezone($event->eventtimezone ?? $timezone[0])?->lessThanOrEqualTo(now());
                                                                    $isEndDate = $eventDateTicket->salesenddate?->timezone($event->eventtimezone ?? $timezone[0])?->greaterThanOrEqualTo(now());

                                                                    if ($isStartDate && $isEndDate) {
                                                                        $price = $eventDateTicket->price - $eventDateTicket->promotionalprice;
                                                                    }
                                                                }

                                                                $subtotal += $price * $value;
                                                                $fee += $eventDateTicket->ticket_fee * $value;
                                                            @endphp

                                                            <div class="flex justify-between items-center m-2">
                                                                <p>{{ $value }} x {{ $eventDateTicket->name }}</p>

                                                                <p class="font-semibold">
                                                                    @if($eventDateTicket->currency_symbol_position === 'left')
                                                                        {{ $eventDateTicket->currency->ccy }}
                                                                    @endif
                                                                    {{ $price * $value }}
                                                                    @if($eventDateTicket->currency_symbol_position === 'right')
                                                                        {{ $eventDateTicket->currency->ccy }}
                                                                    @endif
                                                                </p>
                                                            </div>
                                                        @endif
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
                            </div>
                        @endif
                    @endforeach
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
