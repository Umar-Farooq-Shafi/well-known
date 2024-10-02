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

<footer style="background: linear-gradient(35deg, #535eb2, #009cde);" class="py-10 mt-10 text-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-10">
            <div>
                <h4 class="text-lg font-semibold mb-4">{{ $firstMenu->header }}</h4>
                <ul class="text-base">
                    @foreach($firstMenu->menu->menuElements as $menuElement)
                        @php
                            $trans = $menuElement->menuElementTranslations()
                                ->where('locale', app()->getLocale())
                                ->first();
                        @endphp

                        <li class="@if($loop->last) mb-8 @endif">
                            <a href="{{ $menuElement->link }}" style="color: #d7e8ff;"> <!-- Gray color -->
                                {{ $trans->label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-4">{{ $secondMenu->header }}</h4>
                <ul class="text-base">
                    @foreach($secondMenu->menu->menuElements as $menuElement)
                        @php
                            $trans = $menuElement->menuElementTranslations()
                                ->where('locale', app()->getLocale())
                                ->first();
                        @endphp

                        <li class="@if($loop->last) mb-8 @endif">
                            <a href="{{ $menuElement->link }}" style="color: #d7e8ff;"> <!-- Gray color -->
                                {{ $trans->label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-4">{{ $thirdMenu->header }}</h4>
                <ul class="text-base">
                    @foreach($thirdMenu->menu->menuElements as $menuElement)
                        @php
                            $trans = $menuElement->menuElementTranslations()
                                ->where('locale', app()->getLocale())
                                ->first();
                        @endphp

                        <li class="@if($loop->last) mb-8 @endif">
                            <a href="{{ $menuElement->link }}" style="color: #d7e8ff;"> <!-- Gray color -->
                                {{ $trans->label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div>
                <h4 class="text-lg font-semibold mb-4">Contact Us</h4>
                <p class="text-base">Phone: <span style="color: #d7e8ff;">+977 985-1104277</span></p>
                <p class="text-base">Email: <a href="mailto:info@aafnoticket.com" style="color: #d7e8ff;">info@aafnoticket.com</a></p>

                <div class="flex space-x-4 mt-4">
                    <a href="https://www.facebook.com/aafnoticketnp/" target="_blank" class="text-white">
                        <x-fab-facebook class="h-8 w-8"/>
                    </a>
                    <a href="https://www.instagram.com/yourprofile" target="_blank" class="text-white">
                        <x-fab-instagram class="h-8 w-8"/>
                    </a>
                </div>
            </div>
        </div>

        <div class="text-center mt-8">
            <a href="https://play.google.com/store/apps/details?id=com.aafnoticket" target="_blank" class="inline-block mx-2">
                <img src="https://aafnoticket.co.nz/assets/images/googl-play.svg" alt="Download on Google Play" class="h-10">
            </a>
            <a href="https://apps.apple.com/app/id123456789" target="_blank" class="inline-block mx-2">
                <img src="https://aafnoticket.co.nz/assets/images/ios-appstore.svg" alt="Download on the App Store" class="h-10">
            </a>
        </div>

        <div class="border-t border-gray-300 mt-8 pt-4 text-center">
            <p class="text-base">
                <a href="{{ route('terms-of-service') }}" style="color: #d7e8ff;">Terms of Service</a> |
                <a href="{{ route('privacy-policy') }}" style="color: #d7e8ff;">Privacy Policy</a>
            </p>
            <p class="text-base" style="color: #d7e8ff;">Copyright © 2024</p>
        </div>
    </div>
</footer>
