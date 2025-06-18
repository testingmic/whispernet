<?php
/**
 * Loading Skeleton
 * 
 * @return string
 */
function loadingSkeleton($count = 1, $padding = true) {
    $html = '';
    for ($i = 0; $i < $count; $i++) {
        $html .= '
        <div class="max-w-7xl mx-auto '.($padding ? 'px-4 py-2' : 'mb-2').' sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden animate-pulse">
                <!-- Post Header Skeleton -->
                <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700"></div>
                        <div class="flex-1">
                            <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-3/4"></div>
                            <div class="h-3 bg-gray-200 dark:bg-gray-700 rounded w-1/2 mt-2"></div>
                        </div>
                    </div>
                </div>
                
                <!-- Post Content Skeleton -->
                <div class="p-4 space-y-3">
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-full"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-5/6"></div>
                    <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded w-4/6"></div>
                    
                    <!-- Image Skeleton -->
                    <div class="h-48 bg-gray-200 dark:bg-gray-700 rounded-lg mt-4"></div>
                    
                    <!-- Action Buttons Skeleton -->
                    <div class="flex items-center space-x-4 pt-4">
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                        <div class="h-8 bg-gray-200 dark:bg-gray-700 rounded w-20"></div>
                    </div>
                </div>
            </div>
        </div>';
    }
    return $html;
}

function sampleFeed() {
    return '<!-- Feed Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="space-y-4 posts-container">
            <!-- Post Template -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium">
                                AN
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Anonymous User</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">2 hours ago</p>
                            </div>
                        </div>
                        <button class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" aria-label="Post options">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z" />
                            </svg>
                        </button>
                    </div>
                    <p class="text-gray-800 dark:text-gray-200 mb-4">This is a sample post content. It can include text, images, and links.</p>
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-4">
                            <button class="flex items-center space-x-1 hover:text-blue-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5" />
                                </svg>
                                <span>Like</span>
                            </button>
                            <button class="flex items-center space-x-1 hover:text-blue-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <span>Comment</span>
                            </button>
                            <button class="flex items-center space-x-1 hover:text-blue-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                                </svg>
                                <span>Share</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>';
}
?>