<div class="max-w-2xl mx-auto">
    <!-- Location Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm top-0 z-10 border-b border-blue-400 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm text-gray-900 dark:text-gray-900 location-display">Loading location...</span>
                </div>
                <!-- <button id="changeLocationBtn" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-900">
                    Change Location
                </button> -->
            </div>
            <p class="mt-1 text-xs text-gray-900 dark:text-gray-900">
                Posts are displayed within a 35km radius of your location
            </p>
        </div>
    </div>

    <!-- Location/Radius Modal -->
    <div id="locationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg w-full max-w-md mx-4 p-6 relative">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Select Location & Radius</h2>
            <form id="locationFormd" class="space-y-4">
                <div>
                    <label for="locationSelecta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                    <select id="locationSelecta" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                        <option value="current">Current Location</option>
                        <option value="city_centre">City Centre</option>
                        <option value="university">University Area</option>
                        <option value="mall">Mall District</option>
                    </select>
                </div>
                <div>
                    <label for="radiusInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Radius (km)</label>
                    <input type="range" id="radiusInput" min="1" max="50" value="10" class="w-full">
                    <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-1">
                        <span>1km</span><span id="radiusValue">10km</span><span>50km</span>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" id="cancelLocationBtn" class="px-4 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600">Cancel</button>
                    <button type="submit" class="px-4 py-2 rounded-md bg-blue-600 text-white hover:bg-blue-700">Save</button>
                </div>
            </form>
            <button id="closeLocationModal" class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-2 mb-2">
        <div id="unreadPostsCountContainer" class="text-center hidden bg-gray-600 font-bold dark:bg-gray-800 border border-gray-200 dark:border-gray-700
            shadow-sm text-gray-700 dark:text-gray-200 rounded-md p-2 mb-2 w-full mx-auto cursor-pointer
            bg-gradient-to-br from-red-200 via-green-200 to-blue-200 shadow-lg" onclick="return PostManager.showUnreadPosts()">
            <span id="unreadPostsCount" class="text-sm">0</span> new posts
        </div>
        <div id="feedContainer" class="scroll-sentinel">
            <?= loadingSkeleton(1, false); ?>
        </div>
        <div id="oldPostsContainer" class="text-center mb-5 bg-gray-600 font-bold bg-blue-600 dark:bg-gray-800 border border-gray-200 dark:border-gray-700
            shadow-sm text-white rounded-md p-2 mb-2 w-full hover:bg-blue-500 mx-auto cursor-pointer" onclick="return PostManager.showOldPosts()">
            Show More Posts
        </div>
        <div id="noPostsContainer" class="hidden text-center mb-5 bg-gray-300 font-bold dark:bg-gray-800 border border-gray-200 dark:border-gray-700
            shadow-sm text-white rounded-md p-2 mb-2 w-full mx-auto">
            No posts found
        </div>
    </div>

    <div class="px-4 py-8 sm:p-6">&nbsp;</div>

    <!-- Floating Action Button -->
    <button onclick="return PostManager.openCreateModal()" class="fixed bottom-20 right-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full p-4 shadow-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all transform hover:scale-105">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </button>
</div>