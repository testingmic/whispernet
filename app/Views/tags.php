<script>
    const postsList = <?= json_encode($postsList) ?>;
</script>
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 mt-0 pt-5 pb-16">
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 mb-6">
            <div class="text-center">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <p class="text-lg text-gray-600 dark:text-gray-400 mb-6">Discover, search, and manage your preferred tags</p>

                <!-- Search Bar -->
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="tagSearchInput" placeholder="Search for tags..." value="<?= htmlspecialchars($searchQuery) ?>" class="w-full pl-12 pr-4 py-4 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200">
                        <button id="searchTagsBtn" class="absolute inset-y-0 right-0 px-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-r-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-[60%_37%] gap-8">

            <!-- Tag Posts Section -->
            <div id="tagPostsSection" class="bg-white width-[70%] dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <button id="backToTags" class="p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                                <svg class="w-6 h-6 mr-3 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Tag: <span id="selectedTagDisplay" class="text-purple-600 dark:text-purple-400"> #<?= htmlspecialchars($searchQuery) ?></span>
                            </h2>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400" id="postsCount"><?= $postsCount ?? 0 ?> posts</span>
                    </div>
                </div>

                <div class="p-2">
                    <div id="tagPostsList" class="space-y-6 p-2 max-h-[50vh] overflow-y-auto">
                        <?php if (empty($searchQuery) || empty($postsCount)) { ?>
                            <div class="text-center py-12">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/20 dark:to-green-800/20 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">
                                    <?= empty($searchQuery) ? 'No Tags Selected' : 'No Posts Found' ?>
                                </h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Start by searching and selecting your preferred tags</p>
                                <button onclick="document.getElementById('tagSearchInput').focus()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                    Search Tags
                                </button>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <!-- Popular Tags Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                            <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                            Popular Tags
                        </h2>
                    </div>
                </div>

                <div class="p-2">
                    <?php if (empty($popularTags)): ?>
                        <div class="text-center py-12">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/20 dark:to-blue-800/20 flex items-center justify-center">
                                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Popular Tags</h3>
                            <p class="text-gray-600 dark:text-gray-400">Popular tags will appear here based on usage</p>
                        </div>
                    <?php else: ?>
                        <!-- Popular Tags List -->
                        <div class="space-y-3 max-h-[50vh] p-2 overflow-y-auto">
                            <?php foreach ($popularTags as $tag): ?>
                                <div class="flex items-center justify-between p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-800/30 dark:hover:to-blue-700/30 transition-all duration-200">
                                    <div class="flex items-center space-x-3 flex-1 cursor-pointer" onclick="return TagsManager.showTagPosts('<?= htmlspecialchars($tag['name']) ?>', <?= $tag['tag_id'] ?>)">
                                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-white">#<?= htmlspecialchars($tag['name']) ?></h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400"><?= number_format($tag['usage_count'] ?? 0) ?> posts</p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </div>
                                    <button type="button" class="add-tag-btn hidden p-2 text-gray-400 hover:text-green-500 dark:hover:text-green-400 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200 ml-2" title="Add tag" data-tag-id="<?= $tag['tag_id'] ?>" data-tag-name="<?= htmlspecialchars($tag['name']) ?>">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </div>
</div>
<div class="h-20"></div>
<script>
const TagsManager = {
    searchInput: null,
    searchBtn: null,
    postsList: null,
    searchQuery: '<?= $searchQuery ?>',
    init() {
        // Search functionality
        this.searchInput = document.getElementById('tagSearchInput');
        this.searchBtn = document.getElementById('searchTagsBtn');

        this.searchBtn.addEventListener('click', () => this.performSearch());
        this.searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.performSearch();
            }
        });

        // Add tag functionality
        document.querySelectorAll('.add-tag-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tagId = this.dataset.tagId;
                const tagName = this.dataset.tagName;

                // Add tag to user's selected tags
                fetch('<?= $baseUrl ?>/api/tags/add-user-tag', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            tag_id: tagId,
                            userUUID,
                            tag_name: tagName,
                            token: AppState.getToken()
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Show success notification
                            AppState.showNotification('Tag added successfully!', 'success');
                        } else {
                            AppState.showNotification(data.message || 'Failed to add tag', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        AppState.showNotification('An error occurred', 'error');
                    });
            });
        });

        // Remove tag functionality
        document.querySelectorAll('.remove-tag-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tagId = this.dataset.tagId;

                // Remove tag from user's selected tags
                fetch('<?= $baseUrl ?>/api/tags/remove-user-tag', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            tag_id: tagId,
                            userUUID,
                            token: AppState.getToken()
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Show success notification
                            AppState.showNotification('Tag removed successfully!', 'success');
                        } else {
                            AppState.showNotification(data.message || 'Failed to remove tag', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        AppState.showNotification('An error occurred', 'error');
                    });
            });
        });

        this.loadPostsList();

    },
    performSearch() {
        const query = this.searchInput.value.trim();
        if (query) {
            window.location.href = `<?= $baseUrl ?>/posts/tags/${encodeURIComponent(query)}`;
        }
    },

    showTagPosts(tagName, tagId) {
        // Update the tag display
        document.getElementById('selectedTagDisplay').textContent = ` #${tagName}`;

        // Show the posts section
        const postsSection = document.getElementById('tagPostsSection');
        postsSection.classList.remove('hidden');

        // Scroll to the posts section
        postsSection.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });

        $.get(`<?= $baseUrl ?>/api/tags/postsbyid/${tagId}`, {
            tag_id: tagId,
            userUUID,
            token: AppState.getToken()
        }).then(response => {
            if(response.status === 'success') {
                this.renderTagPosts(response.data, tagName);
            } else {
                AppState.showNotification(response.message || 'Failed to load posts', 'error');
            }
        });
    },

    hideTagPosts() {
        const postsSection = document.getElementById('tagPostsSection');
        postsSection.classList.add('hidden');
    },

    loadPostsList() {
        if(postsList.length > 0) {
            this.renderTagPosts(postsList, this.searchQuery);
        }
    },

    // Function to fetch posts for a specific tag
    renderTagPosts(postsList, tagName) {
        // Show loading state
        const postsContainer = document.getElementById('tagPostsList');
        postsContainer.innerHTML = `
        <div class="flex items-center justify-center py-12">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-500"></div>
            <span class="ml-3 text-gray-600 dark:text-gray-400">Loading posts for #${tagName}...</span>
        </div>`;

        setTimeout(() => {
            // Update posts count
            document.getElementById('postsCount').textContent = '5 posts';
            let postsData = ``;

            $.each(postsList, function(index, post) {
                let hashtags = post.hashtags;
                let hashtagsData = ``;
                $.each(hashtags, function(index, hashtag) {
                    hashtagsData += `<span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                        #${hashtag}
                    </span>`;
                });
                postsData += `
                <div class="bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 hover:from-purple-100 hover:to-purple-200 dark:hover:from-purple-800/30 dark:hover:to-purple-700/30 transition-all duration-200">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-full flex items-center justify-center shadow-lg">
                                <span class="text-lg font-bold text-white">
                                    ${post.username.charAt(0).toUpperCase()}${post.username.charAt(1).toUpperCase()}
                                </span>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-gray-600 dark:text-gray-400 mb-3">
                                ${post.content}
                            </p>
                            <div class="flex flex-wrap gap-2 mb-3">
                                ${hashtagsData}
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ${post.ago}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-4">
                                    <span class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                        </svg>
                                        ${post.upvotes}
                                    </span>
                                    <span class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        ${post.comments_count}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink-0 flex flex-col space-y-2">
                            <button onclick="return PostManager.changeDirection(${post.post_id})" class="p-2 text-gray-400 hover:text-blue-500 dark:hover:text-blue-400 rounded-lg hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-all duration-200" title="View post">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                            </button>
                            <button onclick="return PostManager.handleVote('posts', ${post.post_id}, 'up', ${post.user_id})" class="p-2 text-gray-400 hover:text-green-500 dark:hover:text-green-400 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-all duration-200" title="Like post">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                            </button>
                            <button class="p-2 hidden text-gray-400 hover:text-purple-500 dark:hover:text-purple-400 rounded-lg hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-all duration-200" title="Share post">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>`;
            });

            postsContainer.innerHTML = postsData;
        }, 1000);
    },

}
document.addEventListener('DOMContentLoaded', () => {
    TagsManager.init();
});
</script>