<div class="mt-24">
    @push('metas')
        <meta name="description" content="{{ Str::limit(strip_tags(nl2br($blogPostTranslation->content)), 200) }}..."/>
        @if($blogPostTranslation->tags)
            <meta name="keywords"
                  content="{{ $blogPostTranslation->name }}, {{ $blogPost->category->name }}, @foreach(explode(',', $blogPostTranslation->tags) as $tag){{ $tag }}@if(!$loop->last), @endif @endforeach"/>
        @else
            <meta name="keywords" content="{{ config('settings.website_keywords_' . app()->getLocale()) }}"/>
        @endif
        <meta property="og:title" content="{{ $blogPostTranslation->name }}"/>
        <meta property="og:image"
              content="{{ $blogPost->image_name ? $blogPost->getImagePath() : $blogPost->getImagePlaceholder() }}"/>
        <meta property="og:description"
              content="{{ Str::limit(strip_tags(nl2br($blogPostTranslation->content)), 200) }}..."/>
        <meta name="twitter:title" content="{{ $blogPostTranslation->name }}"/>
        <meta name="twitter:image"
              content="{{ $blogPost->image_name ? $blogPost->getImagePath() : $blogPost->getImagePlaceholder() }}"/>
        <meta name="twitter:image:alt" content="{{ $blogPostTranslation->name }}"/>
        <meta name="twitter:description"
              content="{{ Str::limit(strip_tags(nl2br($blogPostTranslation->content)), 200) }}..."/>
    @endpush

    <div class="flex bg-gray-300 px-4 py-2 rounded justify-between mx-40 my-4">
        <div class="font-bold text-xl">{{ $blogPostTranslation->name }}</div>

        <x-breadcrumbs/>
    </div>

    <div class="container mx-auto p-6">
        <div class="flex flex-col lg:flex-row justify-between mb-4">
            <aside class="w-full lg:w-1/5 mb-4 lg:mb-0 space-y-2 mx-4">
                <article class="card-group-item" id="search-open" x-data="{ searchOpen: true }">
                    <header class="p-3 cursor-pointer bg-gray-200 rounded" @click="searchOpen = ! searchOpen">
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-700">{{ __('Search blog') }}</h6>

                            <template x-if="searchOpen">
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </template>

                            <template x-if="!searchOpen">
                                <x-fas-chevron-up class="w-4 h-4"/>
                            </template>
                        </div>
                    </header>

                    <template x-teleport="#search-open">
                        <div
                            class="flex flex-row gap-x-2 items-center p-4"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            x-show="searchOpen">
                            <div wire:loading wire:target="keyword">
                                <x-heroicon-o-arrow-path class="animate-spin h-5 w-5 text-blue-500"/>
                            </div>

                            <form>
                                <x-input
                                    right-icon="magnifying-glass"
                                    placeholder="Keywords"
                                    wire:model.live.debounce.500ms="keyword"
                                    class="w-full"
                                    wire:loading.attr="disabled"
                                />
                            </form>
                        </div>
                    </template>
                </article>

                <article id="blog-post-open" x-data="{ blogPostCatOpen: true }">
                    <header class="p-3 bg-gray-200 cursor-pointer rounded"
                            @click="blogPostCatOpen = ! blogPostCatOpen">
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-700">{{ __('Categories') }}</h6>

                            <template x-if="blogPostCatOpen">
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </template>

                            <template x-if="!blogPostCatOpen">
                                <x-fas-chevron-up class="w-4 h-4"/>
                            </template>
                        </div>
                    </header>

                    <template x-teleport="#blog-post-open">
                        <div
                            class="gap-x-2 p-4"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            x-show="blogPostCatOpen">
                            <ul class="list-none">
                                @foreach($blogPostCategories as $category)
                                    @php
                                        $cat = $category->blogPostCategoryTranslations->first();
                                    @endphp

                                    <li class="mb-2 w-full">
                                        <a href="{{ route('blog', ['category' => $cat?->slug]) }}"
                                           class="flex flex-row items-center justify-between text-blue-400">
                                            <p class="flex flex-row gap-x-2 items-center">
                                                <x-fas-chevron-right class="w-3 h-3"/>

                                                {{ $cat?->name }}
                                            </p>

                                            <x-badge primary label="{{ count($category?->blogPosts) }}"/>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </template>
                </article>

                <article id="popular-blog-post-open" x-data="{ popularBlogPostCategories: true }">
                    <header class="p-3 bg-gray-200 cursor-pointer rounded"
                            @click="popularBlogPostCategories = ! popularBlogPostCategories">
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-700">{{ __('Popular') }}</h6>

                            <template x-if="popularBlogPostCategories">
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </template>

                            <template x-if="!popularBlogPostCategories">
                                <x-fas-chevron-up class="w-4 h-4"/>
                            </template>
                        </div>
                    </header>

                    <template x-teleport="#popular-blog-post-open">
                        <div
                            class="gap-x-2 p-4"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            x-show="popularBlogPostCategories">
                            @foreach($popularBlogPosts as $popular)
                                @php
                                    $currBlogPost = $popular->blogPostTranslations->first()
                                @endphp

                                <article class="flex items-center mb-3">
                                    <div class="w-1/3 pr-2">
                                        <a href="{{ route('blog-article', ['slug' => $currBlogPost->slug]) }}">
                                            <img
                                                src="{{ $popular->image_name ? $popular->getImagePath() : $popular->getImagePlaceholder() }}"
                                                class="img-lazy-load img-fluid h-16 w-16"
                                                alt="{{ $currBlogPost?->name }}"
                                                loading="lazy"
                                            />
                                        </a>
                                    </div>

                                    <div class="w-2/3">
                                        <small class="text-gray-500">{{ $popular->blogPostCategory->name }}</small>

                                        <h6 class="mb-0">
                                            <a href="{{ route('blog-article', ['slug' => $currBlogPost->slug]) }}"
                                               class="text-gray-700 text-truncate">{{ $currBlogPost->name }}</a>
                                        </h6>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </template>
                </article>

                <article id="tags" x-data="{ tags: true }">
                    <header class="p-3 bg-gray-200 cursor-pointer rounded" @click="tags = ! tags">
                        <div class="flex items-center justify-between">
                            <h6 class="text-gray-700">{{ __('Tags') }}</h6>

                            <template x-if="tags">
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </template>

                            <template x-if="!tags">
                                <x-fas-chevron-up class="w-4 h-4"/>
                            </template>
                        </div>
                    </header>

                    <template x-teleport="#tags">
                        <div
                            class="gap-x-2 p-4"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 scale-90"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-90"
                            x-show="tags">
                            @foreach(explode(',', $blogPostTranslation->tags) as $tag)
                                <a href="{{ route('blog', ['keyword' => $tag]) }}"
                                   class="btn btn-sm btn-default mr-2 mb-2 mt-2">{{ $tag }}</a>
                            @endforeach
                        </div>
                    </template>
                </article>

                @if(config('settings.newsletter_enabled') == 'yes' && config('settings.mailchimp_api_key') && config('settings.mailchimp_list_id'))
                    @include('partials.newsletter-box')
                @endif
            </aside>

            <div class="w-full lg:w-3/4 order-0 lg:order-1">
                <div class="bg-white shadow p-4 rounded">
                    <img class="card-img-top img-lazy-load w-full object-fit max-h-[80vh]"
                         loading="lazy"
                         src="{{ $blogPost->image_name ? asset($blogPost->getImagePath()) : $blogPost->getImagePlaceholder() }}"
                         alt="{{ $blogPostTranslation->name }}"
                    />

                    <div class="card-body">
                        <header class="my-1">
                            <h1 class="text-3xl">{{ $blogPostTranslation->name }}</h1>
                        </header>

                        <p class="mb-4 flex items-center gap-x-4">
                            <small class="text-gray-500 text-base">
                                <a class="flex items-center gap-x-2"
                                   href="{{ route('blog', ['category' => $blogPost->blogPostCategory->slug]) }}">
                                    <x-fas-sitemap class="w-5 h-5"/>

                                    {{ $blogPost->blogPostCategory->name }}
                                </a>
                            </small>

                            <small class="text-gray-500 text-base flex items-center gap-x-2">
                                <x-fas-clock class="w-5 h-5"/>

                                {{ $blogPost->created_at->diffForHumans() }}
                            </small>

                            @if($blogPost->readtime)
                                <small class="text-gray-500">
                                    <x-fas-book-reader class="w-4 h-4"/>

                                    {{ $blogPost->readtime }} {{ __('min read') }}
                                </small>
                            @endif
                        </p>

                        <article>
                            {!! $blogPostTranslation->content !!}
                        </article>

                        <hr class="my-12 h-0.5 border-t-0 bg-neutral-200 dark:bg-white/10"/>

                        <div class="flex flex-wrap">
                            @if($blogPost->tags)
                                <div class="w-full lg:w-1/2 text-center lg:text-left">
                                    @foreach(explode(',', $blogPostTranslation->tags) as $tag)
                                        <a href="{{ route('blog.index', ['keyword' => $tag]) }}"
                                           class="btn btn-sm btn-default mr-3 mb-3 mt-3">{{ $tag }}</a>
                                    @endforeach
                                </div>
                            @endif

                            <div class="w-full text-center">
                                <div class="flex flex-row gap-x-2 justify-center">
                                    <a
                                        target="_blank"
                                        href="https://www.facebook.com/sharer/sharer.php?kid_directed_site=0&sdk=joey&u=https%3A%2F%2Fwww.dev.aafnoticket.com%2Fen%2Fblog-article%2Fmonali-thakur-live-in-nepal&display=popup&ref=plugin&src=share_button"
                                        class="bg-blue-500 flex gap-x-1 py-0.5 px-1 rounded items-center">
                                        <x-fab-facebook class="w-4 h-4 text-white"/>

                                        <small>Share 0</small>
                                    </a>

                                    <a
                                        target="_blank"
                                        href="https://twitter.com/share"
                                        class="bg-[#1DA1F2] flex gap-x-1 py-0.5 px-1 rounded items-center">
                                        <x-fab-x-twitter class="w-4 h-4 text-white"/>

                                        <small>Twitter</small>
                                    </a>

                                    <div class="bg-[#0077B5] flex gap-x-1 py-0.5 px-1 rounded items-center">
                                        <x-fab-linkedin class="w-4 h-4 text-white"/>

                                        <small>Share</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(config('settings.blog_comments_enabled') != 'no')
                            <div class="mt-5">
                                @if(config('settings.blog_comments_enabled') == 'native')
                                    @include('comments::comments', ['model' => $blogPost])
                                @elseif(config('settings.blog_comments_enabled') == 'facebook' && config('settings.facebook_app_id'))
                                    <div id="fb-root"></div>
                                    <script async defer crossorigin="anonymous"
                                            src="https://connect.facebook.net/{{ app()->getLocale() }}/sdk.js#xfbml=1&version=v4.0&appId={{ config('settings.facebook_app_id') }}&autoLogAppEvents=1"></script>
                                    <div class="fb-comments" data-href="{{ request()->url() }}" data-width="100%"
                                         data-numposts="5"></div>
                                @elseif(config('settings.blog_comments_enabled') == 'disqus' && config('settings.disqus_subdomain'))
                                    <div id="disqus_thread"></div>
                                    <script>
                                        var disqus_config = function () {
                                            this.page.url = "{{ request()->url() }}";
                                            this.page.identifier = "{{ $blogPost->slug }}";
                                            this.language = "{{ app()->getLocale() }}";
                                        };
                                        (function () {
                                            var d = document, s = d.createElement('script');
                                            s.src = 'https://{{ config('settings.disqus_subdomain') }}.disqus.com/embed.js';
                                            s.setAttribute('data-timestamp', +new Date());
                                            (d.head || d.body).appendChild(s);
                                        })();
                                    </script>
                                    <noscript>Please enable JavaScript to view the <a
                                            href="https://disqus.com/?ref_noscript">comments powered by
                                            Disqus.</a>
                                    </noscript>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if(count($similarBlogPosts))
            @push('styles')
                <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
            @endpush

            <div class="flex flex-wrap w-full justify-center">
                <h3 class="my-5 font-semibold text-2xl">{{ __('Related posts') }}</h3>

                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach($similarBlogPosts as $similar)
                            @php
                                $similarTrans = $similar->blogPostTranslations->first();
                            @endphp
                            <div class="card mb-4 shadow-none blog-post-card swiper-slide">
                                <a href="{{ route('blog-article', ['slug' => $similarTrans->slug]) }}">
                                    <img class="w-72 h-72 object-fill"
                                         loading="lazy"
                                         src="{{ $similar->image_name ? $similar->getImagePath() : $similar->getImagePlaceholder() }}"
                                         alt="{{ $similarTrans->name }}"
                                    />
                                </a>

                                <div class="card-body">
                                    <p class="card-text">
                                        @if ($similar->blogPostCategory)
                                            <small class="text-muted mr-3 mt-2">
                                                <a href="{{ route('blog', ['category' => $similar->blogPostCategory->slug]) }}"
                                                   class="text-dark flex gap-x-2 flex-row">
                                                    <x-fas-sitemap class="w-4 h-4"/>

                                                    {{ $similar->blogPostCategory->name }}
                                                </a>
                                            </small>
                                        @endif
                                        @isset($showdate)
                                            <small class="text-muted mr-3">
                                                <x-fas-clock class="w-4 h-4"/>

                                                {{ $blogpost->createdAt->diffForHumans() }}
                                            </small>
                                        @endisset
                                        @if ($similar->readtime)
                                            <small class="text-muted"><i
                                                    class="fas fa-book-reader"></i> {{ $similar->readtime }} {{ __('min read') }}
                                            </small>
                                        @endif
                                    </p>
                                    <h5 class="card-title"><a
                                            href="{{ route('blog-article', ['slug' => $similarTrans->slug]) }}"
                                            class="text-dark">{{ $similarTrans->name }}</a></h5>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            </div>

            @push('scripts')
                <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
                <script>
                    var swiper = new Swiper(".mySwiper", {
                        slidesPerView: 4,
                        spaceBetween: 20,
                        freeMode: true,
                        pagination: {
                            el: ".swiper-pagination",
                            clickable: true,
                        },
                        breakpoints: {
                            640: {
                                slidesPerView: 2,
                                spaceBetween: 20,
                            },
                            768: {
                                slidesPerView: 3,
                                spaceBetween: 40,
                            },
                            1024: {
                                slidesPerView: 4,
                                spaceBetween: 50,
                            },
                        },
                    });
                </script>
            @endpush

        @endif
    </div>

</div>
