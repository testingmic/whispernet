<?php
/**
 * App features
 * 
 * @return array
 */
function app_features() {
    return [
        'login' => [
            'title' => 'Welcome to TalkLowKey',
            'caption' => 'Share thoughts, ideas, and experiences in a safe environment where your privacy is our priority. Connect with your community through secure, private messaging.',
            'features' => [
                'Group & Individual' => 'Chat one-on-one or create group conversations',
                'Media Sharing' => 'Share images and media with your conversations',
            ]
        ],
        'signup' => [
            'title' => 'Join TalkLowKey',
            'caption' => 'Connect with your community, share your thoughts, and build meaningful conversations in a safe environment. Start your journey with secure, private messaging.',
            'features' => [
                'Tags' => 'Add tags to your posts to make them more discoverable',
                'Instant Setup' => 'No need to wait for approval, your account is ready to use immediately',
            ]
        ],
        'forgotten' => [
            'title' => 'Forgot Password?',
            'caption' => 'Don\'t worry! It happens to the best of us. Enter your email address and we\'ll send you a secure link to reset your password and get back to connecting with your community.',
            'help' => 'If you\'re still having trouble accessing your account, our support team is here to help.',
            'features' => [
                'Secure Reset' => 'One-time secure link sent to your email',
                'Easy Recovery' => 'Get back to your account immediately',
            ]
        ]
    ];
}

/**
 * Loading Skeleton
 * 
 * @return string
 */
function loadingSkeleton($count = 1, $padding = true) {
    $html = '';
    for ($i = 0; $i < $count; $i++) {
        $html .= '
        <div class="max-w-7xl mx-auto '.($padding ? 'px-4 py-2' : 'mb-2').' sm:px-6 lg:px-8 loading-skeleton">
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

/**
 * Imojis list
 * 
 * @return string
 */
function imoji_list() {
    $imojis = [
        '😊',
        '😂',
        '❤️',
        '👍',
        '🎉',
        '🔥',
        '💯',
        '✨',
        '😍',
        '🤔',
        '😮',
        '😢',
        '😡',
        '🤯',
        '🥳',
        '😴',
        '🤑',
        '🤠',
        '🤡',
        '🤠',
        '🤠',
        '🤠',
    ];
    $html = '';
    foreach ($imojis as $imoji) {
        $html .= '<button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">'.$imoji.'</button>';
    }
    return $html;
}

/**
 * Create post form
 * 
 * @return string
 */
function createPostForm() {
    return '<form id="createPostFormUnique" class="space-y-2" onsubmit="return false;">
            <!-- Textarea Section -->
            <div class="space-y-2">
                <label for="content" class="block text-sm font-medium text-gray-700">What\'s on your mind?</label>
                <div class="relative">
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="4" 
                        class="w-full font-medium text-xl px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-all duration-200"
                        placeholder="Share your thoughts and experiences with users in a 30km radius..."
                    ></textarea>
                    <div class="absolute bottom-2 right-2 flex items-center space-x-2">
                        <!-- Character Counter -->
                        <span id="charCount" class="text-xs text-gray-400">0/1000</span>
                    </div>
                </div>
            </div>

            <!-- Media Upload Section -->
            <div class="">
                
                <div class="flex items-center space-x-4">


                    <!-- Emoji Selector -->
                    <div class="flex items-center space-x-4">
                        <button type="button" id="emojiBtn" class="flex items-center justify-center w-12 h-12 bg-yellow-100 hover:bg-yellow-200 rounded-lg transition-colors duration-200">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- File Upload -->
                    <div class="flex items-center space-x-4">
                        <label for="fileUpload" class="flex items-center justify-center w-12 h-12 bg-gray-100 hover:bg-gray-200 rounded-lg cursor-pointer transition-colors duration-200">
                            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                            <input type="file" id="fileUpload" name="media[]" accept="image/*,video/*" multiple class="hidden" />
                        </label>
                    </div>

                    <!-- Audio Recording -->
                    <div class="flex items-center space-x-2">
                        <button type="button" id="audioRecordBtn" class="flex items-center justify-center w-12 h-12 bg-red-100 hover:bg-red-200 rounded-lg transition-colors duration-200">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                        </button>
                        <span id="audioStatus" class="text-sm text-gray-600"></span>
                        <span id="audioTimer" class="text-sm text-red-600 hidden">00:00</span>
                        <button type="button" id="audioPauseBtn" class="hidden flex items-center justify-center w-8 h-8 bg-yellow-100 hover:bg-yellow-200 rounded-lg transition-colors duration-200">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div id="imagePreviewGrid" class="hidden grid grid-cols-4 gap-2 mt-4"></div>

                <!-- Emoji Picker (Hidden by default) -->
                <div id="emojiPicker" class="hidden bg-gray-50 rounded-lg p-4 border">
                    <div class="grid grid-cols-8 gap-2">
                        '.imoji_list().'
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div id="mediaPreview" class="hidden space-y-2">
                <label class="block text-sm font-medium text-gray-700">Preview</label>
                <div id="previewContainer" class="bg-gray-50 rounded-lg p-4 min-h-[100px] flex items-center justify-center">
                    <span class="text-gray-400">Media preview will appear here</span>
                </div>
            </div>

            <!-- Audio Preview -->
            <div id="audioPreview" class="hidden space-y-2">
                <label class="block text-sm font-medium text-gray-700">Audio Preview</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <audio id="audioPlayer" controls class="w-full"></audio>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Location will be added automatically</span>
                </div>
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span>Post</span>
                    </span>
                </button>
            </div>
        </form>';
}

/**
 * Full view modal
 * 
 * @return string
 */
function full_view_modal() {
    return '<div id="fullViewModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black bg-opacity-90 backdrop-blur-sm"></div>
    
    <div class="relative h-full flex items-center justify-center p-4">
        <!-- Close Button -->
        <button id="closeModal" class="absolute top-6 right-6 z-10 bg-white/60 hover:bg-white/80 text-black rounded-full p-3 transition-all duration-200 backdrop-blur-sm border border-white/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Navigation Buttons -->
        <button id="prevBtn" class="absolute left-6 top-1/2 transform -translate-y-1/2 z-10 bg-white/50 hover:border-gray-700 hover:bg-white/30 text-gray-400 rounded-full p-3 transition-all duration-200 backdrop-blur-sm border border-gray-400">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button id="nextBtn" class="absolute right-6 top-1/2 transform -translate-y-1/2 z-10 bg-white/50 hover:border-gray-700 hover:bg-white/30 text-gray-400 rounded-full p-3 transition-all duration-200 backdrop-blur-sm border border-gray-400">
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
</div>';
}

function custom_button($title, $link, $description = '') {
    return '
    <div class="bg-gradient-to-r from-green-50 mb-3 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-lg p-4 border border-green-200 dark:border-green-700/30 shadow-sm">
        <div class="text-center">
            <a href="'.$link.'" 
                class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                </svg>
                '.$title.'
            </a>
            '.(empty($description) ? '' : '<p class="text-xs text-gray-500 dark:text-gray-400 mt-2">'.$description.'</p>').'
        </div>
    </div>';
}
?>