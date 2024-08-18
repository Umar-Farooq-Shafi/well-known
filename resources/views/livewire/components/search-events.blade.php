<div>
    <form wire:submit.prevent="submit" class="flex items-center justify-center w-full mt-2 mb-5">
        <div class="relative flex items-center divide-x-2 rounded bg-white p-1">
            <x-select
                label=""
                placeholder="Select Country"
                wire:model.debounce.500ms="country"
                option-label="name"
                option-value="id"
                wire:loading.attr="disabled"
                class="w-[180px]"
                :async-data="route('api.events.country')"
            />

            <input
                id="date-picker-input"
                placeholder="All Dates"
                wire:model.debounce.500ms="dates"
                class="appearance-none w-[180px] border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block pl-3 pr-8 py-2.5"
            />

            <div class="relative w-[400px]">
                <input
                    type="text"
                    wire:model.debounce.500ms="query"
                    class="w-[380px] bg-white border border-gray-300 text-gray-900 text-sm focus:ring-blue-500 focus:border-blue-500 block pl-10 pr-3 py-2.5"
                    placeholder="Search by Event"
                />

                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
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

                @if($events)
                    <div class="absolute z-10 max-w-md w-full border divide-y shadow-2xl overflow-y-auto bg-white left-0 mt-1">
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
            </div>

            <button
                type="submit"
                class="bg-blue-500 text-white text-lg font-medium px-4 py-[7px] hover:bg-blue-600 focus:ring-4 focus:ring-blue-300">
                Search
            </button>
        </div>
    </form>

    @push('scripts')
        <script>
            addEventListener("load", () => {
                const input = document.getElementById('date-picker-input');

                flatpickr(input, {
                    mode: "range",
                    dateFormat: "M-d",
                });
            });
        </script>
    @endpush
</div>
