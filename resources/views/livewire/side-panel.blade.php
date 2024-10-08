@php use Illuminate\Support\Facades\Storage; @endphp

<div>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    @endpush

    <div class="swiper mySwiper mt-[15%] md:mt-[11%] lg:mt-[7%]">
        <div class="swiper-wrapper">
            @foreach($sliderContents as $sliderContent)
                @php
                    $trans = $sliderContent->eventTranslations()
                        ->where('locale', app()->getLocale())
                        ->first();

                    $country = $sliderContent->country;

                    if ($sliderContent->eventtimezone) {
                        $timezone[] = $sliderContent->eventtimezone;
                    } else if ($country) {
                        $timezone = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country->code);
                    } else {
                        $timezone[] = 'UTC';
                    }
                @endphp

                <div class="swiper-slide relative">
                    <img
                        class="object-fill w-full h-[85vh]" loading="lazy"
                        src="{{ Storage::url('events/' . $sliderContent->image_name) }}"
                        alt="image"
                    />

                    <div class="absolute inset-0 flex flex-col items-start mx-40 justify-center text-white">
                        <h1 class="text-3xl font-bold mb-4 text-center drop-shadow-md text-shadow:_0_2px_4px_rgb(99_102_241_/_0.8)] leading-snug">{{ $trans->name }}</h1>

                        @if($saleEvent = $sliderContent->getFirstOnSaleEventDate())
                            <div class="text-lg mb-4 flex items-center text-center gap-x-2">
                                <x-fas-map-marker-alt class="w-4 h-5"/>

                                @if($saleEvent->eventDates?->first()?->online)
                                    {{ __('This is an online event') }}
                                @elseif($venue = $saleEvent?->venue)
                                    <div class="flex flex-col items-start gap-y-0.5">
                                        <p class="truncate">{{ $venue->name }}</p>
                                        <p class="truncate">{{ $venue->city }}
                                            , {{ $venue->country->name }}
                                        </p>
                                    </div>
                                @endif

                            </div>

                            <p class="text-lg mb-8 flex items-center gap-x-2">
                                <x-fas-clock class="w-4 h-4" style="margin-top: -25px;"/>
                                <span class="text-left">
                                        @if($saleEvent)
                                        @php
                                            // Determine the start date based on recurrence
                                            $startDate = $saleEvent->recurrent ? $saleEvent->recurrent_startdate : $saleEvent->startdate;
                                        @endphp
                                        @if($startDate)
                                            <span>
                                                    {{ $startDate->timezone($timezone[0])->format('jS M Y') }},
                                                    {{ $startDate->timezone($timezone[0])->format('l') }},
                                                    Start {{ $startDate->timezone($timezone[0])->format('g:i a') }}
                                                </span>
                                            <br>
                                            <span>Timezone ({{ $timezone[0] }})</span>
                                        @endif
                                    @endif
                                    </span>
                            </p>

                            <a
                                href="{{ route('event', ['slug' => $trans->slug]) }}"
                                class="bg-white text-gray-800 py-2 px-4 rounded-full flex items-center gap-x-2">
                                <x-fas-ticket-alt class="w-4 h-4"/>

                                BUY TICKETS
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>
    </div>

    @push('scripts')
        <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
        <script>
            var swiper = new Swiper(".mySwiper", {
                cssMode: true,
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                pagination: {
                    el: ".swiper-pagination",
                },
                mousewheel: true,
                keyboard: true,
            });
        </script>
    @endpush
</div>
