@php
    use App\Models\AppLayoutSetting;
    use Illuminate\Support\Facades\Storage;

    $layout = AppLayoutSetting::query()->first();
    $logo = $layout->logo_name ? Storage::disk('public')->url('layout/' . $layout->logo_name) : null;

    $options = [
        'concert-music' => ['name' => 'Concert / Music', 'icon' => 'fas-music'],
        'tour-and-adventure' => ['name' => 'Tour and Adventure', 'icon' => 'fas-campground'],
        'movies' => ['name' => 'Movies', 'icon' => 'fas-film'],
        'workshop-training' => ['name' => 'Workshop / Training', 'icon' => 'fas-chalkboard-teacher'],
        'all-categories' => ['name' => 'All Categories', 'icon' => 'fas-folder-open']
    ];
@endphp

<nav class="fixed w-full z-20 top-0 start-0 border-b border-gray-200"
     style="background-image: linear-gradient(35deg, #535eb2, #009cde)">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4">
        <a href="{{ env('APP_URL') }}" class="flex items-center space-x-3 rtl:space-x-reverse">
            <img src="{{ $logo }}" class="h-8" alt="App Logo">
        </a>

        @if(auth()->check())
            <div class="relative flex items-center md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse"
                 x-data="{ open: false }">
                <button @click="open = !open" type="button"
                        class="flex text-sm bg-gray-800 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                        id="user-menu-button" aria-expanded="false">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full" src="{{ auth()->user()->getFilamentAvatarUrl() }}"
                         alt="user photo">
                </button>
                <!-- Dropdown menu -->
                <div x-show="open" @click.outside="open = false" x-transition
                     class="absolute top-0 right-0 mt-12 w-48 bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600 z-50">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white">{{ auth()->user()->username }}</span>
                        <span
                            class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ auth()->user()->email }}</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="{{ route('filament.admin.pages.dashboard') }}"
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Dashboard</a>
                        </li>
                        <li>
                            <form method="POST" class="w-full" action="{{ route('filament.admin.auth.logout') }}">
                                @csrf
                                <button type="submit"
                                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                    Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                <button data-collapse-toggle="navbar-user" type="button"
                        class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                        aria-controls="navbar-user" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 17 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M1 1h15M1 7h15M1 13h15"/>
                    </svg>
                </button>
            </div>
        @else
            <div class="flex items-center md:order-2 md:space-x-0 rtl:space-x-reverse">
                <a href="{{ route('filament.admin.auth.login') }}"
                   class="p-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Login</a>
                <a href="{{ route('filament.admin.auth.register') }}"
                   class="p-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign
                    Up</a>
            </div>
        @endif

        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium border border-gray-100 rounded-lg md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:dark:bg-gray-900 dark:border-gray-700">
                <li>
                    <a href="{{ env('APP_URL') }}"
                       class="flex gap-x-1 my-2 py-4 px-3 text-white rounded md:bg-transparent md:p-0 md:dark:text-blue-500"
                       aria-current="page">
                        <x-heroicon-o-home class="h-6 w-6"/>
                        Home
                    </a>
                </li>

                <li>
                    <a href="{{ route('events') }}"
                       class="flex gap-x-1 my-2 py-2 px-3 text-gray-900 rounded hover:bg-transparent hover:text-blue-700 md:p-0">
                        <x-heroicon-o-calendar class="h-6 w-6"/>
                        Browse Events
                    </a>
                </li>

                <li>
                    <div {{ $attributes }}>
                        <div class="relative inline-block text-left" x-data="{
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
                        }" x-on:keydown.escape.prevent.stop="close($refs.button)"
                             x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                             x-id="['dropdown-button']">
                            <div>
                                <button x-ref="button" x-on:click="toggle()" :aria-expanded="open"
                                        :aria-controls="$id('dropdown-button')" type="button"
                                        class="inline-flex py-2 text-gray-900 rounded hover:bg-transparent hover:text-blue-700 focus:outline-none"
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

                            <div x-ref="panel" x-show="open" x-transition.origin.top.right
                                 x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')"
                                 style="display: none;"
                                 class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                <div class="py-1" role="none">
                                    @foreach($options as $key => $option)
                                        <a href="#" x-on:click="close($refs.button)"
                                           class="flex gap-x-1 items-center text-gray-800 hover:bg-transparent hover:text-blue-700 font-medium no-underline px-4 py-2 text-sm"
                                           role="menuitem" tabindex="-1" id="menu-item-{{$key}}">
                                            <x-dynamic-component :component="$option['icon']" class="h-6 w-6"/>
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
