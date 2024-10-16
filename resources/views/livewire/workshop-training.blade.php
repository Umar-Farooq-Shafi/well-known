@php
    $categoryTrans = \App\Models\CategoryTranslation::whereName('Workshop / Training')->first();
@endphp

<div class="mt-[25%] lg:mt-[12%] md:mt-[17%] container mx-auto">
    <div class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
        <div class="font-bold text-xl">Workshop / Training</div>

        <x-breadcrumbs/>
    </div>

    <livewire:components.events.index :category="$categoryTrans->translatable_id" />
</div>
