@php use Illuminate\Support\Facades\Storage; @endphp

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

<div class="container mx-auto p-6">
    <div class="bg-gray-100 flex items-center justify-between p-4 mb-4">
        <p><span class="font-semibold">{{ $events_count }}</span> event(s) found</p>

        <a href="/rss" target="_blank" class="p-2 rounded-full bg-[#90ccf4]">
            <x-fas-rss class="h-4 w-4 text-blue-500"/>
        </a>
    </div>

    <div class="flex flex-col lg:flex-row justify-between mb-4">
        <!-- Sidebar -->
        <div class="w-full lg:w-1/4 mb-4 lg:mb-0 space-y-2 mx-4">
            <form class="flex flex-col gap-y-4">
                <div class="flex flex-row gap-x-2">
                    <div wire:loading wire:target="query">
                        <x-heroicon-o-arrow-path class="animate-spin h-5 mt-8 w-5 text-blue-500"/>
                    </div>

                    <x-input
                        label="Keyword"
                        wire:model.live.debounce.500ms="query"
                        wire:loading.attr="disabled"
                        placeholder="Enter keywords"
                    />
                </div>

                <div class="flex flex-row gap-x-2">
                    <div wire:loading wire:target="category">
                        <x-heroicon-o-arrow-path class="animate-spin h-5 mt-8 w-5 text-blue-500"/>
                    </div>

                    <x-select
                        label="Categories"
                        placeholder="Select an option"
                        wire:model.live.debounce.500ms="category"
                        option-label="name"
                        option-value="id"
                        wire:loading.attr="disabled"
                        :async-data="route('api.events.categories')"
                    />
                </div>

                <div class="flex flex-col gap-y-2 p-4 border">
                    <x-checkbox
                        id="is_online"
                        wire:model.live.debounce.500ms="is_online"
                        wire:loading.attr="disabled"
                        label="Online events only"
                        md
                    />

                    <x-checkbox
                        id="is_local"
                        label="Local events only"
                        wire:model.live.debounce.500ms="is_local"
                        wire:loading.attr="disabled"
                        md
                    />

                    <div class="flex flex-row gap-x-2">
                        <div wire:loading wire:target="country">
                            <x-heroicon-o-arrow-path class="animate-spin h-5 mt-8 w-5 text-blue-500"/>
                        </div>

                        <x-select
                            label="Country"
                            placeholder="Select a country"
                            wire:model.live.debounce.500ms="country"
                            wire:loading.attr="disabled"
                            option-label="name"
                            option-value="id"
                            :async-data="route('api.events.country')"
                        />
                    </div>
                </div>

                <div class="flex flex-col gap-y-2 p-4 border">
                    <x-radio
                        id="day-today"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="Today" value="today" md
                    />

                    <x-radio
                        id="day-tomorrow"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="Tomorrow" value="tomorrow" md
                    />

                    <x-radio
                        id="day-this-weekend"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="This Weekend" value="this-weekend" md
                    />

                    <x-radio
                        id="day-this-week"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="This Week" value="this-week" md
                    />

                    <x-radio
                        id="day-next-week"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="Next Week" value="next-week" md
                    />

                    <x-radio
                        id="day-this-month"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="This Month" value="this-month" md
                    />

                    <x-radio
                        id="day-next-month"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="Next Month" value="next-month" md
                    />

                    <x-radio
                        id="day-pick-date"
                        wire:model.live.debounce.500ms="day"
                        wire:loading.attr="disabled"
                        name="day" label="Pick a date" value="pick-date" md
                    />
                </div>

                @if($day === 'pick-date')
                    <div class="mt-2">
                        <x-datetime-picker
                            wire:model.live="customDate"
                            label=""
                            placeholder="Select date"
                            without-time
                        />
                    </div>
                @endif

                <div class="flex flex-col gap-y-2 p-4 border">
                    <x-checkbox
                        id="is_free"
                        label="Free events only"
                        wire:model.live.debounce.500ms="is_free"
                        wire:loading.attr="disabled"
                        md
                    />

                    <div class="p-4">
                        <div x-data="range()" x-init="mintrigger(); maxtrigger()" class="relative max-w-xl w-full">
                            <div>
                                <input type="range"
                                       step="100"
                                       x-bind:min="min" x-bind:max="max"
                                       x-on:input="mintrigger"
                                       wire:model.live.debounce.500ms="minPrice"
                                       wire:loading.attr="disabled"
                                       class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"/>

                                <input type="range"
                                       step="100"
                                       x-bind:min="min" x-bind:max="max"
                                       x-on:input="maxtrigger"
                                       wire:model.live.debounce.500ms="maxPrice"
                                       wire:loading.attr="disabled"
                                       class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer"/>

                                <div class="relative h-2">

                                    <div
                                        class="absolute left-0 right-0 bottom-0 top-0 rounded-md bg-gray-200"></div>

                                    <div class="absolute top-0 bottom-0 rounded-md bg-blue-300"
                                         x-bind:style="'right:'+maxthumb+'%; left:'+minthumb+'%'"></div>

                                    <div class="absolute w-6 h-6 top-0 left-0 bg-blue-300 rounded-full -mt-2 -ml-1"
                                         x-bind:style="'left: '+minthumb+'%'"></div>

                                    <div
                                        class="absolute w-6 h-6 top-0 right-0 bg-blue-300 rounded-full -mt-2 -mr-3"
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
                </div>
            </form>
        </div>

        <!-- Event Cards -->
        <div class="w-full lg:w-3/4 flex flex-col gap-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($events as $event)
                    <a href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                        <div class="border p-4 rounded-lg flex items-center space-x-4">
                            <img src="{{ Storage::url('events/' . $event->image_name) }}" loading="lazy"
                                 alt="Bungy and Canyoning Day Trip" class="w-24 h-24 rounded-lg">

                            <div>
                                <h3 class="text-lg font-semibold">{{ $event->eventTranslations->first()?->name }}</h3>
                                <span
                                    class="bg-gray-200 text-sm px-2 py-1 rounded">{{ $event->category->categoryTranslations->first()?->name }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            {{ $events->links() }}
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
