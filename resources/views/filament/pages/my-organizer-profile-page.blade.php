@php
    use App\Models\Organizer;

    $orgSlug = Organizer::findOrFail(auth()->user()->organizer_id)->slug;
@endphp

<x-filament-panels::page>
    <x-filament::section>
        <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
            <a class="underline" href="{{ route('organizer-profile', ['slug' => $orgSlug]) }}">Click here</a> to
            preview your profile
        </div>

        <form wire:submit="submit">
            {{ $this->form }}

            <x-filament::button type="submit" class="mt-3">
                {{ __('Save') }}
            </x-filament::button>
        </form>
    </x-filament::section>
</x-filament-panels::page>
