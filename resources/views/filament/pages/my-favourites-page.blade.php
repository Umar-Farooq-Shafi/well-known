@php use Illuminate\Support\Facades\Storage; @endphp

<x-filament-panels::page>
    <x-filament::section class="mt-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($events as $event)
                <div class="inline-block px-3">
                    <a href="{{ route('event', ['slug' => $event->eventTranslations->first()->slug]) }}">
                        <div
                            class="w-72 h-72 max-w-xs overflow-hidden rounded-lg shadow-md bg-white hover:shadow-xl transition-shadow duration-300 ease-in-out"
                        >
                            <img class="w-full h-40"
                                 src="{{ Storage::url('events/' . $event->image_name) }}"
                                 alt="{{ $event->eventTranslations->first()->name }}"/>

                            <p class="p-2 font-medium text-lg">{{ $event->eventTranslations->first()->name }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{ $events->links() }}
    </x-filament::section>
</x-filament-panels::page>
