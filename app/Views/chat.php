<?php
$chats = $chats ?? [];
$users = $users ?? [];
$currentChat = $currentChat ?? null;
$messages = $messages ?? [];
?>

<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Header Section -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-8">
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-sm font-medium mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Messaging Center
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                    Connect & Chat
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                    Start conversations with friends or create group chats to collaborate
                </p>
            </div>
        </div>
    </div>

    <!-- Main Chat Interface -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="flex flex-col lg:flex-row h-[600px] lg:h-[600px]">
                <!-- Left Sidebar - Chat List & Search -->
                <div class="w-full lg:w-80 border-b lg:border-b-0 lg:border-r border-gray-200 dark:border-gray-700 flex flex-col h-64 lg:h-full">
                    <!-- Header with New Chat Button -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
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
                    <div class="flex-1 overflow-y-auto">
                        <div id="chatList" class="space-y-1 p-2">
                            <!-- Individual Chats -->
                            <div class="space-y-2">
                                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-2">Individual Chats</h3>
                                <?php foreach ($chats ?? [] as $chat): ?>
                                    <div class="chat-item p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200 <?= (!empty($currentChat) && ($currentChat['id'] ?? 0 === $chat['id'] ?? 0)) ? 'bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800' : '' ?>" data-chat-id="<?= $chat['id'] ?? 0 ?>">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                                <?= strtoupper(substr($chat['name'] ?? 'U', 0, 1)) ?>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white truncate"><?= htmlspecialchars($chat['name'] ?? 'Unknown') ?></p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?= htmlspecialchars($chat['last_message'] ?? 'No messages yet') ?></p>
                                            </div>
                                            <?php if (($chat['unread_count'] ?? 0) > 0): ?>
                                                <div class="w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">
                                                    <?= $chat['unread_count'] ?? 0 ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <!-- Group Chats -->
                            <div class="space-y-2 mt-4">
                                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider px-3 py-2">Group Chats</h3>
                                <div class="group-chat-item p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200" data-chat-type="group">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-semibold">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white">Create Group Chat</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Start a conversation with multiple people</p>
                                        </div>
                                    </div>
                                </div>
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
                                    <h3 id="chatTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Select a chat to start messaging</h3>
                                    <p id="chatStatus" class="text-sm text-gray-500 dark:text-gray-400">Choose from your conversations</p>
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
                    <div id="messageInputArea" class="p-4 border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hidden">
                        <form id="messageForm" class="flex items-end space-x-2 sm:space-x-4">
                            <button type="button" id="attachButton" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                            </button>
                            <div class="flex-1 relative">
                                <textarea id="messageInput" rows="1" placeholder="Type a message..." 
                                         class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none outline-none transition-all duration-200"></textarea>
                                <div class="absolute bottom-2 right-2 text-xs text-gray-400">
                                    <span id="charCount">0</span>/500
                                </div>
                            </div>
                            <button type="button" id="recordButton" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
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
                    <button id="individualChatBtn" class="p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-blue-500 dark:hover:border-blue-500 transition-all duration-200 text-left">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Individual Chat</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Chat with one person</p>
                    </button>
                    
                    <button id="groupChatBtn" class="p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-green-500 dark:hover:border-green-500 transition-all duration-200 text-left">
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
<div id="userSearchModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
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
                
                <div id="userList" class="space-y-2 max-h-96 overflow-y-auto">
                    <?php foreach ($users as $user): ?>
                        <div class="user-item p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200 border border-gray-200 dark:border-gray-600" data-user-id="<?= $user['id'] ?>" data-user-name="<?= htmlspecialchars($user['name'] ?? '') ?>">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                                    <?= strtoupper(substr($user['name'] ?? 'U', 0, 1)) ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-gray-900 dark:text-white truncate"><?= htmlspecialchars($user['name'] ?? 'Unknown') ?></p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 truncate"><?= htmlspecialchars($user['email'] ?? '') ?></p>
                                </div>
                                <button class="px-3 sm:px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-sm font-semibold rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                                    Chat
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Group Creation Modal -->
<div id="groupCreationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
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

            <!-- Group Creation Form -->
            <form id="groupCreationForm" class="space-y-6">
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

