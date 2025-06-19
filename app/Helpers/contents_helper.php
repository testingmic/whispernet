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
?>