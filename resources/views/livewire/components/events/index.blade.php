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
    <div class="flex flex-col lg:flex-row justify-between mb-4">
        <!-- Sidebar -->
        <div class="w-full lg:w-1/4 mb-4 lg:mb-0 space-y-2 mx-4">
            <form wire:submit="search">
                <x-input
                    label="Keyword"
                    wire:model="query"
                    placeholder="Enter keywords"
                />

                <x-select
                    label="Categories"
                    placeholder="Select an option"
                    wire:model.defer="category"
                    option-label="name"
                    option-value="id"
                    :async-data="route('api.events.categories')"
                />

                <div class="flex flex-col gap-y-2 p-4 my-4 border">
                    <x-checkbox id="label" label="Online events only" md/>

                    <x-checkbox id="label" label="Local events only" md/>

                    <x-select
                        label="Country"
                        placeholder="Select a country"
                        option-label="name"
                        option-value="id"
                        :async-data="route('api.events.country')"
                    />
                </div>

                <div class="flex flex-col gap-y-2 p-4 border">
                    <x-radio id="day-today" wire:model="day" name="day" label="Today" value="today" md/>
                    <x-radio id="day-tomorrow" wire:model="day" name="day" label="Tomorrow" value="tomorrow" md/>
                    <x-radio id="day-this-weekend" wire:model="day" name="day" label="This Weekend"
                             value="this-weekend"
                             md/>
                    <x-radio id="day-this-week" wire:model="day" name="day" label="This Week" value="this-week" md/>
                    <x-radio id="day-next-week" wire:model="day" name="day" label="Next Week" value="next-week" md/>
                    <x-radio id="day-this-month" wire:model="day" name="day" label="This Month" value="this-month"
                             md/>
                    <x-radio id="day-next-month" wire:model="day" name="day" label="Next Month" value="next-month"
                             md/>
                    <x-radio id="day-pick-date" wire:model="day" name="day" label="Pick a date" value="pick-date"
                             md/>
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

                <div class="p-4">
                    <div x-data="range()" x-init="mintrigger(); maxtrigger()" class="relative max-w-xl w-full">
                        <div>
                            <input type="range"
                                   step="100"
                                   x-bind:min="min" x-bind:max="max"
                                   x-on:input="mintrigger"
                                   x-model="minprice"
                                   class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer">

                            <input type="range"
                                   step="100"
                                   x-bind:min="min" x-bind:max="max"
                                   x-on:input="maxtrigger"
                                   x-model="maxprice"
                                   class="absolute pointer-events-none appearance-none z-20 h-2 w-full opacity-0 cursor-pointer">

                            <div class="relative z-10 h-2">

                                <div
                                    class="absolute z-10 left-0 right-0 bottom-0 top-0 rounded-md bg-gray-200"></div>

                                <div class="absolute z-20 top-0 bottom-0 rounded-md bg-blue-300"
                                     x-bind:style="'right:'+maxthumb+'%; left:'+minthumb+'%'"></div>

                                <div class="absolute z-30 w-6 h-6 top-0 left-0 bg-blue-300 rounded-full -mt-2 -ml-1"
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

                <div class="flex justify-center w-full">
                    <x-button label="Search" lg icon="magnifying-glass" interaction="negative" gray type="submit"/>
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
                minprice: 1000,
                maxprice: 7000,
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
