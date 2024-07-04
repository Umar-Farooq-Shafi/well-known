@php use Illuminate\Support\Facades\Storage; @endphp

<div>
    @push('styles')
        <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
    @endpush
    <div class="swiper mySwiper mt-[70px]">
        <div class="swiper-wrapper">
            @foreach($sliderContents as $sliderContent)
                <div class="swiper-slide">
                    <img class="object-fill w-full h-[85vh]"
                         src="{{ Storage::url('events/' . $sliderContent->image_name) }}"
                         alt="image"/>
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
