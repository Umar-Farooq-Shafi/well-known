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
            <h1 class="text-2xl font-semibold">Browse events in</h1>

            <div x-data="{ isOpen: false, openedWithKeyboard: false }" class="relative"
                 @keydown.esc.window="isOpen = false, openedWithKeyboard = false">
                <!-- Toggle Button -->
                <div @click="isOpen = ! isOpen"
                     class="inline-flex cursor-pointer items-center gap-2 whitespace-nowrap p-2 text-sm font-medium tracking-wide transition hover:opacity-75 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-slate-800 dark:border-slate-700 dark:bg-slate-800 dark:focus-visible:outline-slate-300"
                     aria-haspopup="true" @keydown.space.prevent="openedWithKeyboard = true"
                     @keydown.enter.prevent="openedWithKeyboard = true"
                     @keydown.down.prevent="openedWithKeyboard = true"
                     :class="isOpen || openedWithKeyboard ? 'text-black dark:text-white' : 'text-slate-700 dark:text-slate-300'"
                     :aria-expanded="isOpen || openedWithKeyboard">
                    <svg aria-hidden="true" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                         stroke-width="2" stroke="currentColor" class="size-4 totate-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5"/>
                    </svg>

                    {{ $country }}
                </div>

                <div x-cloak x-show="isOpen || openedWithKeyboard" x-transition x-trap="openedWithKeyboard"
                     @click.outside="isOpen = false, openedWithKeyboard = false"
                     @keydown.down.prevent="$focus.wrap().next()" @keydown.up.prevent="$focus.wrap().previous()"
                     class="absolute top-11 left-0 flex w-full min-w-[12rem] flex-col overflow-hidden rounded-xl border border-slate-300 bg-slate-100 py-1.5 dark:border-slate-700 dark:bg-slate-800"
                     role="menu">
                    @foreach($countries as $country)
                        <div
                            wire:click="updateCountry({{ $country }})"
                            class="bg-slate-100 px-4 cursor-pointer py-2 text-sm text-slate-700 hover:bg-slate-800/5 hover:text-black focus-visible:bg-slate-800/10 focus-visible:text-black focus-visible:outline-none dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-100/5 dark:hover:text-white dark:focus-visible:bg-slate-100/10 dark:focus-visible:text-white"
                            role="menuitem">
                            {{ $country }}
                        </div>
                    @endforeach
                </div>
            </div>
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
                                    <img class="w-full h-40" src="{{ Storage::url('events/' . $event->image_name) }}"
                                         loading="lazy"
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