<script>
// Enhanced Chat Management with Mobile Responsiveness
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const newChatBtn = document.getElementById('newChatBtn');
    const newChatModal = document.getElementById('newChatModal');
    const closeNewChatModal = document.getElementById('closeNewChatModal');
    const individualChatBtn = document.getElementById('individualChatBtn');
    const groupChatBtn = document.getElementById('groupChatBtn');
    const userSearchModal = document.getElementById('userSearchModal');
    const closeUserSearchModal = document.getElementById('closeUserSearchModal');
    const groupCreationModal = document.getElementById('groupCreationModal');
    const closeGroupCreationModal = document.getElementById('closeGroupCreationModal');
    const cancelGroupCreation = document.getElementById('cancelGroupCreation');
    const groupCreationForm = document.getElementById('groupCreationForm');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const charCount = document.getElementById('charCount');
    const searchInput = document.getElementById('searchInput');
    const userSearchInput = document.getElementById('userSearchInput');
    const chatList = document.getElementById('chatList');
    const messagesContainer = document.getElementById('messagesContainer');
    const welcomeMessage = document.getElementById('welcomeMessage');
    const messageInputArea = document.getElementById('messageInputArea');
    const chatHeader = document.getElementById('chatHeader');
    const chatTitle = document.getElementById('chatTitle');
    const chatStatus = document.getElementById('chatStatus');
    const chatAvatar = document.getElementById('chatAvatar');
    const backToChats = document.getElementById('backToChats');
    
    // Welcome message buttons
    const startIndividualChat = document.getElementById('startIndividualChat');
    const startGroupChat = document.getElementById('startGroupChat');

    // Mobile view management
    let isMobileView = window.innerWidth < 1024;
    let currentView = 'chat-list'; // 'chat-list' or 'chat-area'

    // Check for mobile view on resize
    window.addEventListener('resize', function() {
        isMobileView = window.innerWidth < 1024;
        updateMobileView();
    });

    function updateMobileView() {
        const chatListContainer = document.querySelector('.w-full.lg\\:w-80');
        const chatAreaContainer = document.querySelector('.flex-1.flex.flex-col');
        
        if (isMobileView) {
            if (currentView === 'chat-list') {
                chatListContainer.classList.remove('hidden');
                chatAreaContainer.classList.add('hidden');
            } else {
                chatListContainer.classList.add('hidden');
                chatAreaContainer.classList.remove('hidden');
            }
        } else {
            chatListContainer.classList.remove('hidden');
            chatAreaContainer.classList.remove('hidden');
        }
    }

    function showChatArea() {
        if (isMobileView) {
            currentView = 'chat-area';
            updateMobileView();
        }
    }

    function showChatList() {
        if (isMobileView) {
            currentView = 'chat-list';
            updateMobileView();
        }
    }

    // Initialize mobile view
    updateMobileView();

    // Modal Management
    function showModal(modal) {
        modal.classList.remove('hidden');
        modal.querySelector('.inline-block').classList.add('animate-fadeIn');
    }

    function hideModal(modal) {
        modal.classList.add('hidden');
        modal.querySelector('.inline-block').classList.remove('animate-fadeIn');
    }

    // New Chat Button
    newChatBtn.addEventListener('click', () => showModal(newChatModal));

    // Welcome message buttons
    if (startIndividualChat) {
        startIndividualChat.addEventListener('click', () => {
            showModal(userSearchModal);
        });
    }

    if (startGroupChat) {
        startGroupChat.addEventListener('click', () => {
            showModal(groupCreationModal);
        });
    }

    // Back to chats button (mobile)
    if (backToChats) {
        backToChats.addEventListener('click', () => {
            showChatList();
        });
    }

    // Close modals
    [closeNewChatModal, closeUserSearchModal, closeGroupCreationModal, cancelGroupCreation].forEach(btn => {
        if (btn) {
            btn.addEventListener('click', () => {
                if (newChatModal.classList.contains('hidden') === false) hideModal(newChatModal);
                if (userSearchModal.classList.contains('hidden') === false) hideModal(userSearchModal);
                if (groupCreationModal.classList.contains('hidden') === false) hideModal(groupCreationModal);
            });
        }
    });

    // Click outside to close
    [newChatModal, userSearchModal, groupCreationModal].forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) hideModal(modal);
        });
    });

    // Individual Chat Button
    individualChatBtn.addEventListener('click', () => {
        hideModal(newChatModal);
        showModal(userSearchModal);
    });

    // Group Chat Button
    groupChatBtn.addEventListener('click', () => {
        hideModal(newChatModal);
        showModal(groupCreationModal);
    });

    // User Selection
    document.querySelectorAll('.user-item').forEach(item => {
        item.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const userName = this.getAttribute('data-user-name');
            
            // Update chat header
            chatTitle.textContent = userName;
            chatStatus.textContent = 'Online';
            chatAvatar.innerHTML = userName.charAt(0).toUpperCase();
            
            // Show chat area
            welcomeMessage.classList.add('hidden');
            messagesContainer.classList.remove('hidden');
            messageInputArea.classList.remove('hidden');
            
            // Load messages for this user
            loadMessages(userId, 'individual');
            
            hideModal(userSearchModal);
            showChatArea();
        });
    });

    // Chat item selection (for existing chats)
    document.querySelectorAll('.chat-item').forEach(item => {
        item.addEventListener('click', function() {
            const chatId = this.getAttribute('data-chat-id');
            const chatName = this.querySelector('p').textContent;
            
            // Update chat header
            chatTitle.textContent = chatName;
            chatStatus.textContent = 'Online';
            chatAvatar.innerHTML = chatName.charAt(0).toUpperCase();
            
            // Show chat area
            welcomeMessage.classList.add('hidden');
            messagesContainer.classList.remove('hidden');
            messageInputArea.classList.remove('hidden');
            
            // Load messages for this chat
            loadMessages(chatId, 'existing');
            
            showChatArea();
        });
    });

    // Group Creation Form
    groupCreationForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const groupName = formData.get('groupName');
        const groupDescription = formData.get('groupDescription');
        const members = formData.getAll('members[]');
        
        if (!groupName.trim()) {
            showNotification('Please enter a group name', 'error');
            return;
        }
        
        if (members.length === 0) {
            showNotification('Please select at least one member', 'error');
            return;
        }
        
        // Update chat header
        chatTitle.textContent = groupName;
        chatStatus.textContent = `${members.length + 1} members`;
        chatAvatar.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
        `;
        
        // Show chat area
        welcomeMessage.classList.add('hidden');
        messagesContainer.classList.remove('hidden');
        messageInputArea.classList.remove('hidden');
        
        // Create group and load messages
        createGroup(groupName, groupDescription, members);
        
        hideModal(groupCreationModal);
        this.reset();
        
        // Reset all checkboxes
        document.querySelectorAll('input[name="members[]"]').forEach(checkbox => {
            checkbox.checked = false;
        });
        
        showChatArea();
    });

    // Message Input Management
    if (messageInput) {
        messageInput.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = length;
            
            // Auto-resize
            this.style.height = 'auto';
            this.style.height = Math.min(this.scrollHeight, 120) + 'px';
        });

        messageInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                if (this.value.trim().length > 0) {
                    messageForm.dispatchEvent(new Event('submit'));
                }
            }
        });

        // Focus input when chat area is shown on mobile
        if (isMobileView) {
            messageInput.addEventListener('focus', function() {
                // Scroll to bottom to ensure input is visible
                setTimeout(() => {
                    window.scrollTo(0, document.body.scrollHeight);
                }, 100);
            });
        }
    }

    // Message Form Submission
    if (messageForm) {
        messageForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            if (message.length === 0) return;
            
            // Add message to UI
            addMessageToUI(message, 'sent');
            
            // Clear input
            messageInput.value = '';
            messageInput.style.height = 'auto';
            charCount.textContent = '0';
            
            // Simulate sending
            setTimeout(() => {
                addMessageToUI('Message received!', 'received');
            }, 1000);
        });
    }

    // Search Functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            filterChats(query);
        });
    }

    if (userSearchInput) {
        userSearchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase();
            filterUsers(query);
        });
    }

    // Helper Functions
    function loadMessages(chatId, type) {
        // Simulate loading messages
        messagesContainer.innerHTML = `
            <div class="flex items-center justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
            </div>
        `;
        
        setTimeout(() => {
            messagesContainer.innerHTML = `
                <div class="text-center py-8">
                    <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
                </div>
            `;
        }, 1000);
    }

    function addMessageToUI(content, type) {
        const messageDiv = document.createElement('div');
        messageDiv.className = `flex ${type === 'sent' ? 'justify-end' : 'justify-start'}`;
        
        messageDiv.innerHTML = `
            <div class="flex items-end space-x-2 max-w-[85%] sm:max-w-[70%]">
                ${type === 'received' ? `
                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300">
                        U
                    </div>
                ` : ''}
                <div class="flex flex-col ${type === 'sent' ? 'items-end' : 'items-start'}">
                    <div class="rounded-2xl px-4 py-2 ${type === 'sent' ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'}">
                        <p class="text-sm break-words">${content}</p>
                    </div>
                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        ${new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}
                    </span>
                </div>
            </div>
        `;
        
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function filterChats(query) {
        const chatItems = document.querySelectorAll('.chat-item');
        chatItems.forEach(item => {
            const name = item.querySelector('p').textContent.toLowerCase();
            if (name.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function filterUsers(query) {
        const userItems = document.querySelectorAll('.user-item');
        userItems.forEach(item => {
            const name = item.querySelector('p').textContent.toLowerCase();
            if (name.includes(query)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function createGroup(name, description, members) {
        // Simulate group creation
        showNotification(`Group "${name}" created successfully!`, 'success');
        
        // Add the new group to the chat list
        const groupChatItem = document.createElement('div');
        groupChatItem.className = 'chat-item p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200';
        groupChatItem.setAttribute('data-chat-id', 'group-' + Date.now());
        groupChatItem.setAttribute('data-chat-type', 'group');
        
        groupChatItem.innerHTML = `
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-gray-900 dark:text-white truncate">${name}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">Group created</p>
                </div>
            </div>
        `;
        
        // Add to the group chats section
        const groupChatsSection = document.querySelector('.space-y-2.mt-4');
        if (groupChatsSection) {
            groupChatsSection.appendChild(groupChatItem);
        }
    }

    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
        notification.classList.add(bgColor, 'text-white');
        
        notification.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span>${message}</span>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }, 5000);
    }
});

// Add CSS animations and mobile optimizations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    
    .chat-item:hover {
        transform: translateY(-1px);
    }
    
    .user-item:hover {
        transform: translateY(-1px);
    }

    /* Mobile optimizations */
    @media (max-width: 1023px) {
        .chat-item, .user-item {
            -webkit-tap-highlight-color: transparent;
        }
        
        .chat-item:active, .user-item:active {
            transform: scale(0.98);
        }
    }

    /* Prevent zoom on input focus on iOS */
    @media (max-width: 767px) {
        input[type="text"], 
        input[type="email"], 
        textarea {
            font-size: 16px;
        }
    }

    /* Smooth scrolling for mobile */
    .overflow-y-auto {
        -webkit-overflow-scrolling: touch;
    }
`;
document.head.appendChild(style);
</script>
