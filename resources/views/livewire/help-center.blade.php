<div class="bg-gray-100 mt-36 h-screen">
    <div class="bg-gradient-to-r from-gray-200 via-gray-400 to-gray-600 py-16">
        <div class="container mx-auto text-center">
            <h1 class="text-4xl font-bold text-white mb-4">How can we help?</h1>
            <div class="relative max-w-3xl mx-auto">
                <input type="text" placeholder="Search..."
                       class="w-full p-4 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <p class="mt-4 text-white">Organizers, attendees, this support center is intended to quickly reply to your
                questions. If you still don't find answers, please contact us, we will be happy to receive your
                inquiry.</p>
        </div>
    </div>

    <div class="container mx-auto mt-8 px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <div class="text-blue-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 11a4 4 0 110-8 4 4 0 010 8zm0 1a7 7 0 00-5.61 2.901A9.01 9.01 0 0110 18a9.01 9.01 0 015.61-3.099A7 7 0 0010 12z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold mb-2">Attendee</h2>
                <div class="text-left">
                    <p>test</p>
                </div>
                <div class="mt-4">
                    <button
                        class="text-blue-500 font-semibold border-2 border-blue-500 rounded-full px-4 py-2 transition duration-300 hover:bg-blue-500 hover:text-white">
                        SEE MORE ARTICLES
                    </button>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <div class="text-blue-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M14 10V7a2 2 0 00-2-2H8a2 2 0 00-2 2v3H4a2 2 0 00-2 2v2a2 2 0 002 2h3v2a2 2 0 002 2h4a2 2 0 002-2v-2h3a2 2 0 002-2v-2a2 2 0 00-2-2h-2zM6 7a1 1 0 011-1h4a1 1 0 011 1v3H6V7zM4 12v-2h12v2H4z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold mb-2">Organizer</h2>
                <div class="text-left">
                    <p>test</p>
                </div>
                <div class="mt-4">
                    <button
                        class="text-blue-500 font-semibold border-2 border-blue-500 rounded-full px-4 py-2 transition duration-300 hover:bg-blue-500 hover:text-white">
                        SEE MORE ARTICLES
                    </button>
                </div>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg text-center">
                <div class="text-blue-500 mb-4">
                    <svg class="w-16 h-16 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M5 9a2 2 0 100-4 2 2 0 000 4zm0 2a3 3 0 00-3 3v4h6v-4a3 3 0 00-3-3zm7-2a2 2 0 100-4 2 2 0 000 4zm0 2a3 3 0 00-3 3v4h6v-4a3 3 0 00-3-3zm5 0a2 2 0 100-4 2 2 0 000 4zm0 2a3 3 0 00-3 3v4h6v-4a3 3 0 00-3-3z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-semibold mb-2">test</h2>
                <div class="text-left">
                    <p>test</p>
                </div>
                <div class="mt-4">
                    <button
                        class="text-blue-500 font-semibold border-2 border-blue-500 rounded-full px-4 py-2 transition duration-300 hover:bg-blue-500 hover:text-white">
                        SEE MORE ARTICLES
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto mt-8 px-4">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center flex flex-col items-center">
            <div class="text-blue-500 mb-4">
                <svg class="w-16 h-16" fill="currentColor" viewBox="0 0 20 20"><path d="M13 7h-1V4a3 3 0 10-6 0v3H5a2 2 0 00-2 2v6a2 2 0 002 2h8a2 2 0 002-2v-6a2 2 0 00-2-2zm-2 0H9V4a1 1 0 112 0v3z"></path></svg>
            </div>
            <p class="text-gray-600">You did not find an answer to your inquiry? Let us know and we will be glad to give you further help</p>
            <a href="{{ route('contact-us') }}" class="mt-4 bg-gray-800 text-white font-semibold rounded-full px-6 py-2 transition duration-300 hover:bg-gray-900">CONTACT US</a>
        </div>
    </div>
</div>
