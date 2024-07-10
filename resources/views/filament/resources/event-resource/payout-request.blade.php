<x-filament::section>
    <x-slot name="description">
        Once the payout request submitted, the event date will be locked and the sales will be suspended for the
        specific event date. If you wish, you can wait until the start date of the event date before requesting the
        payout. You can cancel the payout request any time before it is processed
    </x-slot>

    <p>{{ $record->eventDates()->first()->startdate }}</p>
</x-filament::section>
