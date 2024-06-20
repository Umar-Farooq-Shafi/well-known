<x-filament-panels::page>
    <form wire:submit.prevent="submit">
        {{ $this->form }}

        <div class="pt-4">
            {{ $this->getFormAction() }}
        </div>
    </form>
</x-filament-panels::page>
