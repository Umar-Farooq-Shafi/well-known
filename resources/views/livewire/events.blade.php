<div class="container mx-auto p-6 mt-20">
    <div class="flex flex-col lg:flex-row justify-between mb-4">
        <!-- Sidebar -->
        <div class="w-full lg:w-1/4 mb-4 lg:mb-0 space-y-2">
            <input type="text" placeholder="Keyword" class="border p-2 w-full mb-2">
            <select class="border p-2 w-full mb-2">
                <option>Select a category</option>
                <!-- Add more categories here -->
            </select>
            <select class="border p-2 w-full mb-2">
                <option>Select a location</option>
                <!-- Add more locations here -->
            </select>
            <div class="mb-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox">
                    <span class="ml-2">Online events only</span>
                </label>
            </div>
            <div class="mb-2">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox">
                    <span class="ml-2">Local events only</span>
                </label>
            </div>
            <select class="border p-2 w-full mb-2">
                <option>Select a country</option>
                <!-- Add more countries here -->
            </select>
            <div class="mb-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="date" class="form-radio">
                    <span class="ml-2">Today</span>
                </label>
            </div>
            <div class="mb-2">
                <label class="inline-flex items-center">
                    <input type="radio" name="date" class="form-radio">
                    <span class="ml-2">Tomorrow</span>
                </label>
            </div>
        </div>
        <!-- Event Cards -->
        <div class="w-full lg:w-3/4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="border p-4 rounded-lg flex items-center space-x-4">
                <img src="your-image-url" alt="Bungy and Canyoning Day Trip" class="w-24 h-24 rounded-lg">
                <div>
                    <h3 class="text-lg font-semibold">Bungy and Canyoning Day Trip</h3>
                    <span class="bg-gray-200 text-sm px-2 py-1 rounded">Tours and Adventure</span>
                </div>
            </div>
            <div class="border p-4 rounded-lg flex items-center space-x-4">
                <img src="your-image-url" alt="Bungy and Canyon Swing Day Trip" class="w-24 h-24 rounded-lg">
                <div>
                    <h3 class="text-lg font-semibold">Bungy and Canyon Swing Day Trip</h3>
                    <span class="bg-gray-200 text-sm px-2 py-1 rounded">Tours and Adventure</span>
                </div>
            </div>
            <div class="border p-4 rounded-lg flex items-center space-x-4">
                <img src="your-image-url" alt="Go and See Day Trip" class="w-24 h-24 rounded-lg">
                <div>
                    <h3 class="text-lg font-semibold">Go and See Day Trip</h3>
                    <span class="bg-gray-200 text-sm px-2 py-1 rounded">Tours and Adventure</span>
                </div>
            </div>
            <div class="border p-4 rounded-lg flex items-center space-x-4">
                <img src="your-image-url" alt="Bungy Only" class="w-24 h-24 rounded-lg">
                <div>
                    <h3 class="text-lg font-semibold">Bungy Only</h3>
                    <span class="bg-gray-200 text-sm px-2 py-1 rounded">Tours and Adventure</span>
                </div>
            </div>
        </div>
    </div>
</div>
