@push('styles')
    <style>
        input[type=range]::-webkit-slider-thumb {
            pointer-events: all;
            width: 24px;
            height: 24px;
            -webkit-appearance: none;
            /* @apply w-6 h-6 appearance-none pointer-events-auto; */
        }
    </style>
@endpush

<div class="mt-24">
    <div
        class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
        <div class="font-bold text-xl">Venues</div>

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

                    <div
                        class="gap-x-2 flex items-center p-4"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-90"
                        x-show="countryOpen">
                        <span wire:loading wire:target="country">
                            <x-heroicon-o-arrow-path class="animate-spin h-5 w-5 text-blue-500"/>
                        </span>

                        <form class="w-full">
                            <x-select
                                label=""
                                placeholder="Select country"
                                wire:model.live.debounce.500ms="country"
                                option-label="name"
                                option-value="id"
                                :async-data="route('api.events.country')"
                            />
                        </form>
                    </div>
                </article>

                <article id="venue-type-open" x-data="{ venueTypeOpen: true }">
                    <header class="p-3 bg-gray-200 cursor-pointer rounded" @click="venueTypeOpen = ! venueTypeOpen">
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-700">{{ __('Venu Type') }}</h6>

                            <template x-if="venueTypeOpen">
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </template>

                            <template x-if="!venueTypeOpen">
                                <x-fas-chevron-up class="w-4 h-4"/>
                            </template>
                        </div>
                    </header>

                    <div
                        class="gap-x-2 p-4"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-90"
                        x-show="venueTypeOpen">
                        @if(count($venueTypes))
                            <div>
                                <div wire:loading wire:target="selectedVenueTypes">
                                    <x-heroicon-o-arrow-path class="animate-spin h-5 w-5 text-blue-500"/>
                                </div>

                                <form class="flex flex-col gap-y-2">
                                    @foreach($venueTypes as $venueType)
                                        <div class="flex w-full justify-between">
                                            <x-checkbox
                                                id="label-{{ $venueType->id }}"
                                                label="{{ $venueType->name }}"
                                                wire:model.live.debounce.500ms="selectedVenueTypes.{{ $venueType->id }}"
                                                value="{{ $venueType->id }}"
                                            />

                                            <p>{{ count($venueType->venues) }}</p>
                                        </div>
                                    @endforeach
                                </form>
                            </div>
                        @endif
                    </div>
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

                    <div
                        class="gap-x-2 p-4"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-90"
                        x-show="seatedGuestsOpen">
                        <form x-data="range()" x-init="mintrigger(); maxtrigger()" class="relative max-w-xl w-full">
                            <div>
                                <input type="range"
                                       step="100"
                                       x-bind:min="min"
                                       x-bind:max="max"
                                       x-on:input="mintrigger"
                                       wire:model.live.debounce.500ms="minSeatedGuests"
                                       x-model="minprice"
                                       class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                                />

                                <input type="range"
                                       step="100"
                                       x-bind:min="min"
                                       x-bind:max="max"
                                       x-on:input="maxtrigger"
                                       x-model="maxprice"
                                       wire:model.live.debounce.500ms="maxSeatedGuests"
                                       class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                                />

                                <div class="relative z-10 h-2">

                                    <div
                                        class="absolute z-10 left-0 right-0 bottom-0 top-0 rounded-md bg-gray-200"></div>

                                    <div class="absolute z-20 top-0 bottom-0 rounded-md bg-blue-300"
                                         x-bind:style="'right:'+maxthumb+'%; left:'+minthumb+'%'"></div>

                                    <div
                                        class="absolute z-30 w-6 h-6 top-0 left-0 bg-blue-300 rounded-full -mt-2 -ml-1"
                                        x-bind:style="'left: '+minthumb+'%'"></div>

                                    <div
                                        class="absolute z-30 w-6 h-6 top-0 right-0 bg-blue-300 rounded-full -mt-2 -mr-3"
                                        x-bind:style="'right: '+maxthumb+'%'"></div>

                                </div>

                            </div>

                            <div class="flex justify-between items-center py-5">
                                <div>
                                    <input type="text" maxlength="5" x-on:input="mintrigger" x-model="minprice"
                                           class="px-3 py-2 border border-gray-200 rounded w-24 text-center">
                                </div>
                                <div>
                                    <input type="text" maxlength="5" x-on:input="maxtrigger" x-model="maxprice"
                                           class="px-3 py-2 border border-gray-200 rounded w-24 text-center">
                                </div>
                            </div>

                        </form>
                    </div>
                </article>

                <article id="standing-guests-open" x-data="{ standingGuestsOpen: true }">
                    <header class="p-3 bg-gray-200 cursor-pointer rounded"
                            @click="standingGuestsOpen = ! standingGuestsOpen">
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-700">{{ __('Standing Guests') }}</h6>

                            <template x-if="standingGuestsOpen">
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </template>

                            <template x-if="!standingGuestsOpen">
                                <x-fas-chevron-up class="w-4 h-4"/>
                            </template>
                        </div>
                    </header>

                    <div
                        class="gap-x-2 p-4"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-300"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-90"
                        x-show="standingGuestsOpen">
                        <div x-data="range()" x-init="mintrigger(); maxtrigger()" class="relative max-w-xl w-full">
                            <div>
                                <input type="range"
                                       step="100"
                                       x-bind:min="min"
                                       x-bind:max="max"
                                       x-on:input="mintrigger"
                                       x-model="minprice"
                                       wire:model.live.debounce.500ms="minStandingGuests"
                                       class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                                />

                                <input type="range"
                                       step="100"
                                       x-bind:min="min"
                                       x-bind:max="max"
                                       x-on:input="maxtrigger"
                                       x-model="maxprice"
                                       wire:model.live.debounce.500ms="maxStandingGuests"
                                       class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"
                                />

                                <div class="relative z-10 h-2">

                                    <div
                                        class="absolute z-10 left-0 right-0 bottom-0 top-0 rounded-md bg-gray-200"></div>

                                    <div class="absolute z-20 top-0 bottom-0 rounded-md bg-blue-300"
                                         x-bind:style="'right:'+maxthumb+'%; left:'+minthumb+'%'"></div>

                                    <div
                                        class="absolute z-30 w-6 h-6 top-0 left-0 bg-blue-300 rounded-full -mt-2 -ml-1"
                                        x-bind:style="'left: '+minthumb+'%'"></div>

                                    <div
                                        class="absolute z-30 w-6 h-6 top-0 right-0 bg-blue-300 rounded-full -mt-2 -mr-3"
                                        x-bind:style="'right: '+maxthumb+'%'"></div>

                                </div>

                            </div>

                            <div class="flex justify-between items-center py-5">
                                <div>
                                    <input type="text" maxlength="5" x-on:input="mintrigger" x-model="minprice"
                                           class="px-3 py-2 border border-gray-200 rounded w-24 text-center">
                                </div>
                                <div>
                                    <input type="text" maxlength="5" x-on:input="maxtrigger" x-model="maxprice"
                                           class="px-3 py-2 border border-gray-200 rounded w-24 text-center">
                                </div>
                            </div>

                        </div>
                    </div>
                </article>
            </aside>

            <div class="w-full flex flex-col gap-y-4 lg:w-3/4 order-0 lg:order-1">
                @foreach($venues as $venue)
                    <div class="bg-white rounded-xl shadow-md overflow-hidden">
                        <div class="md:flex">
                            <a
                                href="{{ route('venue', ['slug' => $venue->slug]) }}"
                                class="md:flex-shrink-0 flex justify-center items-center bg-orange-200 md:bg-transparent p-4">
                                <img
                                    src="{{ $venue->getFirstImageOrPlaceholder() }}"
                                    loading="lazy"
                                    class="w-48 h-48"
                                    alt=""
                                />
                            </a>

                            <div class="flex justify-between w-full divide-x">
                                <div class="p-4 md:p-8 flex flex-col justify-between">
                                    <a
                                        href="{{ route('venue', ['slug' => $venue->slug]) }}"
                                        class="block mt-1 text-xl text-indigo-400 leading-tight font-semibold">
                                        {{ $venue->name }}
                                    </a>

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
                                                <x-dynamic-component :component="$amenity->icon"
                                                                     class="text-gray-500 w-5 h-4"/>
                                            @endforeach
                                        </div>
                                    @endif

                                    <a
                                        href="{{ route('venue', ['slug' => $venue->slug]) }}"
                                        class="flex items-center px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md hover:bg-blue-600">
                                        <x-fas-building class="w-5 h-5 mr-2"/>
                                        More Details
                                    </a>
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

@push('scripts')
    <script>
        function range() {
            return {
                minprice: 0,
                maxprice: 10000,
                min: 100,
                max: 10000,
                minthumb: 0,
                maxthumb: 0,

                mintrigger() {
                    this.minprice = Math.min(this.minprice, this.maxprice - 500);
                    this.minthumb = ((this.minprice - this.min) / (this.max - this.min)) * 100;
                },

                maxtrigger() {
                    this.maxprice = Math.max(this.maxprice, this.minprice + 500);
                    this.maxthumb = 100 - (((this.maxprice - this.min) / (this.max - this.min)) * 100);
                },
            }
        }
    </script>
@endpush
