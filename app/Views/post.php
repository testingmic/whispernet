<div class="min-h-[calc(100vh-100px)] bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Enhanced Header Section -->
    <div class="bg-gradient-to-r from-blue-500 via-purple-500 to-indigo-600 text-white shadow-lg">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Post Details</h1>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <a href="<?= $baseUrl ?>/dashboard" class="inline-flex items-center py-2 px-4 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-2xl hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105 shadow-xl border border-gray-200 dark:border-gray-700 backdrop-blur-sm">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        <span class="text-base">Go Back</span>
                        <div class="ml-2 w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-10">

        <div class="relative">
            <div id="postContainer" class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border-2 border-blue-200 dark:border-blue-700"
                data-posts-id="<?= $postId ?>"
                data-post-uuid="<?= $postUUID ?? '' ?>">
                <!-- Post Gradient Border -->
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 via-purple-500/5 to-indigo-500/5 pointer-events-none"></div>

                <!-- Post Header -->
                <div class="relative rounded-2xl bg-gradient-to-r from-blue-50 to-purple-50 dark:from-blue-900/20 dark:to-purple-900/20 p-4 border-b border-blue-200 dark:border-blue-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">Post Details</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">View and interact with this post</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Post Content Area -->
                <div class="p-6">
                    <?= loadingSkeleton(1, false); ?>
                </div>
            </div>
        </div>

        <!-- Enhanced Comments Section -->
        <div id="commentsSectionContainer" class="mt-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-8">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-gray-800">
                <h3 class="text-xl text-gray-900 dark:text-white flex items-center">
                    <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3 shadow-lg">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <span class="font-semibold">Replies</span>
                    <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-full" id="commentCount">(0)</span>
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Join the conversation and share your thoughts</p>
            </div>

            <!-- Enhanced Comments List -->
            <div class="p-6">
                <div class="space-y-4" id="commentsList"></div>
            </div>
        </div>

    </div>

    <!-- Enhanced Fixed Comment Input -->
    <div id="commentFormContainer" class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-2xl backdrop-blur-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form id="commentForm" class="flex items-center space-x-4">
                <div class="flex-1 relative">
                    <textarea
                        maxlength="300"
                        name=""
                        id="commentInput"
                        rows="2"
                        class="w-full px-4 py-4 border border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-base resize-none outline-none transition-all duration-200 min-h-[60px] max-h-32 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent hover:scrollbar-thumb-gray-400 dark:hover:scrollbar-thumb-gray-500"
                        placeholder="Share your thoughts on this post..."
                        style="scrollbar-width: thin; scrollbar-color: #d1d5db transparent;"></textarea>

                    <!-- Character Counter -->
                    <div class="absolute bottom-2 right-2 text-xs text-gray-400 dark:text-gray-500">
                        <span id="charCounter">0</span>/300
                    </div>

                </div>

                <button type="submit"
                    class="flex-shrink-0 px-6 py-4 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span class="text-sm font-medium">Reply</span>
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Enhanced Full View Modal -->
<?= full_view_modal() ?>
<div id="additionalHeight" class="h-24"></div>

<!-- Enhanced Loading States -->
<style>
    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }

        100% {
            background-position: calc(200px + 100%) 0;
        }
    }

    .loading-shimmer {
        background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
        background-size: 200px 100%;
        animation: shimmer 1.5s infinite;
    }

    .dark .loading-shimmer {
        background: linear-gradient(90deg, #374151 25%, #4b5563 50%, #374151 75%);
        background-size: 200px 100%;
    }
</style>