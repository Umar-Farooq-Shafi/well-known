<div>
    <form class="max-w-md mx-auto mt-2 mb-5">
        <label for="default-search"
               class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <div wire:loading wire:target="query">
                    <x-heroicon-o-arrow-path class="animate-spin h-4 w-4 text-blue-500"/>
                </div>

                <div wire:loading.class="hidden" wire:target="query">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                         xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
            </div>

            <input type="search" id="default-search"
                   class="block w-full px-1 py-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                   placeholder="Search for events"
                   wire:model.live.debounce.500ms="query"
                   wire:loading.attr="disabled"
                   required
            />
        </div>

        @if($events)
            <div class="absolute z-10 max-w-md w-full border divide-y shadow-2xl overflow-y-auto bg-white mt-1">
                @if(count($events))
                    <div class="flex flex-col gap-y-2">
                        @foreach($events as $eventTranslation)
                            <a class="flex gap-x-4 px-2 py-4"
                               href="{{ route('event', ['slug' => $eventTranslation->slug]) }}">
                                <img
                                    class="w-16 h-16 object-fill"
                                    src="{{ Storage::url('events/' . $eventTranslation->event->image_name) }}"
                                    loading="lazy"
                                    alt="{{ $eventTranslation->name }}"
                                />

                                <div>
                                    <p class="mb-3 text-base font-semibold text-gray-700">
                                        {{ $eventTranslation->name }}
                                    </p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="p-2 font-semibold text-base">No Result found</p>
                @endif
            </div>
        @endif
    </form>
</div>
