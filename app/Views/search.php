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
        <div id="searchResults" class="space-y-4">
            <?php if (isset($results)): ?>
                <?php if (empty($results)): ?>
                    <div class="p-6 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-lg shadow">
                        No results found for your search.
                    </div>
                <?php else: ?>
                    <?php foreach ($results as $post): ?>
                        <div class="card p-4">
                            <div class="flex items-center mb-2">
                                <div class="avatar-gradient-blue flex items-center justify-center rounded-full w-10 h-10 font-bold text-base mr-3">
                                    <?= strtoupper(substr($post['username'] ?? 'AN', 0, 2)) ?>
                                </div>
                                <div>
                                    <span class="font-semibold text-blue"><?= htmlspecialchars($post['username'] ?? 'Anonymous User') ?></span>
                                    <span class="block text-xs text-gray-400"><?= date('M d, Y • h:i A', strtotime($post['created_at'] ?? time())) ?></span>
                                </div>
                            </div>
                            <div class="text-gray-800 dark:text-gray-100 mb-2">
                                <?= htmlspecialchars($post['content']) ?>
                            </div>
                            <div class="flex flex-wrap gap-2 mb-2">
                                <?php foreach ($post['tags'] ?? [] as $tag): ?>
                                    <span class="px-2 py-1 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-medium">#<?= htmlspecialchars($tag) ?></span>
                                <?php endforeach; ?>
                            </div>
                            <div class="flex space-x-4 mt-2">
                                <button class="btn-primary flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" ...></svg>Like</button>
                                <button class="btn-success flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" ...></svg>Comment</button>
                                <button class="btn-danger flex items-center px-3 py-1.5"><svg class="w-4 h-4 mr-1" ...></svg>Share</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div> 