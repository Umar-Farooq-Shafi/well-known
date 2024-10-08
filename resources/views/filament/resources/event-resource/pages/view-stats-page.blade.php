@php
    use Illuminate\Support\Facades\App;

    $event = $this->record;
    $eventTranslation = $event->eventTranslations()->where('locale', App::getLocale())->first();
    $eventDate = $event->eventDates()->first();
    $venue = $eventDate->venue;
    $countryTrans = $venue ? $venue->country->countryTranslations()->where('locale', App::getLocale())->first() : null;
@endphp

<x-filament-panels::page>
    <section class="bg-gray-200 flex flex-col gap-x-2 p-4 shadow">
        <div class="flex flex-row gap-x-2 items-center">
            <x-fas-chart-line class="w-4 h-4"/>

            <h1 class="font-semibold text-xl">{{ $eventTranslation?->name }}</h1>
        </div>

        <div class="mt-2" style="width: 10rem">
            <x-filament::badge size="xl">
                Event already started
            </x-filament::badge>
        </div>

        <div class="mt-2 font-medium">
            <p>When: {{ $eventDate?->startdate ? \Carbon\Carbon::make($eventDate->startdate)->format('l jS F Y, h:i A') : '' }}</p>
            <p>
                Where: {{ $venue?->street }} {{ $venue?->street2 }} {{ $venue?->city }} {{ $venue?->state }} {{ $countryTrans?->name }}</p>
        </div>
    </section>

    {{ $this->form }}

    @livewire(\App\Filament\Resources\EventResource\Widgets\StatsOverview::class, ['event' => $event])

</x-filament-panels::page>
