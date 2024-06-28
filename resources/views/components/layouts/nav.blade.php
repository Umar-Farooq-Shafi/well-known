@php
    use App\Models\AppLayoutSetting;use Illuminate\Support\Facades\Storage;

    $layout = AppLayoutSetting::query()->first();

    $logo = null;

    if ($layout->logo_name) {
        $logo = Storage::disk('public')->url('layout/' . $layout->logo_name);
    }

    $options = [
        'concert-music' => [
            'name' => 'Concert / Music',
            'icon' => 'fas-music'
        ],
        'tour-and-adventure' => [
            'name' => 'Tour and Adventure',
            'icon' => 'fas-campground'
        ],
        'movies' => [
            'name' => 'Movies',
            'icon' => 'fas-film'
        ],
        'workshop-training' => [
            'name' => 'Workshop / Training',
            'icon' => 'fas-chalkboard-teacher'
        ],
        'all-categories' => [
            'name' => 'All Categories',
            'icon' => 'fas-folder-open'
        ]
    ];
@endphp

<nav class="bg-white dark:bg-gray-900 fixed w-full z-20 top-0 start-0 border-b border-gray-200 dark:border-gray-600">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{ env('APP_URL') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ $logo }}" class="h-8" alt="Flowbite Logo">
        </a>
        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
            <button type="button"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-4 py-2 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Get started
            </button>
            <button data-collapse-toggle="navbar-sticky" type="button"
                    class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    aria-controls="navbar-sticky" aria-expanded="false">
                <span class="sr-only">Open main menu</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 17 14">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M1 1h15M1 7h15M1 13h15"/>
                </svg>
            </button>
        </div>

        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700">
                <li>
                    <a href="{{ env('APP_URL') }}"
                       class="flex gap-x-1 my-2 py-4 px-3 text-white bg-blue-700 rounded md:bg-transparent md:text-blue-700 md:p-0 md:dark:text-blue-500"
                       aria-current="page">
                        <x-heroicon-o-home class="h-6 w-6"/>

                        Home
                    </a>
                </li>

                <li>
                    <a href="{{ route('events') }}"
                       class="flex gap-x-1 my-2 py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                        <x-heroicon-o-calendar class="h-6 w-6"/>

                        Browse Events
                    </a>
                </li>

                <li>

                    <div {{ $attributes }}>
                        <div class="relative inline-block text-left"
                             x-data="{
                                open: false,
                                toggle() {
                                    if (this.open) {
                                        return this.close()
                                    }

                                    this.open = true
                                },
                                close(focusAfter) {
                                    this.open = false

                                    focusAfter && focusAfter.focus()
                                }
                            }"
                             x-on:keydown.escape.prevent.stop="close($refs.button)"
                             x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                             x-id="['dropdown-button']">
                            <div>
                                <button
                                    x-ref="button"
                                    x-on:click="toggle()"
                                    :aria-expanded="open"
                                    :aria-controls="$id('dropdown-button')"
                                    type="button"
                                    class="inline-flex py-2 text-gray-900 rounded hover:bg-gray-50 focus:outline-none"
                                    id="menu-button" aria-expanded="true" aria-haspopup="true">
                                    <x-fas-stream class="h-4 w-4 mt-1 mr-1"/>

                                    Explore
                                    <svg class="-mr-1 ml-2 mt-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg"
                                         viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>


                            <div x-ref="panel"
                                 x-show="open"
                                 x-transition.origin.top.right
                                 x-on:click.outside="close($refs.button)"
                                 :id="$id('dropdown-button')"
                                 style="display: none;"
                                 class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                <div class="py-1" role="none">
                                    @foreach($options as $key => $option)
                                        <a href="#" x-on:click="close($refs.button)"
                                           class="flex gap-x-1 items-center text-gray-800 font-medium no-underline px-4 py-2 text-sm"
                                           role="menuitem" tabindex="-1"
                                           id="menu-item-{{$key}}">
                                            <x-dynamic-component :component="$option['icon']" class="h-6 w-6" />


                                            {{ $option['name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                </li>

                <li>
                    <a href="#"
                       class="flex gap-x-1 my-2 py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                        <x-heroicon-o-question-mark-circle class="h-6 w-6"/>

                        How it's work?
                    </a>
                </li>

                <li>
                    <a href="#"
                       class="flex gap-x-1 my-2 py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                        <x-fas-credit-card class="h-6 w-6"/>

                        Blog
                    </a>
                </li>

                <li>
                    <a href="#"
                       class="flex gap-x-1 my-2 py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                        <x-fas-ticket class="h-6 w-6"/>

                        My tickets
                    </a>
                </li>

                <li>
                    <a href="#"
                       class="flex gap-x-1 my-2 py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 md:dark:hover:text-blue-500 dark:text-white dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-gray-700">
                        <x-fas-calendar class="h-6 w-6"/>

                        Add my event
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>
