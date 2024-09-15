<div wire:init="loadData">
    <x-heroicon-o-arrow-path
        class="animate-spin h-5 mt-8 w-5 text-blue-500"
        wire:loading
        wire:target="loadData"
    />

    <div class="flex flex-col gap-y-2 px-4 justify-center items-center py-8" wire:loading.remove>
        <a
            href="{{ $this->googleCalendarLink() }}"
            target="_blank"
            class="flex items-center gap-2 cursor-pointer text-zinc-800 hover:bg-zinc-50 px-4 py-2 whitespace-nowrap">
            <x-fab-google class="h-4 w-4"/>
            Google Calendar
        </a>

        <a
            href="{{ $this->yahooCalendarLink() }}"
            target="_blank"
            class="flex items-center cursor-pointer gap-2 text-zinc-800 hover:bg-zinc-50 px-4 py-2 whitespace-nowrap">
            <x-fab-yahoo class="h-4 w-4"/>

            Yahoo Calendar
        </a>

        <a
            href="{{ $this->outlookCalendarLink() }}"
            target="_blank"
            class="flex items-center cursor-pointer gap-2 text-zinc-800 hover:bg-zinc-50 px-4 py-2 whitespace-nowrap">
            <x-fab-apple class="h-4 w-4"/>

            iCal Calendar
        </a>

        <a
            href="{{ $this->outlookCalendarLink() }}"
            target="_blank"
            class="flex items-center cursor-pointer gap-2 text-zinc-800 hover:bg-zinc-50 px-4 py-2 whitespace-nowrap">
            <x-fab-yahoo class="h-4 w-4"/>

            Outlook Calendar
        </a>
    </div>

</div>
