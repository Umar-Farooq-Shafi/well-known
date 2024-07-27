@push('scripts')
    <script async src="https://www.google.com/recaptcha/api.js"></script>

    <script>
        function handle(e) {
            grecaptcha.ready(function () {
                grecaptcha.execute('{{config('custom.recaptcha.recaptcha_site_key')}}', {action: 'submit'})
                    .then(function (token) {
                        @this.set('captcha', token);
                    });
            })
        }
    </script>
@endpush

<div class="mt-24">
    <div class="flex flex-col gap-y-1 md:flex-row justify-between bg-gray-300 px-4 py-2 md:rounded md:mx-16 lg:mx-32 my-4">
        <div class="font-bold text-xl">Contact Us</div>

        <x-breadcrumbs/>
    </div>

    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex flex-col gap-y-7 items-center">
                <h1 class="text-3xl font-bold">FAQ</h1>

                <p class="text-blue-400">test</p>

                <a href="{{ route('help-center') }}" class="text-blue-400 gap-x-2 items-center flex text-xl">
                    <x-fas-life-ring class="w-5 h-5"/>

                    Help Center
                </a>
            </div>

            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

            <div class="flex justify-center text-3xl">
                <h1>Contact information</h1>
            </div>

            <div class="flex flex-col lg:flex-row gap-16 p-8 px-20">
                <div class="flex items-center gap-x-2">
                    <div class="rounded-full bg-blue-400 p-4">
                        <x-fas-envelope-open-text class="w-8 h-8 text-white"/>
                    </div>

                    <p class="text-xl">info@aafnoticket.com</p>
                </div>

                <div class="flex items-center gap-x-2">
                    <div class="rounded-full bg-blue-400 p-4">
                        <x-fas-phone class="w-6 h-6 text-white"/>
                    </div>

                    <p class="text-xl">+977 985-1104277</p>
                </div>

                <div class="flex items-center gap-x-2">
                    <div class="rounded-full bg-blue-400 p-4">
                        <x-fas-map-marker class="w-6 h-6 text-white"/>
                    </div>

                    <p class="text-xl">+Dudhpati- 1, Bhaktapur 44800, Nepal</p>
                </div>
            </div>

            <hr class="h-px my-8 bg-gray-200 border-0 dark:bg-gray-700">

            <div class="flex flex-col items-center">
                <form class="w-2/3 lg:w-1/2 flex flex-col items-center gap-y-4">
                    <h1 class="text-3xl">Send us an email</h1>

                    <x-input
                        label="Name"
                        required
                        wire:model="name"
                        placeholder="Input name"
                    />

                    <x-input
                        label="Email"
                        required
                        wire:model="email"
                        type="email"
                        placeholder="Input Email"
                    />

                    <x-input
                        label="Subject"
                        required
                        wire:model="subject"
                        placeholder="Input Subject"
                    />

                    <x-textarea
                        label="Message"
                        rows="10"
                        wire:model="message"
                        placeholder="Input Message"
                    />

                    <div class="g-recaptcha mt-4" data-sitekey={{config('custom.recaptcha.recaptcha_site_key')}}></div>

                    <button
                        class="outline-none w-full inline-flex justify-center items-center group hover:shadow-sm focus:ring-offset-background-white dark:focus:ring-offset-background-dark transition-all ease-in-out duration-200 focus:ring-2 disabled:opacity-80 disabled:cursor-not-allowed text-white bg-blue-500 dark:bg-primary-700 hover:text-white hover:bg-primary-600 dark:hover:bg-primary-600 focus:text-white focus:ring-offset-2 focus:bg-primary-600 focus:ring-primary-600 dark:focus:bg-primary-600 dark:focus:ring-primary-600 rounded-md gap-x-2 text-sm px-4 py-2"
                        data-sitekey="{{config('custom.recaptcha.recaptcha_site_key')}}"
                        data-callback='handle'
                        data-action='submit'
                        type="submit">
                        Send
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
