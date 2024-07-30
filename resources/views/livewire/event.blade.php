@php
    use App\Models\CategoryTranslation;
    use Illuminate\Support\Facades\Storage;

    $eventCategoryTranslation = CategoryTranslation::whereTranslatableId($eventTranslation->event->category_id)
        ->where('locale', app()->getLocale())
        ->first();

    $event = $eventTranslation->event;
    $country = $event?->country?->code;

    $componentName = 'flag-4x3-' . strtolower($country);
@endphp

<div class="mt-24">
    <div
        class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
        <div class="font-bold text-xl">Events</div>

        <x-breadcrumbs/>
    </div>

    <div class="w-full">
        <img src="{{ Storage::url('events/' . $event->image_name) }}" alt="Canyon Swing"
             loading="lazy"
             class="object-contain z-10 w-full relative opacity-100 p-8 h-[50rem]"/>

        <div class="absolute top-[13rem] opacity-75 bg-gradient-to-b blur-xl bg-cover h-[40rem] bg-no-repeat w-full"
             style="background-image: url({{ Storage::url('events/' . $event->image_name) }})"
        >
        </div>
    </div>

    <div class="flex justify-center rounded-lg shadow-lg bg-white mx-20 my-8">
        <div class="container w-[60%]">
            <div class="mt-8 p-6">
                <h1 class="text-2xl font-bold mb-4">{{ $eventTranslation->name }}</h1>
                <div class="flex items-center mb-4">
                    <span class="text-blue-500">★★★☆☆</span>
                    <span class="ml-2 text-gray-600">(1 review)</span>
                </div>

                <h1 class="mb-4">Description</h1>

                <span class="text-gray-700">
                    {!! $eventTranslation->description !!}
                </span>

                <hr class="my-4 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10"/>

                <div class="flex justify-between items-center">
                    <div class="text-gray-700 font-semibold">
                        Category:
                    </div>

                    <div>{{ $eventCategoryTranslation?->name }}</div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-gray-700 font-semibold mr-2">Country:</span>

                    <div class="flex items-center gap-x-2">
                        <x-dynamic-component :component="$componentName" class="inline-block h-8 w-8 rounded-md"/>

                        {{ $country }}
                    </div>
                </div>

                <hr class="my-4 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10"/>
            </div>

            <div class="mt-8 p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-xl font-bold mb-4">{{ count($reviews) }} review</h2>

                    @if(auth()->user()->hasRole('ROLE_ATTENDEE'))
                        <x-button href="{{ route('add-review', ['slug' => $eventTranslation->slug]) }}"
                                  label="ADD YOUR REVIEW" icon="star" teal/>
                    @endif
                </div>

                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <span class="text-blue-500">★★★☆☆</span>
                        <span class="ml-2 text-gray-600">3 out of 5 stars</span>
                    </div>

                    <div class="flex items-center">
                        <span class="text-gray-600 mr-2">5 stars</span>
                        <div class="w-full bg-gray-200 rounded-lg h-4">
                            <div class="bg-blue-500 h-4 rounded-lg" style="width: 0%;"></div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-2">4 stars</span>
                        <div class="w-full bg-gray-200 rounded-lg h-4">
                            <div class="bg-blue-500 h-4 rounded-lg" style="width: 0%;"></div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-2">3 stars</span>
                        <div class="w-full bg-gray-200 rounded-lg h-4">
                            <div class="bg-blue-500 h-4 rounded-lg" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-2">2 stars</span>
                        <div class="w-full bg-gray-200 rounded-lg h-4">
                            <div class="bg-blue-500 h-4 rounded-lg" style="width: 0%;"></div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-600 mr-2">1 star</span>
                        <div class="w-full bg-gray-200 rounded-lg h-4">
                            <div class="bg-blue-500 h-4 rounded-lg" style="width: 0%;"></div>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="text-gray-600">Reviewed by Dinesh Karki</span>
                </div>
            </div>
        </div>

        <div
            class="inline-block h-screen w-0.5 self-stretch bg-neutral-100 dark:bg-white/10"></div>

        <div class="w-[30%] p-8">
            <div class="flex gap-x-2 items-center bg-[#31708f] p-2 rounded text-white px-4">
                <x-fas-info-circle class="w-4 h-4"/>

                <p>No tickets on sale at this moment</p>
            </div>
        </div>
    </div>
</div>
