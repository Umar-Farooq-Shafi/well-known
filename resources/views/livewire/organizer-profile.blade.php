<div class="mt-24">

    <div class="container mx-auto p-4">
        <!-- Profile Section -->
        <div class="flex flex-col items-center bg-white p-4 rounded-lg shadow-lg mb-4">
            <div class="w-24 h-24 bg-orange-500 rounded-full flex items-center justify-center text-white mb-2">
                @if($organizer->logo_name)
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url("organizers/" . $organizer->logo_name) }}"
                        loading="lazy"
                        alt="{{ $organizer->name }}"
                        class="w-44 h-44"
                    />
                @else
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                         xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2m8-4a4 4 0 100-8 4 4 0 000 8zm6 0a4 4 0 00-.88-2.54M15 12V5a2 2 0 10-4 0v7m-3 3v2a2 2 0 01-2 2H5a2 2 0 01-2-2v-2a6 6 0 0112 0zm5-1v1m0 4v.01M15 21v.01M19 11v.01M19 8v.01"></path>
                    </svg>
                @endif
            </div>
            <h2 class="text-xl font-semibold">{{ $organizer->name }}</h2>
            <div class="flex space-x-4 mt-2">
                <div class="text-gray-500">{{ count($organizer->events) }} Events</div>
                <div class="text-gray-500">{{ count($organizer->followings) }} Followers</div>
            </div>

            @if(auth()->check())
                <button type="button"
                        wire:click="followOrganization"
                        class="text-white mt-4 flex gap-x-1 items-center bg-blue-400 hover:bg-blue-600 focus:ring-4 focus:ring-blue-300 rounded-lg text-sm px-3 py-1 focus:outline-none">
                    <x-fas-folder-plus class="w-3 h-3"/>

                    @if($organizer->followings()->where('User_id', auth()->id())->exists())
                        Unfollow
                    @else
                        Follow
                    @endif
                </button>
            @endif
        </div>

        <!-- Tabs Section -->
        <div class="flex justify-center gap-x-4 mb-4">
            <button
                wire:click="setActiveTab(1)"
                class="px-4 py-2 {{ $activeTab === 1 ? 'bg-blue-500 text-white' : 'text-gray-700 bg-gray-300' }} rounded-t-lg focus:outline-none">
                Events on sale (10)
            </button>

            <button
                wire:click="setActiveTab(2)"
                class="px-4 py-2 {{ $activeTab === 2 ? 'bg-blue-500 text-white' : 'text-gray-700 bg-gray-300' }} rounded-t-lg focus:outline-none">
                Past events (10)
            </button>
        </div>

        <!-- Event Cards Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($events as $event)

                <div class="bg-white relative p-4 rounded-lg shadow-lg">
                    <a class=""
                       href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                        <img
                            class="w-full h-52"
                            src="{{ Storage::url('events/' . $event->image_name) }}"
                            loading="lazy"
                            alt="{{ $event->eventTranslations->first()?->name }}    "
                        />

                        @if(auth()->check())
                            <span
                                wire:click.prevent="eventFavourite({{ $event->id }})"
                                class="absolute right-3 bottom-[25%] z-10 bg-gray-50 shadow rounded-full p-1">
                                @if($event->favourites()->where('User_id', auth()->id())->exists())
                                    <x-heroicon-s-heart class="w-4 h-4"/>
                                @else
                                    <x-heroicon-o-heart class="w-4 h-4"/>
                                @endif
                            </span>
                        @endif
                    </a>

                    <span class="text-sm text-gray-500 mb-2">
                        {{ $event->category->categoryTranslations->first()?->name }}
                    </span>

                    <a href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                        <h3 class="text-lg font-semibold mb-2">{{ $event->eventTranslations->first()?->name }}</h3>
                    </a>
                </div>

            @endforeach
        </div>


    </div>

</div>
