<?php
$title = "WhisperNet - Chat";
ob_start();
?>

<div class="flex flex-col h-[calc(100vh-8rem)]">
    <!-- Chat List -->
    <div class="bg-white border-b">
        <div class="max-w-2xl mx-auto px-4">
            <div class="flex items-center justify-between py-4">
                <h1 class="text-xl font-semibold text-gray-900">Messages</h1>
                <button class="text-blue-500 hover:text-blue-600">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Chat List -->
    <div class="flex-1 overflow-y-auto">
        <div class="max-w-2xl mx-auto px-4">
            <div class="space-y-1">
                <!-- Chat Item -->
                <div class="flex items-center space-x-3 p-3 hover:bg-gray-50 rounded-lg cursor-pointer">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-500 text-sm">AN</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Anonymous User</p>
                            <p class="text-xs text-gray-500">2m ago</p>
                        </div>
                        <p class="text-sm text-gray-500 truncate">Hey, I saw your post about the event...</p>
                    </div>
                </div>

                <!-- Active Chat Item -->
                <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg cursor-pointer">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-500 text-sm">AN</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-900">Anonymous User</p>
                            <p class="text-xs text-gray-500">Just now</p>
                        </div>
                        <p class="text-sm text-gray-500 truncate">Are you going to the meetup?</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Chat View (Hidden by default) -->
    <div class="hidden flex flex-col h-full">
        <!-- Chat Header -->
        <div class="bg-white border-b">
            <div class="max-w-2xl mx-auto px-4">
                <div class="flex items-center space-x-3 py-4">
                    <button class="text-gray-500 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <span class="text-blue-500 text-sm">AN</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900">Anonymous User</p>
                        <p class="text-xs text-gray-500">Active now</p>
                    </div>
                    <button class="text-gray-500 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Chat Messages -->
        <div class="flex-1 overflow-y-auto bg-gray-50">
            <div class="max-w-2xl mx-auto px-4 py-4">
                <div class="space-y-4">
                    <!-- Received Message -->
                    <div class="flex items-start space-x-2">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                <span class="text-blue-500 text-xs">AN</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-white rounded-lg px-4 py-2 shadow-sm">
                                <p class="text-sm text-gray-900">Hey, I saw your post about the event. Are you going?</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">2:30 PM</p>
                        </div>
                    </div>

                    <!-- Sent Message -->
                    <div class="flex items-start space-x-2 justify-end">
                        <div class="flex-1">
                            <div class="bg-blue-500 rounded-lg px-4 py-2 shadow-sm">
                                <p class="text-sm text-white">Yes, I'm planning to go! Are you?</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1 text-right">2:31 PM</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500 text-xs">ME</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Message Input -->
        <div class="bg-white border-t">
            <div class="max-w-2xl mx-auto px-4">
                <div class="flex items-center space-x-2 py-4">
                    <button class="text-gray-500 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                        </svg>
                    </button>
                    <input
                        type="text"
                        placeholder="Type a message..."
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    >
                    <button class="text-blue-500 hover:text-blue-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require_once __DIR__ . '/templates/layout.php';
?> 