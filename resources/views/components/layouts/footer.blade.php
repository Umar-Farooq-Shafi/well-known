<footer class="bg-gray-200 py-8 mt-10">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8">
            <div>
                <h4 class="font-semibold mb-4">Useful Links</h4>
                <ul>
                    <li><a href="{{ route('about-us') }}" class="text-gray-600 hover:text-gray-800">About us</a></li>
                    <li><a href="{{ route('help-center') }}" class="text-gray-600 hover:text-gray-800">Help center</a></li>
                    <li><a href="{{ route('blog') }}" class="text-gray-600 hover:text-gray-800">Blog</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-gray-800">Venues</a></li>
                    <li><a href="{{ route('contact-us') }}" class="text-gray-600 hover:text-gray-800">Send us an email</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">My Account</h4>
                <ul>
                    <li><a href="{{ route('filament.admin.auth.register') }}" class="text-gray-600 hover:text-gray-800">Create an account</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-gray-800">Sell tickets online</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-gray-800">My tickets</a></li>
                    <li><a href="{{ route('filament.admin.auth.password-reset.request') }}" class="text-gray-600 hover:text-gray-800">Forgot your password?</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-gray-800">Payment, delivery and return</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Event Categories</h4>
                <ul>
                    <li><a href="#" class="text-gray-600 hover:text-gray-800">Party</a></li>
                    <li><a href="{{ route('concert-music') }}" class="text-gray-600 hover:text-gray-800">Concert / Music</a></li>
                    <li><a href="{{ route('tours-and-adventure') }}" class="text-gray-600 hover:text-gray-800">Tours and Adventure</a></li>
                    <li><a href="#" class="text-gray-600 hover:text-gray-800">Sport / Fitness</a></li>
                    <li><a href="{{ route('all-categories') }}" class="text-gray-600 hover:text-gray-800">All categories</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Contact Us</h4>
                <p class="text-gray-600">Phone: +977 985-1104277</p>
                <a href="https://www.facebook.com/aafnoticketnp/" target="_blank" class="text-blue-600 hover:text-blue-800">
                    <x-fab-facebook class="h-8 w-8" />
                </a>
            </div>
        </div>
        <div class="border-t border-gray-300 mt-8 pt-4 text-center">
            <p class="text-gray-600">Terms of service | Privacy policy</p>
            <p class="text-gray-600">Copyright © 2024</p>
        </div>
    </div>
</footer>
