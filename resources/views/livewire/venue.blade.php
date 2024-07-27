<div class="mt-24">
    <div class="flex justify-between bg-gray-300 px-4 py-2 rounded mx-32 my-4">
        <div class="font-bold text-xl">Tours And Adventure</div>

        <x-breadcrumbs/>
    </div>

    <div class="container mx-auto p-6">
        <div class="bg-gray-100 flex items-center justify-between p-4">
            <p><span class="font-semibold">{{ $total_venue }}</span> result(s) found</p>

            <div class="flex flex-row gap-x-2 items-center">
                <div wire:loading wire:target="created_at">
                    <x-heroicon-o-arrow-path class="animate-spin h-5 w-5 text-blue-500"/>
                </div>

                <form class="flex gap-x-2 items-center">
                    <p class="w-full font-semibold">Sort by</p>

                    <x-select
                        label=""
                        wire:model.live.debounce.500ms="created_at"
                        wire:loading.attr="disabled"
                        :clearable="false">
                        <x-select.option label="Creation date (asc)" value="asc"/>
                        <x-select.option label="Creation date (desc)" value="desc"/>
                    </x-select>
                </form>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row justify-between mb-4 mt-8">
            <aside class="w-full lg:w-1/5 mb-4 lg:mb-0 space-y-2 mx-4">
                <article class="card-group-item" id="search-open" x-data="{ searchOpen: true }">
                    <header class="p-3 cursor-pointer bg-gray-200 rounded" @click="searchOpen = ! searchOpen">
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-700">{{ __('Keyword') }}</h6>

                            <template x-if="searchOpen">
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </template>

                            <template x-if="!searchOpen">
                                <x-fas-chevron-up class="w-4 h-4"/>
                            </template>
                        </div>
                    </header>

                    <template x-teleport="#search-open">
                        <div
                            class="flex flex-row gap-x-2 items-center p-4"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            x-show="searchOpen">
                            <div wire:loading wire:target="name">
                                <x-heroicon-o-arrow-path class="animate-spin h-5 w-5 text-blue-500"/>
                            </div>

                            <form>
                                <x-input
                                    right-icon="magnifying-glass"
                                    placeholder="Venue Name"
                                    wire:model.live.debounce.500ms="name"
                                    class="w-full"
                                    wire:loading.attr="disabled"
                                />
                            </form>
                        </div>
                    </template>
                </article>

                <article id="country-open" x-data="{ countryOpen: true }">
                    <header class="p-3 bg-gray-200 cursor-pointer rounded" @click="countryOpen = ! countryOpen">
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-700">{{ __('Country') }}</h6>

                            <template x-if="countryOpen">
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </template>

                            <template x-if="!countryOpen">
                                <x-fas-chevron-up class="w-4 h-4"/>
                            </template>
                        </div>
                    </header>

                    <template x-teleport="#country-open">
                        <div
                            class="gap-x-2 p-4"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            x-show="countryOpen">
                            <div wire:loading wire:target="country">
                                <x-heroicon-o-arrow-path class="animate-spin h-5 w-5 text-blue-500"/>
                            </div>

                            <x-select
                                label=""
                                placeholder="Select country"
                                class="w-full"
                                wire:model.live.debounce.500ms="country"
                                option-label="name"
                                option-value="id"
                                :async-data="route('api.events.country')"
                            />
                        </div>
                    </template>
                </article>

                <article id="venue-type-open" x-data="{ venueTypeOpen: true }">
                    <header class="p-3 bg-gray-200 cursor-pointer rounded" @click="venueTypeOpen = ! venueTypeOpen">
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-700">{{ __('Popular') }}</h6>

                            <template x-if="venueTypeOpen">
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </template>

                            <template x-if="!venueTypeOpen">
                                <x-fas-chevron-up class="w-4 h-4"/>
                            </template>
                        </div>
                    </header>

                    <template x-teleport="#venue-type-open">
                        <div
                            class="gap-x-2 p-4"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            x-show="venueTypeOpen">

                        </div>
                    </template>
                </article>

                <article id="seated-guests-open" x-data="{ seatedGuestsOpen: true }">
                    <header class="p-3 bg-gray-200 cursor-pointer rounded"
                            @click="seatedGuestsOpen = ! seatedGuestsOpen">
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-700">{{ __('Seated Guests') }}</h6>

                            <template x-if="seatedGuestsOpen">
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </template>

                            <template x-if="!seatedGuestsOpen">
                                <x-fas-chevron-up class="w-4 h-4"/>
                            </template>
                        </div>
                    </header>

                    <template x-teleport="#seated-guests-open">
                        <div
                            class="gap-x-2 p-4"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            x-show="seatedGuestsOpen">

                        </div>
                    </template>
                </article>
            </aside>

            <div class="w-full flex flex-col gap-y-4 lg:w-3/4 order-0 lg:order-1">
                @foreach($venues as $venue)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="md:flex">
                            <div
                                class="md:flex-shrink-0 flex justify-center items-center bg-orange-200 md:bg-transparent p-4">
                                <div class="text-orange-500 text-5xl">
                                    <img
                                        src="{{ $venue->getFirstImageOrPlaceholder() }}"
                                        loading="lazy"
                                        class="w-48 h-48"
                                        alt=""
                                    />
                                </div>
                            </div>

                            <div class="flex justify-between w-full divide-x">
                                <div class="p-4 md:p-8 flex flex-col justify-between">
                                    <h2 class="block mt-1 text-xl text-indigo-400 leading-tight font-semibold">
                                        {{ $venue->name }}
                                    </h2>

                                    <div class="tracking-wide text-sm">
                                        {{ $venue->venueType?->name }}
                                    </div>
                                    <p class="mt-2 text-gray-500 text-truncate">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($venue->description), 150) }}
                                    </p>
                                    <div class="mt-4">
                                        @if($venue->stringifyAddress)
                                            <p class="text-sm text-gray-600 flex gap-x-8">
                                                <strong>Location:</strong> {{ $venue->stringifyAddress }}</p>
                                        @endif
                                        @if($venue->seatedguests || $venue->standingguests)
                                            <p class="text-sm text-gray-600 flex gap-x-11">
                                                @if($venue->seatedguests)
                                                    <strong>Guests:</strong> Seated: {{ $venue->seatedguests }} -
                                                @endif
                                                @if($venue->standingguests)
                                                    Standing: {{ $venue->standingguests }}
                                                @endif
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                <div
                                    class="mt-4 flex flex-col space-x-4 min-w-52 space-y-4 items-center justify-center">
                                    @if(count($venue->amenities))
                                        <div class="flex space-x-4">
                                            @foreach($venue->amenities as $amenity)
                                                <x-dynamic-component :component="$amenity->icon" class="text-gray-500 w-5 h-4"/>
                                            @endforeach
                                        </div>
                                    @endif

                                    <button
                                        class="flex items-center px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md hover:bg-blue-600">
                                        <x-fas-building class="w-5 h-5 mr-2"/>
                                        More Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="flex flex-row space-x-3 mt-2">
                    {{ $venues->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
