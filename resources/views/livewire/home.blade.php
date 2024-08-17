@php use Illuminate\Support\Facades\Storage; @endphp

<div>
    <livewire:side-panel/>

    <main class="mx-16">
        @if(count($categories))
            <section class="flex items-center overflow-auto gap-x-24 py-6 justify-center w-full">
                @foreach($categories as $cat)
                    <div class="flex items-center flex-col">
                        <a
                            href="?category={{ $cat->categoryTranslations->first()?->name  }}"
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
                            <a href="/"
                               class="{{ $category === '' ? 'text-blue-700 underline decoration-blue-500 decoration-2 underline-offset-8' : 'text-gray-900' }} text-blue-700 text-base  dark:text-white hover:underline"
                               aria-current="page">
                                All
                            </a>
                        </li>
                        <li>
                            <a href="?category=online"
                               class="{{ $category === 'online' ? 'text-blue-700 underline decoration-blue-500 decoration-2 underline-offset-8' : 'text-gray-900' }} text-base dark:text-white hover:underline">
                                Online
                            </a>
                        </li>
                        <li>
                            <a href="?category=today"
                               class="{{ $category === 'today' ? 'text-blue-700 underline decoration-blue-500 decoration-2 underline-offset-8' : 'text-gray-900' }} text-base dark:text-white hover:underline">
                                Today
                            </a>
                        </li>
                        <li>
                            <a href="?category=this-weekend"
                               class="{{ $category === 'this-weekend' ? 'text-blue-700 underline decoration-blue-500 decoration-2 underline-offset-8' : 'text-gray-900' }} text-base dark:text-white hover:underline">
                                This Weekend
                            </a>
                        </li>
                        <li>
                            <a href="?category=free"
                               class="{{ $category === 'free' ? 'text-blue-700 underline decoration-blue-500 decoration-2 underline-offset-8' : 'text-gray-900' }} text-base dark:text-white hover:underline">
                                Free
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

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
                                    <a
                                        href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                                        <span
                                            class="absolute top-2.5 shadow z-10 bg-white text-gray-700 rounded-l py-0.5 px-2 opacity-90 right-0">
                                            {{ $event->category->categoryTranslations->first()?->name ?? '' }}
                                        </span>

                                        @if($eventDate = $event->eventDates->first())
                                            @if($eventDate?->isOnSale())
                                                <div
                                                    class="absolute w-[50px] top-2.5 left-1 justify-center items-center shadow z-10 bg-white flex flex-col gap-y-2 text-gray-700">
                                                    <p class="bg-sky-300 w-full text-center">
                                                        {{ \Carbon\Carbon::make($eventDate->startdate)->format('M') }}
                                                    </p>

                                                    <p class="pb-1">
                                                        {{ \Carbon\Carbon::make($eventDate->startdate)->format('d') }}
                                                    </p>
                                                </div>
                                            @endif
                                        @endif

                                        <img class="w-full h-40" loading="lazy"
                                             src="{{ Storage::url('events/' . $event->image_name) }}"
                                             alt="{{ $event->eventTranslations->first()->name }}"
                                        />

                                        <div class="flex flex-col h-2/5 justify-between p-2">
                                            <p class="font-medium text-base">{{ $event->eventTranslations->first()->name }}</p>

                                            <div class="flex justify-between items-center">
                                                <p class="w-[90%]">
                                                    @if($venue = $event->eventDates?->first()?->venue)
                                                        {{ $venue->stringifyAddress }}
                                                    @endif
                                                </p>

                                                <p class="w-[16%]">
                                                    @if($eventDate = $event->eventDates?->first())
                                                        {{ $eventDate->getCurrencyCode() }}

                                                        {{ $eventDate->getTotalTicketFees() }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
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
        </script>
    @endpush
</div>
