<div class="mt-[25%] lg:mt-[12%] md:mt-[17%] container mx-auto">
    <div class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
        <div class="font-bold text-xl">Event Categories</div>

        <x-breadcrumbs/>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mx-12 sm:mx-8 lg:mx-40 my-8">
        @foreach($categories as $category)
            <a href="{{ route('event', ['slug' => $category->categoryTranslations->first()->slug]) }}">
                <div class="relative group">
                    <img src="{{ Storage::url('categories/' . $category->image_name) }}" alt="Workshop / Training" loading="lazy"
                         class="w-full h-64 object-cover rounded-lg shadow-lg">
                    <div
                        class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <span
                            class="text-white text-lg font-semibold">{{ $category->categoryTranslations->first()?->name }}</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
