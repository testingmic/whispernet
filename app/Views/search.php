<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-20 pt-2">
    <div class="max-w-3xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Search Posts</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Find posts by tag, keyword, or advanced filters</p>
        </div>

        <!-- Search Form -->
        <form id="searchForm" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="tag" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tag</label>
                    <input type="text" id="tag" name="tag" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="#tagname">
                </div>
                <div>
                    <label for="keywords" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Keywords</label>
                    <input type="text" id="keywords" name="keywords" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Search words...">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                    <input type="date" id="date_from" name="date_from" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                    <input type="date" id="date_to" name="date_to" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
            </div>
            <div>
                <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Sort By</label>
                <select id="sort" name="sort" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    <option value="relevance">Relevance</option>
                    <option value="newest">Newest</option>
                    <option value="oldest">Oldest</option>
                    <option value="most_liked">Most Liked</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="btn-primary px-6 py-2">Search</button>
            </div>
        </form>

        <!-- Search Results -->
        <div id="searchResults" class="space-y-6">
            <!-- Sample Result 1 -->
            <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow p-5 flex flex-col gap-2">
                <div class="flex items-center mb-1">
                    <div class="avatar-gradient-blue flex items-center justify-center rounded-full w-10 h-10 font-bold text-base mr-3">JD</div>
                    <div>
                        <span class="font-semibold text-blue">John Doe</span>
                        <span class="block text-xs text-gray-400">Apr 20, 2024 • 10:15 AM</span>
                    </div>
                </div>
                <div class="text-gray-800 dark:text-gray-100 mb-1">
                    Just discovered an amazing new coffee shop in town! ☕️ #coffee #discovery
                </div>
                <div class="flex flex-wrap gap-2 mb-2">
                    <span class="px-2 py-1 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-medium">#coffee</span>
                    <span class="px-2 py-1 rounded bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-medium">#discovery</span>
                </div>
                <div class="flex space-x-4 mt-2">
                    <button class="btn-primary flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>Like</button>
                    <button class="btn-success flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>Comment</button>
                    <button class="btn-danger flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>Share</button>
                </div>
            </div>
            <!-- Sample Result 2 -->
            <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow p-5 flex flex-col gap-2">
                <div class="flex items-center mb-1">
                    <div class="avatar-gradient-green flex items-center justify-center rounded-full w-10 h-10 font-bold text-base mr-3">AS</div>
                    <div>
                        <span class="font-semibold text-green">Alice Smith</span>
                        <span class="block text-xs text-gray-400">Apr 19, 2024 • 8:45 PM</span>
                    </div>
                </div>
                <div class="text-gray-800 dark:text-gray-100 mb-1">
                    Excited for the upcoming marathon! Who else is joining? #marathon #fitness
                </div>
                <div class="flex flex-wrap gap-2 mb-2">
                    <span class="px-2 py-1 rounded bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-medium">#marathon</span>
                    <span class="px-2 py-1 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-medium">#fitness</span>
                </div>
                <div class="flex space-x-4 mt-2">
                    <button class="btn-primary flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>Like</button>
                    <button class="btn-success flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>Comment</button>
                    <button class="btn-danger flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>Share</button>
                </div>
            </div>
            <!-- Sample Result 3 -->
            <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow p-5 flex flex-col gap-2">
                <div class="flex items-center mb-1">
                    <div class="avatar-gradient-red flex items-center justify-center rounded-full w-10 h-10 font-bold text-base mr-3">MK</div>
                    <div>
                        <span class="font-semibold text-red">Mike K.</span>
                        <span class="block text-xs text-gray-400">Apr 18, 2024 • 2:30 PM</span>
                    </div>
                </div>
                <div class="text-gray-800 dark:text-gray-100 mb-1">
                    Anyone has tips for learning JavaScript fast? #javascript #webdev
                </div>
                <div class="flex flex-wrap gap-2 mb-2">
                    <span class="px-2 py-1 rounded bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 text-xs font-medium">#javascript</span>
                    <span class="px-2 py-1 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-medium">#webdev</span>
                </div>
                <div class="flex space-x-4 mt-2">
                    <button class="btn-primary flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/></svg>Like</button>
                    <button class="btn-success flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>Comment</button>
                    <button class="btn-danger flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>Share</button>
                </div>
            </div>
        </div>
    </div>
</div> 