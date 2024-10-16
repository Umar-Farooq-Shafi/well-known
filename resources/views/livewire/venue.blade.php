<div class="mt-[25%] lg:mt-[12%] md:mt-[17%] container mx-auto">
    <div class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded my-4">
        <div class="font-bold text-xl">{{ $venueTranslation->name }}</div>

        <x-breadcrumbs/>
    </div>

    <div class="container mx-auto p-6">
        <img
            src="{{ $venue->getFirstImageOrPlaceholder() }}"
            alt="The Concept Club and Pub Logo"
            class="w-full h-[40rem] object-fit"
        />

        <!-- Map and Form Section -->
        <div class="bg-white mx-20 p-6 mt-8 rounded-lg shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Map Section -->
                <div>
                    <div class="mb-4 flex justify-between">
                        <h2 class="text-2xl font-bold">{{ $venueTranslation->name }}</h2>

                        <h1>{{ $venue->venueType->name }}</h1>
                    </div>
                    <p class="mb-2">Guests</p>
                    <p class="font-semibold ml-2">Seated: {{ $venue->seatedguests }} -
                        Standing: {{ $venue->standingguests }}</p>
                    @if($venue->showmap)
                        <div class="mt-4">
                            <iframe height="500" class="w-full border-0 venue-map"
                                    src="https://maps.google.com/maps?q={{ urlencode($venueTranslation->name) . '%20' . urlencode($venue->stringifyAddress) }}&t=&z=13&ie=UTF8&iwloc=&output=embed"></iframe>
                        </div>
                    @endif

                    <p class="my-2">Address</p>
                    <p class="font-semibold ml-2">{{ $venue->stringifyAddress }}</p>

                    @if($venue->neighborhoods)
                        <p class="my-2">Neighborhoods</p>
                        <p class="font-semibold ml-2">{{ $venue->neighborhoods }}</p>
                    @endif

                    @if($venue->pricing)
                        <p class="my-2">Pricing</p>
                        <p class="font-semibold ml-2">{{ $venue->pricing }}</p>
                    @endif

                    @if($venueTranslation->description)
                        <p class="my-2">The space</p>
                        <div class="font-semibold ml-2">
                            {!! $venueTranslation->description !!}
                        </div>
                    @endif
                </div>

                <!-- Form Section -->
                <div>
                    <div class="flex gap-x-2 items-center mb-4">
                        <x-fas-calendar-check class="w-5 h-5"/>

                        <h2 class="text-2xl font-bold">
                            Request a quote
                        </h2>
                    </div>

                    <form wire:submit="submit" class="space-y-4">
                        <x-input
                            label="Your email"
                            type="email"
                            wire:model="email"
                            placeholder="Input your email"
                            required
                        />

                        <x-input
                            label="Phone number"
                            type="tel"
                            wire:model="phone"
                            placeholder="Input phone number"
                            required
                        />

                        <x-input
                            label="Number of guests"
                            type="number"
                            wire:model="guests"
                            placeholder="Input number of guests"
                            required
                        />

                        <x-textarea
                            label="Additional note"
                            wire:model="note"
                            placeholder="write additional note"
                        />

                        <div>
                            <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded-md">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

