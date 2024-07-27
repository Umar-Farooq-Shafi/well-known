<div class="mt-24">
    <div class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
        <div class="font-bold text-xl">Blog</div>

        <x-breadcrumbs/>
    </div>

    <div class="container mx-auto p-6">
        <div class="bg-gray-100 flex items-center justify-between p-4">
            <p><span class="font-semibold">{{ count($blogs) }}</span> post(s) found</p>

            <div class="flex flex-row gap-x-2 items-center">
                <div wire:loading wire:target="name">
                    <x-heroicon-o-arrow-path class="animate-spin h-5 w-5 text-blue-500"/>
                </div>

                <form>
                    <x-input
                        right-icon="magnifying-glass"
                        placeholder="Search blog posts"
                        wire:model.live.debounce.500ms="name"
                        autofocus
                        wire:loading.attr="disabled"
                    />
                </form>
            </div>
        </div>

        <div class="grid justify-items-center mt-4 grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-y-4">
            @foreach($blogs as $blog)
                @php

                    $blogCategory = \App\Models\BlogPostCategoryTranslation::whereTranslatableId($blog->blogPost->category_id)
                        ->where('locale', app()->getLocale())
                         ->first();
                @endphp

                <a href="{{ route('blog-article', ['slug' => $blog->slug]) }}">
                    <div class="flex flex-col gap-2">
                        <div>
                            <img src="{{ Storage::url('blog/' . $blog->blogPost->image_name) }}" loading="lazy"
                                 alt="Bungy and Canyoning Day Trip" class="w-96 h-96 rounded-lg">

                            <div class="px-2 pt-1 text-sm flex flex-row gap-x-5">
                                <p class="flex items-center gap-x-1">
                                    <x-fas-sitemap class="w-3 h-3" />
                                    {{ $blogCategory?->name }}
                                </p>

                                <p>{{ \Carbon\Carbon::make($blog->blogPost->created_at)->diffForHumans() }}</p>
                            </div>
                        </div>

                        <p class="font-semibold w-3/4 text-xl break-words">{{ $blog->name }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</div>
