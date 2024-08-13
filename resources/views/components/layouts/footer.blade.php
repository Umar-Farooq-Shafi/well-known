@php
    use App\Models\MenuTranslation;

    $firstMenu = MenuTranslation::whereTranslatableId(2)
        ->where('locale', app()->getLocale())
        ->first();

    $secondMenu = MenuTranslation::whereTranslatableId(3)
        ->where('locale', app()->getLocale())
        ->first();

    $thirdMenu = MenuTranslation::whereTranslatableId(4)
        ->where('locale', app()->getLocale())
        ->first();
@endphp

<footer class="bg-gray-200 py-8 mt-10">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h4 class="font-semibold mb-4">{{ $firstMenu->header }}</h4>
                <ul>
                    @foreach($firstMenu->menu->menuElements as $menuElement)
                        @php
                            $trans = $menuElement->menuElementTranslations()
                                ->where('locale', app()->getLocale())
                                ->first();
                        @endphp

                        <li>
                            <a href="{{ $menuElement->link }}" class="text-gray-600 hover:text-gray-800">
                                {{ $trans->label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">{{ $secondMenu->header }}</h4>
                <ul>
                    @foreach($secondMenu->menu->menuElements as $menuElement)
                        @php
                            $trans = $menuElement->menuElementTranslations()
                                ->where('locale', app()->getLocale())
                                ->first();
                        @endphp

                        <li>
                            <a href="{{ $menuElement->link }}" class="text-gray-600 hover:text-gray-800">
                                {{ $trans->label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="font-semibold mb-4">{{ $thirdMenu->header }}</h4>
                <ul>
                    @foreach($thirdMenu->menu->menuElements as $menuElement)
                        @php
                            $trans = $menuElement->menuElementTranslations()
                                ->where('locale', app()->getLocale())
                                ->first();
                        @endphp

                        @if($menuElement->link === 'footer_categories_section')
                            <li><a href="{{ route('concert-music') }}" class="text-gray-600 hover:text-gray-800">Concert / Music</a></li>
                            <li><a href="{{ route('tours-and-adventure') }}" class="text-gray-600 hover:text-gray-800">Tours and Adventure</a></li>
                            <li><a href="{{ route('events', ['category' => 'Sport / Fitness']) }}" class="text-gray-600 hover:text-gray-800">Sport / Fitness</a></li>
                        @else
                            <li>
                                <a href="{{ $menuElement->link }}" class="text-gray-600 hover:text-gray-800">
                                    {{ $trans->label }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Contact Us</h4>
                <p class="text-gray-600">Phone: +977 985-1104277</p>

                <div class="w-8">
                    <a href="https://www.facebook.com/aafnoticketnp/" target="_blank"
                       class="text-blue-600 mt-2 hover:text-blue-800">
                        <x-fab-facebook class="h-8 w-8"/>
                    </a>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-300 mt-8 pt-4 text-center">
            <p class="text-gray-600">
                <a href="{{ route('terms-of-service') }}">Terms of service</a> |
                <a href="{{ route('privacy-policy') }}">Privacy policy</a>
            </p>
            <p class="text-gray-600">Copyright © 2024</p>
        </div>
    </div>
</footer>
