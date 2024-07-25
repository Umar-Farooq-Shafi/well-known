@php use Illuminate\Support\Facades\Storage; @endphp

<div>
    <livewire:side-panel/>

    <main class="mx-16">
        @if(count($categories))
            <section class="flex items-center gap-x-24 py-6 justify-center w-full">
                @foreach($categories as $category)
                    <div class="flex items-center flex-col">
                        <div class="border rounded-full border-gray-300 bg-white p-6">
                            <x-icon name="{{ $category->icon }}" class="w-6 h-6"/>
                        </div>

                        <p>{{ $category->categoryTranslations->first()?->name }}</p>
                    </div>
                @endforeach
            </section>
        @endif

        <section class="flex gap-x-2 px-4 items-center py-6">
            <h1 class="text-2xl font-semibold">Brows events in</h1>

            <x-heroicon-o-chevron-down class="w-5 h-5 font-bold"/>

            <h1 class="text-lg font-semibold text-blue-400 underline">Indian Wells</h1>
        </section>

        <nav>
            <div class="max-w-screen-xl px-4 py-3">
                <div class="flex items-center">
                    <ul class="flex flex-row font-medium mt-0 space-x-8 rtl:space-x-reverse text-sm">
                        <li>
                            <a href="#"
                               class="text-blue-700 underline text-base decoration-blue-500 decoration-2 underline-offset-8 dark:text-white hover:underline"
                               aria-current="page">All</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">Company</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">Online</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">Today</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">This Weekend</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">Canada Day</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">4th of july</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">Pride</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">Free</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">Music</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">Food and
                                Drink</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-900 text-base dark:text-white hover:underline">Charity and
                                courses</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <h1 class="text-2xl font-bold px-4 py-8">Events $30 and under</h1>


        @if(count($events))
            <div
                class="flex overflow-x-scroll pb-10 hide-scroll-bar"
            >
                <div
                    class="flex flex-nowrap lg:ml-40 md:ml-20 ml-10"
                >
                    @foreach($events as $event)
                        <div class="inline-block px-3">
                            <a href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                                <div
                                    class="w-72 h-72 max-w-xs overflow-hidden rounded-lg shadow-md bg-white hover:shadow-xl transition-shadow duration-300 ease-in-out"
                                >
                                    <img class="w-full h-40" src="{{ Storage::url('events/' . $event->image_name) }}" loading="lazy"
                                         alt="{{ $event->eventTranslations->first()->name }}"/>

                                    <p class="p-2 font-medium text-lg">{{ $event->eventTranslations->first()->name }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="m-4">
            <div class="flex justify-center">
                <h1 class="font-semibold text-2xl">Featured Events</h1>
            </div>

            @if(count($featuredEvents))
                <div
                    class="flex overflow-x-auto py-10 hide-scroll-bar"
                >
                    <div
                        class="flex flex-nowrap"
                    >
                        @foreach($featuredEvents as $event)
                            <div class="inline-block px-3">
                                <div
                                    class="relative w-80 h-72 max-w-xs overflow-hidden rounded-lg shadow-md bg-white hover:shadow-xl transition-shadow duration-300 ease-in-out"
                                >
                                    <a href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                                    <span
                                        class="absolute top-2.5 shadow z-10 bg-white text-gray-700 rounded-l py-0.5 px-2 opacity-90 right-0">
                                        {{ $event->category->categoryTranslations->first()?->name ?? '' }}
                                    </span>

                                        <img class="w-full h-40" loading="lazy"
                                             src="{{ Storage::url('events/' . $event->image_name) }}"
                                             alt="{{ $event->eventTranslations->first()->name }}"/>

                                        <p class="p-2 font-medium text-lg">{{ $event->eventTranslations->first()->name }}</p>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </main>
</div>
