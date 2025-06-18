<!-- Location Header -->
<div class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-10">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="text-sm text-gray-600 dark:text-gray-300 location-display">Loading location...</span>
            </div>
            <button id="changeLocationBtn" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                Change Location
            </button>
        </div>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Posts are displayed within a 10km radius of your location
        </p>
    </div>
</div>

<!-- Create Post Card -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <!-- Post Creation Header -->
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium">
                    AN
                </div>
                <div class="flex-1">
                    <div type="button" id="createPostButton" 
                        class="w-full text-left px-4 py-2.5 bg-gray-100 dark:bg-gray-700 rounded-full text-gray-500 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-200">
                        What's on your mind?
                    </div>
                    <!-- Post Creation Form (Hidden by default) -->
                    <div id="postCreationForm" class="hidden">
                        <form id="createPostForm" class="space-y-4 p-4">
                            <!-- Text Input -->
                            <div class="relative">
                                <textarea id="postContent" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none text-base"
                                    placeholder="Share your thoughts..."></textarea>

                                <!-- Rich Text Toolbar -->
                                <div class="absolute bottom-3 left-3 flex space-x-2 bg-white dark:bg-gray-800 p-1 rounded-lg shadow-sm">
                                    <button type="button" class="p-1.5 text-gray-500 hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Bold">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h4a2 2 0 100-4H6v4zm0 0h4a2 2 0 110 4H6v-4z" />
                                        </svg>
                                    </button>
                                    <button type="button" class="p-1.5 text-gray-500 hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Italic">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                    </button>
                                    <button type="button" class="p-1.5 text-gray-500 hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-400 rounded hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Add Link">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Media Preview -->
                            <div id="mediaPreview" class="hidden">
                                <div class="relative rounded-xl overflow-hidden">
                                    <img id="previewImage" class="max-h-96 w-full object-cover" src="" alt="Preview">
                                    <button type="button" id="removeMedia"
                                        class="absolute top-3 right-3 bg-gray-900 bg-opacity-50 rounded-full p-2 text-white hover:bg-opacity-75 transition-opacity">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Tags Input -->
                            <div class="flex hidden flex-wrap gap-2 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl" id="tagsContainer">
                                <input type="text" id="tagInput"
                                    class="flex-1 min-w-[120px] px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-full text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                                    placeholder="Add tags...">
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex flex-wrap gap-2">
                                    <!-- Photo/Video Upload -->
                                    <label class="inline-flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                        <input type="file" id="mediaUpload" class="hidden" accept="image/*,video/*">
                                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Photo/Video</span>
                                    </label>

                                </div>

                                <!-- Post Button -->
                                <button type="submit"
                                    class="ml-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium">
                                    Post
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>


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

<div id="feedContainer">
    <!-- Loading Skeleton Card -->
    <?= loadingSkeleton(1); ?>
</div>

<!-- Floating Action Button -->
<button class="fixed bottom-20 right-4 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full p-4 shadow-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all transform hover:scale-105">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
    </svg>
</button>