@php
    $categoryTrans = \App\Models\CategoryTranslation::whereName('Movies')->first();
@endphp

<div class="mt-[25%] lg:mt-[12%] md:mt-[17%] container mx-auto">
    <div class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded my-4">
        <div class="font-bold text-xl">Movies</div>

        <x-breadcrumbs/>
    </div>

    <livewire:components.events.index :category="$categoryTrans->translatable_id" />
</div>
