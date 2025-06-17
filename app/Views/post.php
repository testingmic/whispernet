<!-- Post View Container -->
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Post Section -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
            <!-- Post Header -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium">
                            <?= substr($post['username'] ?? 'AN', 0, 2) ?>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white"><?= $post['username'] ?? 'Anonymous User' ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400"><?= date('M d, Y • h:i A', strtotime($post['created_at'] ?? time())) ?></p>
                        </div>
                    </div>
                    <button class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Post Content -->
            <div class="p-4">
                <p class="text-gray-800 dark:text-gray-200 mb-4"><?= $post['content'] ?? 'No content' ?></p>
                
                <!-- Media Attachment -->
                <?php if (!empty($post['media_url'])): ?>
                <div class="rounded-xl overflow-hidden mb-4">
                    <?php if (strpos($post['media_url'], '.mp4') !== false): ?>
                        <video class="w-full" controls>
                            <source src="<?= $post['media_url'] ?? '' ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    <?php else: ?>
                        <img src="<?= $post['media_url'] ?>" alt="Post attachment" class="w-full object-cover">
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Post Actions -->
                <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">
                    <div class="flex items-center space-x-4">
                        <button class="flex items-center space-x-1 hover:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                            <span>Like</span>
                        </button>
                        <button class="flex items-center space-x-1 hover:text-blue-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                            <span>Share</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Comments Section -->
        <div class="mt-6 space-y-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Comments</h3>
            
            <!-- Comments List -->
            <div class="space-y-4" id="commentsList">
                <?php foreach ($comments ?? [] as $comment): ?>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                    <div class="p-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-medium">
                                <?= substr($comment['username'] ?? 'AN', 0, 2) ?>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-gray-900 dark:text-white"><?= $comment['username'] ?? 'Anonymous User' ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?= date('M d, Y • h:i A', strtotime($comment['created_at'])) ?></p>
                                </div>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-300"><?= $comment['content'] ?></p>
                                <div class="mt-2 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                    <button class="hover:text-blue-500 transition-colors">Like</button>
                                    <button class="hover:text-blue-500 transition-colors">Reply</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Fixed Comment Input -->
    <div class="fixed bottom-16 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <form id="commentForm" class="flex items-center space-x-4">
                <div class="flex-1 relative">
                    <textarea name="" id="commentInput" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white text-base" placeholder="Write a comment..."></textarea>
                    <button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
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
<div class="h-32"></div> 