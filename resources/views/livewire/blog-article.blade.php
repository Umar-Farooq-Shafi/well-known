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
        <div class="flex flex-wrap">
            <aside class="w-full lg:w-1/4 order-1 lg:order-none">
                <div class="card bg-gray-100">
                    <article class="card-group-item">
                        <header class="px-2 py-4 bg-gray-200">
                            <a href="#" data-toggle="collapse" data-target="#filter-keyword"
                               class="flex items-center justify-between">
                                <h6 class="text-gray-700">{{ __('Search blog') }}</h6>
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </a>
                        </header>

                        <div class="p-4 collapse show" id="filter-keyword">
                            <form wire:submit="search" class="pb-3">
                                <input
                                    wire:model="keyword"
                                    class="form-control mb-3" placeholder="{{ __('Keyword') }}" name="keyword"
                                    type="text"
                                />

                                <button type="submit" class="btn btn-outline-primary btn-block">
                                    <x-fas-search class="w-4 h-4"/>
                                    {{ __('Search') }}
                                </button>
                            </form>
                        </div>
                    </article>

                    <article class="card-group-item">
                        <header class="p-4 bg-gray-200">
                            <a href="#" data-toggle="collapse" data-target="#filter-event-type"
                               class="flex items-center justify-between">
                                <h6 class="text-gray-700">{{ __('Categories') }}</h6>
                                <x-fas-chevron-down class="w-4 h-4"/>
                            </a>
                        </header>
                        <div class="p-4 collapse show" id="filter-event-type">
                            <ul class="list-none">
                                @foreach($blogPostCategories as $category)
                                    <li class="mb-2">
                                        <a href="{{ route('blog', ['category' => $category->slug]) }}"
                                           class="flex items-center justify-between">
                                            <span>{{ $category->name }}</span>
                                            <span
                                                class="badge badge-primary">{{ count($category->blogPosts) }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </article>
                    <article class="card-group-item">
                        <header class="p-4 bg-gray-200">
                            <a href="#" data-toggle="collapse" data-target="#collapsePopular"
                               class="flex items-center justify-between">
                                <h6 class="text-gray-700">{{ __('Popular') }}</h6>
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </header>
                        <div class="p-4 collapse show" id="collapsePopular">
                            @foreach($popularBlogPosts as $popular)
                                @php
                                    $currBlogPost = $popular->blogPostTranslations->first()
                                @endphp
                                <article class="flex items-center mb-3">
                                    <div class="w-1/3 pr-2">
                                        <a href="#">
                                            <img
                                                src="{{ $popular->image_name ? $popular->getImagePath() : $popular->getImagePlaceholder() }}"
                                                class="img-lazy-load img-fluid"
                                                alt="{{ $currBlogPost?->name }}"
                                                loading="lazy"
                                            />
                                        </a>
                                    </div>
                                    <div class="w-2/3">
                                        <small class="text-gray-500">{{ $popular->blogPostCategory->name }}</small>
                                        <h6 class="mb-0">
                                            <a href="#"
                                               class="text-gray-700">{{ $currBlogPost->name }}</a>
                                        </h6>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    </article>
                    <article class="card-group-item">
                        <header class="p-4 bg-gray-200">
                            <a href="#" data-toggle="collapse" data-target="#collapseTags"
                               class="flex items-center justify-between">
                                <h6 class="text-gray-700">{{ __('Tags') }}</h6>
                                <i class="fa fa-chevron-down"></i>
                            </a>
                        </header>
                        <div class="p-4 collapse show" id="collapseTags">
                            @foreach(explode(',', $blogPostTranslation->tags) as $tag)
                                <a href="{{ route('blog', ['keyword' => $tag]) }}"
                                   class="btn btn-sm btn-default mr-2 mb-2 mt-2">{{ $tag }}</a>
                            @endforeach
                        </div>
                    </article>
                    @if(config('settings.newsletter_enabled') == 'yes' && config('settings.mailchimp_api_key') && config('settings.mailchimp_list_id'))
                        @include('partials.newsletter-box')
                    @endif
                </div>
            </aside>
            <div class="w-full lg:w-3/4 order-0 lg:order-1">
                <div class="card box">
                    <img class="card-img-top img-lazy-load"
                         src="{{ $blogPost->image_name ? asset($blogPost->getImagePath()) : $blogPost->getImagePlaceholder() }}"
                         alt="{{ $blogPostTranslation->name }}">
                    <div class="card-body">
                        <header class="mb-1">
                            <h1 class="text-3xl">{{ $blogPostTranslation->name }}</h1>
                        </header>
                        <p class="mb-4">
                            <small class="text-gray-500 mr-2"><a
                                    href="{{ route('blog', ['category' => $blogPost->blogPostCategory->slug]) }}"><i
                                        class="fas fa-sitemap"></i> {{ $blogPost->blogPostCategory->name }}
                                </a></small>
                            <small class="text-gray-500 mr-2"><i
                                    class="far fa-clock"></i> {{ $blogPost->created_at->diffForHumans() }}
                            </small>
                            @if($blogPost->readtime)
                                <small class="text-gray-500"><i
                                        class="fas fa-book-reader"></i> {{ $blogPost->readtime }} {{ __('min read') }}
                                </small>
                            @endif
                        </p>
                        <article>
                            {!! $blogPostTranslation->content !!}
                        </article>
                        <hr>
                        <div class="flex flex-wrap">
                            @if($blogPost->tags)
                                <div class="w-full lg:w-1/2 text-center lg:text-left">
                                    @foreach(explode(',', $blogPostTranslation->tags) as $tag)
                                        <a href="{{ route('blog.index', ['keyword' => $tag]) }}"
                                           class="btn btn-sm btn-default mr-3 mb-3 mt-3">{{ $tag }}</a>
                                    @endforeach
                                </div>
                            @endif
                            <div class="w-full lg:w-1/2 text-center lg:text-right sharer mt-3">
                                <!-- Social media share buttons can be added here -->
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
            <div class="flex flex-wrap">
                <div class="w-full">
                    <h3 class="mt-5 mb-4 text-center">{{ __('Related posts') }}</h3>
                    <div class="owl-carousel owl-theme" data-margin="15" data-items="4" data-dots="false"
                         data-nav="true" data-autoplay="false">
                        @foreach($similarBlogPosts as $similar)
                            {{--                                @include('partials.blog-card', ['blogpost' => $similar, 'thumbnailsize' => [241, 241]])--}}
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>
