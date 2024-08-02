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
        <div class="font-bold text-xl">{{ $eventTranslation->name }}</div>

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

    <div class="flex justify-center rounded-lg shadow-lg bg-white mx-28 lg:mx-44 my-8">
        <div class="container w-[65%]">
            <div class="mt-8 p-6">
                <h1 class="text-2xl font-bold mb-4">{{ $eventTranslation->name }}</h1>
                <div class="flex items-center mb-4">
                    <div class="flex gap-x-1">
                        @for($i = 0; $i < $averageRating; $i++)
                            <x-heroicon-s-star class="w-4 h-4 text-blue-600"/>
                        @endfor

                        @for($i = $averageRating; $i < 5; $i++)
                            <x-heroicon-o-star class="w-4 h-4"/>
                        @endfor
                    </div>

                    <span class="ml-2 text-gray-600">({{ count($reviews) }} review)</span>
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

                    @if(auth()->user()?->hasRole('ROLE_ATTENDEE'))
                        @if($event->reviews()->where('user_id', auth()->id())->exists())
                            <x-button
                                href="{{ route('filament.admin.resources.reviews.index') }}"
                                label="MY REVIEWS" icon="star"
                                teal
                            />
                        @else
                            <x-button
                                href="{{ route('add-review', ['slug' => $eventTranslation->slug]) }}"
                                label="ADD YOUR REVIEW" icon="star"
                                teal
                            />
                        @endif
                    @endif
                </div>

                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <div class="flex gap-x-1">
                            @for($i = 0; $i < $averageRating; $i++)
                                <x-heroicon-s-star class="w-4 h-4 text-blue-600"/>
                            @endfor

                            @for($i = $averageRating; $i < 5; $i++)
                                <x-heroicon-o-star class="w-4 h-4"/>
                            @endfor
                        </div>

                        <span class="ml-2 text-gray-600">{{ (int) $averageRating }} out of 5 stars</span>
                    </div>

                    <div class="w-full flex flex-col gap-y-2 my-8">
                        @foreach ([5, 4, 3, 2, 1] as $rating)
                            <div class="flex items-center gap-x-3 mb-2">
                                <span class="text-gray-600 mr-2 w-auto whitespace-nowrap">{{ $rating }} stars</span>
                                <div class="flex-1 bg-gray-200 h-4">
                                    <div class="bg-blue-500 h-4 rounded"
                                         style="width: {{ $ratingPercentages[$rating] }}%;"></div>
                                </div>
                                <span class="text-gray-600 ml-2 w-8 whitespace-nowrap">{{ $ratingPercentages[$rating] }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-y-4">
                    @foreach($reviews as $review)
                        <div class="flex flex-col gap-y-4">
                            <div class="flex items-center gap-x-2">
                                <x-avatar sm :src="$review->user->getFilamentAvatarUrl()"/>

                                <p class="text-lg">{{ $review->user->getFullNameAttribute() }}</p>
                            </div>

                            <div>
                                <div class="flex gap-x-2 items-center">
                                    <div class="flex gap-x-1">
                                        @for($i = 0; $i < $review->rating; $i++)
                                            <x-heroicon-s-star class="w-4 h-4 text-blue-600"/>
                                        @endfor

                                        @for($i = $review->rating; $i < 5; $i++)
                                            <x-heroicon-o-star class="w-4 h-4"/>
                                        @endfor
                                    </div>

                                    <p class="text-base text-muted">{{ $review->rating }} out of 5 stars</p>
                                </div>

                                <p class="text-sm text-muted">{{ $review->created_at }}</p>
                            </div>

                            <div class="flex flex-col gap-y-1">
                                <h1 class="font-semibold text-base">{{ $review->headline }}</h1>

                                <p class="text-sm">{{ $review->details }}</p>
                            </div>
                        </div>
                    @endforeach
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

            <hr class="my-8 h-0.5 border-t-0 bg-neutral-100 dark:bg-white/10"/>

            <div class="flex flex-col gap-y-2">
                <div class="flex justify-between items-center text-sm">
                    <p class="font-semibold">Organizer</p>

                    <a href="{{ route('organizer-profile', ['slug' => $event->organizer->slug]) }}"
                       class="text-blue-400 flex gap-x-1 items-center">
                        <x-fas-id-card class="w-3 h-3"/>

                        Details
                    </a>
                </div>

                <div class="bg-gray-200 flex flex-col gap-y-2 justify-between items-center p-8">
                    <p class="text-blue-300 font-semibold">{{ $event->organizer->name }}</p>

                    <button type="button"
                            @if(!$event->organizer->followings()->where('User_id', auth()->id())->exists())
                                wire:click="followOrganization"
                            @endif
                            class="text-white flex gap-x-1 items-center bg-blue-400 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-1 focus:outline-none">
                        <x-fas-folder-plus class="w-3 h-3"/>

                        @if($event->organizer->followings()->where('User_id', auth()->id())->exists())
                            Following
                        @else
                            Follow
                        @endif
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
