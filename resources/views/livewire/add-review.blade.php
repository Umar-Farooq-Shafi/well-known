<div class="mt-24">
    <div
        class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
        <div class="text-xl">Add your review for <span class="font-bold">{{ $eventTranslation->name }}</span></div>

        <x-breadcrumbs/>
    </div>

    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center">
            <div class="flex gap-x-6 items-center w-1/2">
                <img
                    loading="lazy"
                    class="w-52 h-52"
                    alt="{{ $eventTranslation->name }}"
                    src="{{ \Illuminate\Support\Facades\Storage::url('events/' . $event->image_name) }}"
                />

                <div class="flex flex-col gap-y-3">
                    <h1 class="text-4xl font-semibold text-blue-400">{{ $eventTranslation->name }}</h1>

                    <div class="flex items-center mb-4">
                        <span class="text-blue-500 text-xl">★★★☆☆</span>
                        <span class="ml-2 text-xl text-gray-600">5 out of 5 stars</span>
                    </div>
                </div>
            </div>

            <div class="w-1/2 flex flex-col gap-y-2">
                <div class="flex items-center gap-x-3">
                    <span class="text-gray-600 mr-2 w-auto whitespace-nowrap">5 stars</span>
                    <div class="flex-1 bg-gray-200 h-4">
                        <div class="bg-blue-500 h-4 rounded" style="width: 0%;"></div>
                    </div>
                </div>

                <div class="flex items-center gap-x-3">
                    <span class="text-gray-600 mr-2 whitespace-nowrap">4 stars</span>
                    <div class="flex-1 bg-gray-200 h-4">
                        <div class="bg-blue-500 h-4 rounded" style="width: 0%;"></div>
                    </div>
                </div>

                <div class="flex items-center gap-x-3">
                    <span class="text-gray-600 mr-2 whitespace-nowrap">3 stars</span>
                    <div class="flex-1 bg-gray-200 h-4">
                        <div class="bg-blue-500 h-4 rounded" style="width: 100%;"></div>
                    </div>
                </div>

                <div class="flex items-center gap-x-3">
                    <span class="text-gray-600 mr-2 whitespace-nowrap">2 stars</span>
                    <div class="flex-1 bg-gray-200 h-4">
                        <div class="bg-blue-500 h-4 rounded" style="width: 0%;"></div>
                    </div>
                </div>

                <div class="flex items-center gap-x-3">
                    <span class="text-gray-600 mx-2 whitespace-nowrap">1 star </span>
                    <div class="flex-1 bg-gray-200 h-4">
                        <div class="bg-blue-500 h-4 rounded" style="width: 0%;"></div>
                    </div>
                </div>
            </div>
        </div>

        <x-card class="p-4 mt-8 w-full" shadow="md">
            <form wire:submit="submit" class="space-y-4">
                <h1>Your rating (out of 5 stars) <span class="text-red-500">*</span></h1>

                <div class="flex gap-x-5">
                    <x-radio
                        id="5-stars"
                        label="5 stars"
                        wire:model="rating"
                        value="5"
                    />

                    <x-radio
                        id="4-stars"
                        label="4 stars"
                        wire:model="rating"
                        value="4"
                    />

                    <x-radio
                        id="3-stars"
                        label="3 stars"
                        wire:model="rating"
                        value="3"
                    />

                    <x-radio
                        id="2-stars"
                        label="2 stars"
                        wire:model="rating"
                        value="2"
                    />

                    <x-radio
                        id="1-star"
                        label="1 star"
                        wire:model="rating"
                        value="1"
                    />
                </div>

                <x-input
                    label="Your review headline"
                    wire:model="headline"
                    placeholder="Input your review headline"
                    required
                />

                <x-textarea
                    label="Let the other attendee know more details about your experience"
                    placeholder=""
                    rows="8"
                    wire:model="details"
                />

                <x-button label="Save" type="submit" interaction:solid="positive" />
            </form>
        </x-card>
    </div>
</div>
