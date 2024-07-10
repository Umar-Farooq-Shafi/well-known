<div class="mt-24">
    <div class="flex justify-between mx-40 my-4">
        <div></div>

        <x-breadcrumbs/>
    </div>

    <div class="container mx-auto p-6">
        <div class="flex flex-col lg:flex-row justify-between mb-4">
            <!-- Sidebar -->
            <div class="w-full lg:w-1/4 mb-4 lg:mb-0 space-y-2 mx-4">
                <x-input
                    label="Keyword"
                    placeholder="Enter keywords"
                />

                <x-select
                    label="Categories"
                    placeholder="Select an option"
                    option-label="name"
                    option-value="id"
                    :async-data="route('api.events.categories')"
                />

                <div class="flex flex-col gap-y-2 p-4 border">
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
                    <x-radio id="day-this-weekend" wire:model="day" name="day" label="This Weekend" value="this-weekend"
                             md/>
                    <x-radio id="day-this-week" wire:model="day" name="day" label="This Week" value="this-week" md/>
                    <x-radio id="day-next-week" wire:model="day" name="day" label="Next Week" value="next-week" md/>
                    <x-radio id="day-this-month" wire:model="day" name="day" label="This Month" value="this-month" md/>
                    <x-radio id="day-next-month" wire:model="day" name="day" label="Next Month" value="next-month" md/>
                    <x-radio id="day-pick-date" wire:model="day" name="day" label="Pick a date" value="pick-date" md/>
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

            </div>
            <!-- Event Cards -->
            <div class="w-full lg:w-3/4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border p-4 rounded-lg flex items-center space-x-4">
                    <img src="your-image-url" alt="Bungy and Canyoning Day Trip" class="w-24 h-24 rounded-lg">
                    <div>
                        <h3 class="text-lg font-semibold">Bungy and Canyoning Day Trip</h3>
                        <span class="bg-gray-200 text-sm px-2 py-1 rounded">Tours and Adventure</span>
                    </div>
                </div>
                <div class="border p-4 rounded-lg flex items-center space-x-4">
                    <img src="your-image-url" alt="Bungy and Canyon Swing Day Trip" class="w-24 h-24 rounded-lg">
                    <div>
                        <h3 class="text-lg font-semibold">Bungy and Canyon Swing Day Trip</h3>
                        <span class="bg-gray-200 text-sm px-2 py-1 rounded">Tours and Adventure</span>
                    </div>
                </div>
                <div class="border p-4 rounded-lg flex items-center space-x-4">
                    <img src="your-image-url" alt="Go and See Day Trip" class="w-24 h-24 rounded-lg">
                    <div>
                        <h3 class="text-lg font-semibold">Go and See Day Trip</h3>
                        <span class="bg-gray-200 text-sm px-2 py-1 rounded">Tours and Adventure</span>
                    </div>
                </div>
                <div class="border p-4 rounded-lg flex items-center space-x-4">
                    <img src="your-image-url" alt="Bungy Only" class="w-24 h-24 rounded-lg">
                    <div>
                        <h3 class="text-lg font-semibold">Bungy Only</h3>
                        <span class="bg-gray-200 text-sm px-2 py-1 rounded">Tours and Adventure</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
