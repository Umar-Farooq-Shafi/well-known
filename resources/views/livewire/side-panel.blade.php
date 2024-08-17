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
                @endphp

                <div class="swiper-slide relative">
                    <img
                        class="object-fill w-full h-[85vh]" loading="lazy"
                        src="{{ Storage::url('events/' . $sliderContent->image_name) }}"
                        alt="image"
                    />

                    <div class="absolute inset-0 flex flex-col items-start mx-40 justify-center text-white">
                        <h1 class="text-3xl font-bold mb-4 text-center">{{ $trans->name }}</h1>

                        @if($saleEvent = $sliderContent->getFirstOnSaleEventDate())
                            <p class="text-lg mb-4 flex items-center text-center gap-x-2">
                                <x-fas-map-marker-alt class="w-4 h-4"/>

                                @if($saleEvent?->venue)
                                    {{ $saleEvent?->venue?->name }} {{ $saleEvent?->venue?->stringifyAddress }}
                                @else
                                    <span>Online</span>
                                @endif
                            </p>
                            <p class="text-lg mb-8 flex items-center text-center gap-x-2">
                                <x-fas-clock class="w-4 h-4"/>

                                @if($saleEvent?->startdate)
                                    <span>{{ $saleEvent->startdate }}</span>
                                @endif
                            </p>

                            <a href="{{ route('event', ['slug' => $trans->slug]) }}" class="bg-white text-gray-800 py-2 px-4 rounded-full flex items-center gap-x-2">
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
