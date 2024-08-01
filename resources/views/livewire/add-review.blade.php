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
                        <div class="flex gap-x-1">
                            @for($i = 0; $i < $averageRating; $i++)
                                <x-heroicon-s-star class="w-4 h-4 text-blue-600"/>
                            @endfor

                            @for($i = $averageRating; $i < 5; $i++)
                                <x-heroicon-o-star class="w-4 h-4"/>
                            @endfor
                        </div>

                        <span class="ml-2 text-gray-600">({{ $reviews }} review)</span>
                    </div>
                </div>
            </div>

            <div class="w-1/2 flex flex-col gap-y-2">
                @foreach ([5, 4, 3, 2, 1] as $rating)
                    <div class="flex items-center gap-x-3 mb-2">
                        <span class="text-gray-600 mr-2 w-auto whitespace-nowrap">{{ $rating }} stars</span>
                        <div class="flex-1 bg-gray-200 h-4">
                            <div class="bg-blue-500 h-4 rounded" style="width: {{ $ratingPercentages[$rating] }}%;"></div>
                        </div>
                        <span class="text-gray-600 ml-2 w-8 whitespace-nowrap">{{ $ratingPercentages[$rating] }}%</span>
                    </div>
                @endforeach
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
