<?php
$savedPosts = $bookmarkedPosts ?? [];
$user = $user ?? null;
?>

<div class="min-h-[calc(100vh-4rem)] bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Header Section -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Back Button and Title -->
                <div class="flex items-center space-x-4">
                    <a href="<?= base_url() ?>" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <!-- <h1 class="text-xl font-bold text-gray-900 dark:text-white">Saved Posts</h1> -->
                    </div>
                </div>

                <!-- Search and Filter -->
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <input type="text" id="searchSavedPosts" placeholder="Search saved posts..."
                            class="w-64 px-4 py-2 pl-10 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white text-sm">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <button id="filterBtn" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Saved Posts Grid -->
    <div id="savedPostsContainer">
        <?php if (empty($savedPosts)): ?>
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Saved Posts Yet</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Start saving posts you want to revisit later</p>
                <a href="<?= base_url() ?>" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Explore Posts
                </a>
            </div>
        <?php else: ?>
            <!-- Posts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3  p-4 gap-6" id="savedPostsGrid">
                <?php foreach ($savedPosts as $post): ?>
                    <div class="saved-post-card bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-300" data-post-id="<?= $post['post_id'] ?? '' ?>">
                        <!-- Post Header -->
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3" onclick="return PostManager.changeDirection(<?= $post['post_id'] ?>)">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white">
                                        <span class="text-sm font-semibold"><?= strtoupper(substr($post['username'] ?? 'U', 0, 2)) ?></span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($post['username'] ?? 'Unknown') ?></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 flex items-center space-x-1">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <span class="mr-2"><?= $post['city'] ?? 'Unknown location' ?></span>
                                            
                                            <svg xmlns="http://www.w3.org/2000/svg" 
                                                width="16" height="16" viewBox="0 0 24 24"  fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span class="ml-2"><?= $post['ago'] ?? 'Unknown time' ?></span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Post Content -->
                            <div class="mb-4" onclick="return PostManager.changeDirection(<?= $post['post_id'] ?>)">
                                <p class="text-gray-800 dark:text-gray-200 text-sm leading-relaxed line-clamp-3"><?= $post['content'] ?? '' ?></p>
                            </div>

                            <!-- Media Indicators -->
                            <?php if (!empty($post['media_types'])): ?>
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <?php if (in_array('images', $post['media_types'])): ?>
                                        <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-full text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z" />
                                            </svg>
                                            Images
                                        </span>
                                    <?php endif; ?>
                                    <?php if (in_array('video', $post['media_types'])): ?>
                                        <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-full text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z" />
                                            </svg>
                                            Video
                                        </span>
                                    <?php endif; ?>
                                    <?php if (in_array('audio', $post['media_types'])): ?>
                                        <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 rounded-full text-xs">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                                            </svg>
                                            Audio
                                        </span>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Post Footer -->
                        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50" onclick="return PostManager.changeDirection(<?= $post['post_id'] ?>)">
                            <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center space-x-1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                        </svg>
                                        <span><?= $post['comments_count'] ?? 0 ?></span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                        </svg>
                                        <span><?= $post['upvotes'] ?? 0 ?></span>
                                    </span>
                                    <span class="flex items-center space-x-1">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        <span><?= $post['views'] ?? 0 ?> views</span>
                                    </span>
                                </div>
                                <span class="text-xs">Saved <?= $post['saved_ago'] ?? 'recently' ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Load More Button -->
    <?php if (count($savedPosts) >= 12): ?>
        <div class="text-center mt-8">
            <button id="loadMoreBtn" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                Load More Posts
            </button>
        </div>
    <?php endif; ?>
    <div class="h-20"></div>
    <div class="h-20"></div>
</div>
<script>
    const postsList = <?= json_encode($savedPosts) ?>;
    let foundPosts = [];
    $(`input[id="searchSavedPosts"]`).on('keyup', function() {
        const searchValue = $(this).val();
        const filteredPosts = postsList.filter(
            post => post.content.toLowerCase().includes(searchValue.toLowerCase()) || 
            post.username.toLowerCase().includes(searchValue.toLowerCase()) ||
            post.city.toLowerCase().includes(searchValue.toLowerCase())
        );
        // set all saved-post-card to hidden except the filtered posts post_id
        $('.saved-post-card').each(function() {
            if(filteredPosts.some(post => post.post_id == $(this).data('post-id'))) {
                $(this).removeClass('hidden');
            } else {
                $(this).addClass('hidden');
            }
        });
    });
</script>