@php use Illuminate\Support\Facades\Storage; @endphp

<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        @foreach($events as $event)

            <div class="bg-white border border-gray-200 rounded shadow dark:bg-gray-800 dark:border-gray-700">
                <a class="relative"
                   href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                    <img
                        class="w-full h-50 rounded"
                        src="{{ Storage::url('events/' . $event->image_name) }}"
                        loading="lazy"
                        alt="{{ $event->eventTranslations->first()?->name }}    "
                    />

                    <span
                        wire:click.prevent="eventFavourite({{ $event->id }})"
                        class="absolute right-2 -bottom-2 z-10 bg-gray-50 shadow rounded-full p-1">
                                @if(count($event->favourites))
                            <x-heroicon-s-heart class="w-4 h-4"/>
                        @else
                            <x-heroicon-o-heart class="w-4 h-4"/>
                        @endif
                            </span>

                </a>

                <div class="p-5">
                    <a href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                        <h5 class="mb-2 text-lg font-bold tracking-tight text-gray-900 dark:text-white">
                            {{ $event->category->categoryTranslations->first()?->name }}
                        </h5>

                        <p class="mb-3 font-normal text-xl text-gray-700 dark:text-gray-400">
                            {{ $event->eventTranslations->first()?->name }}
                        </p>
                    </a>
                </div>
            </div>

        @endforeach
    </div>

    {{ $events->links() }}
</x-filament-panels::page>
