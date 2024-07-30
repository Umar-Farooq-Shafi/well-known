<div class="mt-24">
    <div
        class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
        <div class="text-xl">Add your review for <span class="font-bold">{{ $eventTranslation->name }}</span></div>

        <x-breadcrumbs/>
    </div>

    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center">
            <div class="flex gap-x-6">
                <img
                    loading="lazy"
                    class="w-52 h-52"
                    alt="{{ $eventTranslation->name }}"
                    src="{{ \Illuminate\Support\Facades\Storage::url('events/' . $event->image_name) }}"
                />

                <h1 class="text-3xl font-semibold text-blue-400">{{ $eventTranslation->name }}</h1>
            </div>
        </div>


    </div>
</div>
