<!-- Post View Container -->
<style>
  /* Custom scrollbar styles for textarea */
  #commentInput::-webkit-scrollbar {
    width: 6px;
  }
  
  #commentInput::-webkit-scrollbar-track {
    background: transparent;
    border-radius: 3px;
  }
  
  #commentInput::-webkit-scrollbar-thumb {
    background: #d1d5db;
    border-radius: 3px;
    transition: background-color 0.2s ease;
  }
  
  #commentInput::-webkit-scrollbar-thumb:hover {
    background: #9ca3af;
  }
  
  /* Dark mode scrollbar */
  .dark #commentInput::-webkit-scrollbar-thumb {
    background: #4b5563;
  }
  
  .dark #commentInput::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
  }
  
  /* Firefox scrollbar */
  #commentInput {
    scrollbar-width: thin;
    scrollbar-color: #d1d5db transparent;
  }
  
  .dark #commentInput {
    scrollbar-color: #4b5563 transparent;
  }
  
  /* Smooth scrolling */
  #commentInput {
    scroll-behavior: smooth;
  }
</style>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pt-4 pb-10">
        <!-- Back Navigation -->
        <div class="mb-4">
            <a href="<?= $baseUrl ?>/dashboard" class="inline-flex items-center px-6 py-3 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105 shadow-lg border border-gray-200 dark:border-gray-700">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Feed
            </a>
        </div>

        <!-- Post Container -->
        <div id="postContainer" class="singlePostContainer shadow-lg rounded-lg bg-gradient-to-br from-red-200 via-green-200 to-blue-200 p-2 rounded-xl shadow-md border border-gray-200 dark:border-gray-700" data-posts-id="<?= $postId ?>">
            <?= loadingSkeleton(1, false); ?>
        </div>

        <!-- Media Preview Section -->
        <div id="postMediaPreview" class="media-display-container mb-3">
            <!-- Media content will be dynamically inserted here -->
        </div>

        <!-- Comments Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mb-24">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-gray-800">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    Replies
                    <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400" id="commentCount">(0)</span>
                </h3>
            </div>

            <!-- Comments List -->
            <div class="p-4">
                <div class="space-y-4" id="commentsList">
                    <div class="flex items-center justify-center py-8">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-100 to-purple-100 dark:from-blue-900/30 dark:to-purple-900/30 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium" id="commentsLoading">Loading comments...</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Be the first to share your thoughts</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Fixed Comment Input -->
    <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-2xl backdrop-blur-sm">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <form id="commentForm" class="flex items-center space-x-3">
                <div class="flex-1 relative">
                    <textarea 
                        maxlength="300" 
                        name="" 
                        id="commentInput" 
                        rows="2"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-base resize-none outline-none transition-all duration-200 min-h-[56px] max-h-32 scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600 scrollbar-track-transparent hover:scrollbar-thumb-gray-400 dark:hover:scrollbar-thumb-gray-500" 
                        placeholder="Share your thoughts on this post..."
                        style="scrollbar-width: thin; scrollbar-color: #d1d5db transparent;"
                    ></textarea>
                    
                    <!-- Emoji Button (Hidden for now) -->
                    <button type="button" class="absolute hidden right-12 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
                
                <button type="submit"
                    class="flex-shrink-0 px-4 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200 transform hover:scale-105 shadow-lg">
                    <div class="flex items-center">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span class="text-sm font-medium">Post</span>
                    </div>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Enhanced Full View Modal -->
<?= full_view_modal() ?>
<div class="h-20"></div>