@php
    $categoryTrans = \App\Models\CategoryTranslation::whereName('Concert / Music')->first();
@endphp

<div class="mt-24">
    <div class="flex justify-between mx-40 my-4">
        <div></div>

        <x-breadcrumbs/>
    </div>

    <livewire:components.events.index :category="$categoryTrans->translatable_id" />
</div>
