<div class="flex flex-col h-[calc(100vh-8rem)] bg-gray-50 dark:bg-gray-900">
    <!-- Chat Container -->
    <div class="flex h-full">
        <!-- Chat List Sidebar -->
        <div id="chatList" class="w-full md:w-96 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 flex flex-col">
            <!-- Search and New Chat -->
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <div class="relative">
                    <input type="text" 
                        placeholder="Search conversations..." 
                        class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                    >
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <button class="mt-4 w-full flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Message
                </button>
            </div>

            <!-- Chat List -->
            <div class="flex-1 overflow-y-auto bg-gray-100">
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?= loadingSkeleton() ?>
                    <?php foreach ($chats ?? [] as $i => $chat) { ?>
                        <!-- Chat Item -->
                        <!-- <div class="chat-item p-4 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                            <div class="flex items-center space-x-3">
                                <div class="relative">
                                    <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                        <span class="text-blue-600 dark:text-blue-300 text-sm font-medium">JD</span>
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">John Doe <?= $i ?></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">2m ago</p>
                                    </div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate">Hey, I saw your post about the event...  <?= $i ?></p>
                                </div>
                            </div>
                        </div> -->
                    <?php } ?>
                </div>
            </div>
        </div>

        <!-- Chat View -->
        <div id="chatView" class="hidden md:flex flex-col flex-1">
            <!-- Chat Header -->
            <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between p-4">
                    <div class="flex items-center space-x-3">
                        <button id="backToList" class="md:hidden text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </button>
                        <div class="relative">
                            <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-300 text-sm font-medium">AS</span>
                            </div>
                            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white dark:border-gray-800 rounded-full"></span>
                        </div>
                        <div>
                            <h2 class="text-sm font-medium text-gray-900 dark:text-white">Alice Smith</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Active now</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="p-2 text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </button>
                        <button class="p-2 text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                        </button>
                        <button class="p-2 text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Messages Area -->
            <div class="flex-1 overflow-y-auto bg-gray-50 dark:bg-gray-900 p-4">
                <div class="max-w-2xl mx-auto space-y-4">
                    <!-- Date Separator -->
                    <div class="flex items-center justify-center">
                        <span class="text-xs text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-800 px-3 py-1 rounded-full">Today</span>
                    </div>

                    <!-- Received Message -->
                    <div class="flex items-start space-x-2">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-300 text-xs">AS</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-white dark:bg-gray-800 rounded-lg px-4 py-2 shadow-sm">
                                <p class="text-sm text-gray-900 dark:text-white">Hey, I saw your post about the event. Are you going?</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">2:30 PM</p>
                        </div>
                    </div>

                    <!-- Sent Message -->
                    <div class="flex items-start space-x-2 justify-end">
                        <div class="flex-1">
                            <div class="bg-blue-600 rounded-lg px-4 py-2 shadow-sm">
                                <p class="text-sm text-white">Yes, I'm planning to go! Are you?</p>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 text-right">2:31 PM</p>
                        </div>
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-gray-500 dark:text-gray-400 text-xs">ME</span>
                            </div>
                        </div>
                    </div>

                    <!-- Image Message -->
                    <div class="flex items-start space-x-2">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                <span class="text-blue-600 dark:text-blue-300 text-xs">AS</span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                                <img src="https://images.unsplash.com/photo-1522075469751-3a6694fb2f61" alt="Event photo" class="w-full h-48 object-cover">
                                <div class="px-4 py-2">
                                    <p class="text-sm text-gray-900 dark:text-white">Here's a photo from last year's event!</p>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">2:32 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Input - Fixed at bottom -->
            <div class="fixed bottom-16 left-0 right-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 shadow-lg bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                <div class="max-w-2xl mx-auto p-4">
                    <!-- Media Preview -->
                    <div id="mediaPreview" class="hidden mb-4 relative">
                        <div class="relative inline-block">
                            <img id="previewImage" src="" alt="Preview" class="max-h-48 rounded-lg">
                            <button id="removeMedia" class="absolute top-2 right-2 p-1 bg-gray-900 bg-opacity-50 rounded-full text-white hover:bg-opacity-75">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Audio Recorder -->
                    <div id="audioRecorder" class="hidden mb-4">
                        <div class="flex items-center space-x-4 bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">Recording...</span>
                                </div>
                                <div class="mt-2 h-1 bg-gray-200 dark:bg-gray-600 rounded-full">
                                    <div class="h-1 bg-blue-500 rounded-full animate-pulse" style="width: 30%"></div>
                                </div>
                            </div>
                            <button id="stopRecording" class="p-2 text-red-500 hover:text-red-600">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Input Form -->
                    <form id="messageForm" class="flex items-end space-x-2">
                        <div class="flex-1 relative">
                            <textarea
                                id="messageInput"
                                rows="1"
                                placeholder="Type a message..."
                                class="w-full px-4 py-3 pr-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none"
                            ></textarea>
                            <button type="button" id="emojiButton" class="absolute right-3 bottom-3 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex space-x-2">
                            <button type="button" id="attachButton" class="p-3 text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                                </svg>
                            </button>
                            <button type="button" id="recordButton" class="p-3 text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                                </svg>
                            </button>
                            <button type="submit" class="p-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </div>
                    </form>

                    <!-- Hidden File Input -->
                    <input type="file" id="fileInput" class="hidden" accept="image/*,video/*">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Emoji Picker (Hidden by default) -->
<div id="emojiPicker" class="hidden fixed bottom-24 right-4 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 p-4">
    <!-- Emoji grid will be populated by JavaScript -->
</div>

<!-- New Message Modal -->
<div id="newMessageModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" role="dialog" aria-modal="true">
    <div class="min-h-screen px-4 text-center">
        <div class="fixed inset-0" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>

        <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">New Message</h3>
                <button type="button" class="close-modal text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <div class="relative">
                    <input type="text" 
                           id="userSearchInput"
                           class="w-full px-4 py-2 pl-10 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:border-transparent dark:bg-gray-700 dark:text-white" 
                           placeholder="Search users...">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>

            <div id="userSearchResults" class="max-h-96 overflow-y-auto">
                <!-- User search results will be populated here -->
            </div>

            <div class="mt-4 flex justify-end space-x-3">
                <button type="button" class="close-modal px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>
