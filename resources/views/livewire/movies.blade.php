@php
    $categoryTrans = \App\Models\CategoryTranslation::whereName('Movies')->first();
@endphp

<div class="mt-24">
    <div class="flex justify-between mx-40 my-4">
        <div class="font-bold text-xl">Movies</div>

        <x-breadcrumbs/>
    </div>

    <livewire:components.events.index :category="$categoryTrans->translatable_id" />
</div>
