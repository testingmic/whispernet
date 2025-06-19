<!-- Post View Container -->
<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-10">
        <!-- Back Navigation -->
        <div class="mb-4">
            <a href="<?= $baseUrl ?>/dashboard" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105 shadow-lg border border-gray-200 dark:border-gray-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Dashboard
            </a>
        </div>

        <!-- Post Container -->
        <div id="postContainer" class="singlePostContainer shadow-lg rounded-lg bg-gradient-to-br from-red-200 via-green-200 to-blue-200 p-2 rounded-xl shadow-md" data-posts-id="<?= $postId ?>">
            <?= loadingSkeleton(1, false); ?>
        </div>

        <!-- Media Preview Section -->
        <div id="postMediaPreview" class="media-display-container mb-3">
            <!-- Media content will be dynamically inserted here -->
        </div>

        <!-- Comments Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Comments & Discussion
                </h3>
            </div>

            <!-- Comments List -->
            <div class="p-3">
                <div class="space-y-4" id="commentsList">
                    <div class="flex items-center justify-center py-4">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400" id="commentsLoading">Loading comments...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Fixed Comment Input -->
    <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-2xl backdrop-blur-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4 align-middle justify-center items-center">
            <form id="commentForm" class="flex items-end space-x-4">
                <div class="flex-1 relative">
                    <textarea 
                        maxlength="300" 
                        name="" 
                        id="commentInput" 
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-base resize-none outline-none transition-all duration-200" 
                        placeholder="Share your thoughts on this post..."
                    ></textarea>
                    <div class="absolute bottom-2 right-2 text-xs text-gray-400">
                        <span id="charCount">0</span>/300
                    </div>
                    <button type="button" class="absolute hidden right-12 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
                <button type="submit"
                    class="px-2 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Post
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Enhanced Full View Modal -->
<div id="fullViewModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black bg-opacity-90 backdrop-blur-sm"></div>
    
    <div class="relative h-full flex items-center justify-center p-4">
        <!-- Close Button -->
        <button id="closeModal" class="absolute top-6 right-6 z-10 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-all duration-200 backdrop-blur-sm border border-white/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Navigation Buttons -->
        <button id="prevBtn" class="absolute left-6 top-1/2 transform -translate-y-1/2 z-10 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-all duration-200 backdrop-blur-sm border border-white/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button id="nextBtn" class="absolute right-6 top-1/2 transform -translate-y-1/2 z-10 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-all duration-200 backdrop-blur-sm border border-white/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- Content Container -->
        <div id="modalContent" class="max-w-full max-h-full flex items-center justify-center">
            <!-- Content will be dynamically inserted here -->
        </div>

        <!-- Enhanced Loading Spinner -->
        <div id="loadingSpinner" class="absolute inset-0 flex items-center justify-center">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-8 h-8 bg-white/10 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Media Counter -->
        <div id="mediaCounter" class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-10 bg-black/50 text-white px-4 py-2 rounded-full backdrop-blur-sm text-sm font-medium">
            <span id="currentIndex">1</span> of <span id="totalCount">1</span>
        </div>
    </div>
</div>