<!-- Post View Container -->
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Post Section -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4">

        <div class="text-xl font-medium text-gray-900 dark:text-white mb-3">
            <a class="flex items-center space-x-2 text-base" href="<?= $baseUrl ?>/dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span class="bg-gradient-to-r from-red-500 via-green-500 to-blue-500 bg-clip-text text-transparent">Back</span>
            </a>
        </div>

        <div id="postContainer" class="singlePostContainer shadow-lg rounded-lg bg-gradient-to-br from-red-200 via-green-200 to-blue-200 p-4 rounded-xl shadow-md" data-posts-id="<?= $postId ?>">
            <?= loadingSkeleton(1, false); ?>
        </div>

        <!-- Comments Section -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Comments</h3>

            <!-- Comments List -->
            <div class="space-y-4" id="commentsList">
                <p class="text-gray-500 dark:text-gray-400" id="commentsLoading">Loading comments...</p>
            </div>
        </div>
    </div>
    <div class="px-4 py-8 sm:p-6">&nbsp;</div>

    <!-- Fixed Comment Input -->
    <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg bg-gradient-to-br from-red-200 via-green-200 to-blue-200">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form id="commentForm" class="flex items-center space-x-4">
                <div class="flex-1 relative">
                    <textarea name="" id="commentInput" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-base" placeholder="Write a comment..."></textarea>
                    <button type="button" class="absolute hidden right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-full hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium">
                    Post
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Add padding to account for fixed comment input and footer -->
<div class="h-8"></div>