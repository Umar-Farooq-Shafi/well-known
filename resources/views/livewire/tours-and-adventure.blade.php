@php
    $categoryTrans = \App\Models\CategoryTranslation::whereName('Tours and Adventure')->first();
@endphp

<div class="mt-24">
    <div class="flex justify-between bg-gray-300 px-4 py-2 rounded mx-32 my-4">
        <div class="font-bold text-xl">Tours And Adventure</div>

        <x-breadcrumbs/>
    </div>

    <livewire:components.events.index :category="$categoryTrans->translatable_id" />
</div>
