<?php
$chats = $chats ?? [];
$users = $users ?? [];
$currentChat = $currentChat ?? null;
$messages = $messages ?? [];
?>

<div class="dbg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex items-center justify-center p-4">
    <!-- Main Chat Interface -->
    <div class="w-full max-w-7xl">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="flex flex-col lg:flex-row h-[73vh] lg:h-[70vh]">
                <!-- Left Sidebar - Chat List & Search -->
                <div class="w-full lg:w-80 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 flex flex-col h-[28rem] lg:h-full">
                    <!-- Header with New Chat Button -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white">Messages</h2>
                            <button id="newChatBtn" class="p-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <!-- Search Bar -->
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Search users..." 
                                   class="w-full px-4 py-2 pl-10 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white text-sm">
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Chat List -->
                    <div class="flex-1 overflow-y-auto min-h-0">
                        <div id="chatList" class="space-y-1 p-2">
                            <!-- Individual Chats -->
                            <div class="space-y-2">
                                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-2">Individual Chats</h3>
                                
                                <?php if(empty($chatRooms)) { ?>
                                <div id="individualChats">
                                    <div class=" p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200" data-chat-id="0">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0" onclick="return individualChatBtnClick()">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">Create Individual Chat</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Start a conversation with a single person</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php foreach($chatRooms as $chat) { ?>
                                    <?php if($chat['room']['type'] !== 'individual') continue; ?>
                                    <div onclick="return beginChat(<?= $chat['room_id']; ?>, '<?= $chat['room']['type']; ?>')" class="cursor-pointer p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= !empty($chat['room']['name']) ? $chat['room']['name'] : $chat['full_name']; ?></p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400"><?= !empty($chat['room']['description']) ? $chat['room']['description'] : $chat['username']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>

                            </div>

                            <!-- Group Chats -->
                            <div class="space-y-2 mt-4">
                                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-2">Group Chats</h3>
                                <?php if(empty($groupChats)) { ?>
                                <div class="group-chat-item p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200" data-chat-type="group">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-semibold">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0" onclick="return groupChatBtnClick()">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Create Group Chat</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Start a conversation with multiple people</p>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php foreach($groupChats as $chat) { ?>
                                    <?php if($chat['room']['type'] !== 'group') continue; ?>
                                    <div onclick="return beginChat(<?= $chat['room_id']; ?>, '<?= $chat['room']['type']; ?>')" class="cursor-pointer p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-semibold">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white"><?= !empty($chat['room']['name']) ? $chat['room']['name'] : $chat['full_name']; ?></p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400"><?= !empty($chat['room']['description']) ? $chat['room']['description'] : $chat['username']; ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Chat Area -->
                <div class="flex-1 flex flex-col h-96 lg:h-full">
                    <!-- Chat Header -->
                    <div id="chatHeader" class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-700/50">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <button id="backToChats" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <div id="chatAvatar" class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 id="chatTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Go Back</h3>
                                    <p id="chatStatus" class="text-sm text-gray-500 dark:text-gray-400">Offline</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button id="chatInfoBtn" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Messages Area -->
                    <div id="messagesArea" class="flex-1 overflow-y-auto p-4 bg-gray-50 dark:bg-gray-700/50">

                        <div id="selfDestructMessage" class="text-center hidden text-sm text-danger color-red-500 mb-4 border-b border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-700/50 dark:text-white">
                            <span class="text-red-500 dark:text-white">All messages in this chat will self destruct after 24 hours.</span>
                        </div>

                        <div id="welcomeMessage" class="flex items-center justify-center h-full">
                            <div class="text-center">
                                <div class="w-20 h-20 mx-auto mb-6 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Welcome to Chat</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">Select a conversation from the sidebar or start a new chat</p>
                                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 justify-center">
                                    <button id="startIndividualChat" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                                        Start Individual Chat
                                    </button>
                                    <button id="startGroupChat" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200">
                                        Create Group Chat
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <div id="messagesContainer" class="space-y-4 hidden">
                            <!-- Messages will be dynamically loaded here -->
                        </div>
                    </div>

                    <!-- Message Input -->
                    <div id="messageInputArea" class="p-2 pt-3 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hidden">
                        <!-- Media Preview Area -->
                        <div id="mediaPreviewArea" class="mb-3 hidden">
                            <div class="flex items-center justify-between mb-2">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300">Media Preview</h4>
                                <button type="button" id="clearAllMedia" class="text-xs text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                    Clear All
                                </button>
                            </div>
                            <div id="mediaPreviewContainer" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-2">
                                <!-- Preview items will be added here -->
                            </div>
                        </div>
                        
                        <form id="messageForm" class="flex items-end space-x-2 sm:space-x-4">
                            <!-- Hidden file input -->
                            <input type="file" id="fileInput" accept="image/*,video/*" multiple class="hidden">
                            
                            <button type="button" id="attachButton" class="p-2 hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                            </button>
                            <div class="flex-1 relative">
                                <textarea id="messageInput" rows="1" placeholder="Type a message..." 
                                         class="w-full px-2 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none outline-none transition-all duration-200"></textarea>
                            </div>
                            <button type="button" id="recordButton" class="p-2 hidden text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                                </svg>
                            </button>
                            <button type="submit" class="px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Chat Modal -->
<div id="newChatModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="inline-block w-full max-w-sm sm:max-w-md p-4 sm:p-6 lg:p-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-2xl">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Start New Chat</h3>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Choose who you want to chat with</p>
                </div>
                <button id="closeNewChatModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Chat Type Selection -->
            <div class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <button onclick="return individualChatBtnClick()" class="p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-200 text-left">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Individual Chat</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Chat with one person</p>
                    </button>
                    
                    <button onclick="return groupChatBtnClick()" class="p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-green-500 dark:hover:border-green-500 transition-all duration-200 text-left">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Group Chat</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Chat with multiple people</p>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- User Search Modal -->
<div id="userSearchModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="inline-block w-full max-w-sm sm:max-w-lg p-4 sm:p-6 lg:p-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-2xl max-h-[80vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Select User</h3>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Choose a user to start chatting with</p>
                </div>
                <button id="closeUserSearchModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- User Search -->
            <div class="space-y-4">
                <div class="relative">
                    <input type="text" id="userSearchInput" placeholder="Search users..." 
                           class="w-full px-4 py-3 pl-10 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:text-white">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                
                <div id="userList" class="space-y-2 max-h-96 overflow-y-auto"></div>
            </div>
        </div>
    </div>
</div>

<!-- Group Creation Modal -->
<div id="groupCreationModal" class="fixed inset-0 z-50 items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="inline-block w-full max-w-sm sm:max-w-lg p-4 sm:p-6 lg:p-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-2xl max-h-[80vh] overflow-y-auto">
            <!-- Modal Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Create Group Chat</h3>
                    <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-1">Set up a new group conversation</p>
                </div>
                <button id="closeGroupCreationModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div id="unreadPostsCountContainer" class=" mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Coming soon</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">We are working on this feature. Please check back later.</p>
                    <button onclick="return closeModal();" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105">
                        Close
                    </button>
                </div>
            </div>
            <!-- Group Creation Form -->
            <form id="groupCreationForm" class="space-y-6 hidden">
                <div>
                    <label for="groupName" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Group Name</label>
                    <input type="text" id="groupName" name="groupName" required 
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
                           placeholder="Enter group name">
                </div>
                
                <div>
                    <label for="groupDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                    <textarea id="groupDescription" name="groupDescription" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none"
                              placeholder="Describe the purpose of this group"></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Members</label>
                    <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-xl p-3">
                        <?php foreach ($users as $user): ?>
                            <label class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                <input type="checkbox" name="members[]" value="<?= $user['id'] ?>" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white text-sm font-semibold">
                                    <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white truncate"><?= htmlspecialchars($user['name'] ?? 'Unknown') ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4 pt-4">
                    <button type="button" id="cancelGroupCreation" class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                        Cancel
                    </button>
                    <button type="submit" class="px-8 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105">
                        Create Group
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
