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