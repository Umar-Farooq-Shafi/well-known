@php
    $record = $widgetData['record'];

    $eventTrans = \App\Models\EventTranslation::whereTranslatableId($record->id)
        ->where('locale', app()->getLocale())
        ->first();

    $eventDate = $record->eventDates->first();
@endphp

<x-filament-widgets::widget>
    <x-filament::section class="h-full flex items-center">
        <div class="flex flex-row gap-x-2">
            <img
                src="{{ Storage::url('events/' . $record->image_name) }}" alt="Canyon Swing"
                loading="lazy"
                width="100"
                height="100"
                class="object-contain opacity-100"
            />

            <div class="flex flex-col gap-y-1">
                <h1 class="font-bold text-primary-500">{{ $eventTrans?->name }}</h1>

                <p class="text-sm">
                    When:
                    <span>
                         {{ $eventDate?->startdate ? \Carbon\Carbon::make($eventDate?->startdate)->format('Y-m-d') : '' }}
                    </span>
                </p>

                <p class="text-sm">
                    Where:

                    @if($venue = $eventDate?->venue)
                        <span>{{ $venue?->name }} {{ $venue->stringifyAddress }}</span>
                    @else
                        <span>Online</span>
                    @endif
                </p>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
