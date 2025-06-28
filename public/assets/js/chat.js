let selectedChatId = 0;
let selectedChatType = 'individual';
let selectedUserId = 0;
let selectedUserInfo = [];
let searchedUsersList = [];
let mostRecentMessageId = 0;
let messageUUID = '';

// Elements
const newChatBtn = document.getElementById("newChatBtn");
const newChatModal = document.getElementById("newChatModal");
const closeNewChatModal = document.getElementById("closeNewChatModal");
const userSearchModal = document.getElementById("userSearchModal");
const closeUserSearchModal = document.getElementById("closeUserSearchModal");
const groupCreationModal = document.getElementById("groupCreationModal");
const closeGroupCreationModal = document.getElementById("closeGroupCreationModal");
const cancelGroupCreation = document.getElementById("cancelGroupCreation");
const groupCreationForm = document.getElementById("groupCreationForm");
const messageForm = document.getElementById("messageForm");
const messageInput = document.getElementById("messageInput");
const charCount = document.getElementById("charCount");
const searchInput = document.getElementById("searchInput");
const userSearchInput = document.getElementById("userSearchInput");
const chatList = document.getElementById("chatList");
const messagesContainer = document.getElementById("messagesContainer");
const welcomeMessage = document.getElementById("welcomeMessage");
const messageInputArea = document.getElementById("messageInputArea");
const chatHeader = document.getElementById("chatHeader");
const chatTitle = document.getElementById("chatTitle");
const chatStatus = document.getElementById("chatStatus");
const chatAvatar = document.getElementById("chatAvatar");
const backToChats = document.getElementById("backToChats");

// Welcome message buttons
const startIndividualChat = document.getElementById("startIndividualChat");
const startGroupChat = document.getElementById("startGroupChat");

// Chat Info Dropdown Elements
const chatInfoBtn = document.getElementById("chatInfoBtn");
const chatInfoDropdown = document.getElementById("chatInfoDropdown");
const shareChatLink = document.getElementById("shareChatLink");
const muteChat = document.getElementById("muteChat");
const blockUser = document.getElementById("blockUser");
const leaveGroup = document.getElementById("leaveGroup");
const deleteChat = document.getElementById("deleteChat");
const muteText = document.getElementById("muteText");
const blockText = document.getElementById("blockText");

// Confirmation Modal Elements
const leaveGroupModal = document.getElementById("leaveGroupModal");
const deleteChatModal = document.getElementById("deleteChatModal");
const blockUserModal = document.getElementById("blockUserModal");
const cancelLeaveGroup = document.getElementById("cancelLeaveGroup");
const confirmLeaveGroup = document.getElementById("confirmLeaveGroup");
const cancelDeleteChat = document.getElementById("cancelDeleteChat");
const confirmDeleteChat = document.getElementById("confirmDeleteChat");
const cancelBlockUser = document.getElementById("cancelBlockUser");
const confirmBlockUser = document.getElementById("confirmBlockUser");
const deleteChatType = document.getElementById("deleteChatType");
const blockUserAction = document.getElementById("blockUserAction");
const confirmBlockUserText = document.getElementById("confirmBlockUserText");

// Mobile view management
let isMobileView = window.innerWidth < 1024;
let currentView = "chat-list";
let newGroupInfo = {};

// Check for mobile view on resize
window.addEventListener("resize", function () {
  isMobileView = window.innerWidth < 1024;
  updateMobileView();
});

function updateMobileView() {
  const chatListContainer = document.querySelector(".w-full.lg\\:w-80");
  const chatAreaContainer = document.querySelector(".flex-1.flex.flex-col");

  if (isMobileView) {
    if (currentView === "chat-list") {
      chatListContainer.classList.remove("hidden");
      chatAreaContainer.classList.add("hidden");
    } else {
      chatListContainer.classList.add("hidden");
      chatAreaContainer.classList.remove("hidden");
    }
  } else {
    chatListContainer.classList.remove("hidden");
    chatAreaContainer.classList.remove("hidden");
  }
}

function showChatArea(type = 'individual') {
  if (isMobileView) {
    currentView = "chat-area";
    updateMobileView();
  }
  // Load messages for this user
  loadMessages(selectedUserId, type);
}

function showChatList() {
  if (isMobileView) {
    currentView = "chat-list";
    updateMobileView();
  }
}

// Initialize mobile view
updateMobileView();

// Modal Management
function showModal(modal) {
  modal.classList.remove("hidden");
  modal.querySelector(".inline-block").classList.add("animate-fadeIn");
}

function hideModal(modal) {
  modal.classList.add("hidden");
  modal.querySelector(".inline-block").classList.remove("animate-fadeIn");
}

// New Chat Button
newChatBtn.addEventListener("click", () => showModal(newChatModal));

// Welcome message buttons
if (startIndividualChat) {
  startIndividualChat.addEventListener("click", () => {
    showModal(userSearchModal);
  });
}

if(isMobileView) {
  $(`button[id="refreshChats"]`).removeClass('hidden');
  $(`button[id="refreshChats"]`).on('click', function() {
    location.reload();
  });
}

if (startGroupChat) {
  startGroupChat.addEventListener("click", () => {
    showModal(groupCreationModal);
  });
}

// Back to chats button (mobile)
if (backToChats) {
  backToChats.addEventListener("click", () => {
    showChatList();
  });
}

// Close modals
[
  closeNewChatModal,
  closeUserSearchModal,
  closeGroupCreationModal,
  cancelGroupCreation,
].forEach((btn) => {
  if (btn) {
    btn.addEventListener("click", () => {
      if (newChatModal.classList.contains("hidden") === false)
        hideModal(newChatModal);
      if (userSearchModal.classList.contains("hidden") === false)
        hideModal(userSearchModal);
      if (groupCreationModal.classList.contains("hidden") === false)
        hideModal(groupCreationModal);
    });
  }
});

// Click outside to close
[newChatModal, userSearchModal, groupCreationModal].forEach((modal) => {
  modal.addEventListener("click", (e) => {
    if (e.target === modal) hideModal(modal);
  });
});

// Confirmation Modal Event Listeners
if (cancelLeaveGroup) {
  cancelLeaveGroup.addEventListener("click", function(e) {
    e.preventDefault();
    hideModal(leaveGroupModal);
  });
}

if (confirmLeaveGroup) {
  confirmLeaveGroup.addEventListener("click", function(e) {
    e.preventDefault();
    hideModal(leaveGroupModal);
    performLeaveGroupAction();
  });
}

if (cancelDeleteChat) {
  cancelDeleteChat.addEventListener("click", function(e) {
    e.preventDefault();
    hideModal(deleteChatModal);
  });
}

if (confirmDeleteChat) {
  confirmDeleteChat.addEventListener("click", function(e) {
    e.preventDefault();
    hideModal(deleteChatModal);
    performDeleteChatAction();
  });
}

if (cancelBlockUser) {
  cancelBlockUser.addEventListener("click", function(e) {
    e.preventDefault();
    hideModal(blockUserModal);
  });
}

if (confirmBlockUser) {
  confirmBlockUser.addEventListener("click", function(e) {
    e.preventDefault();
    hideModal(blockUserModal);
    performBlockUserAction();
  });
}

// Close confirmation modals when clicking outside
[leaveGroupModal, deleteChatModal, blockUserModal].forEach((modal) => {
  if (modal) {
    modal.addEventListener("click", (e) => {
      if (e.target === modal) hideModal(modal);
    });
  }
});

// Chat Info Dropdown Management
if (chatInfoBtn) {
  chatInfoBtn.addEventListener("click", function(e) {
    e.preventDefault();
    e.stopPropagation();
    toggleChatInfoDropdown();
  });
}

// Close dropdown when clicking outside
document.addEventListener("click", function(e) {
  if (chatInfoDropdown && !chatInfoDropdown.contains(e.target) && !chatInfoBtn.contains(e.target)) {
    hideChatInfoDropdown();
  }
});

// Chat Info Dropdown Functions
function toggleChatInfoDropdown() {
  if (chatInfoDropdown.classList.contains("hidden")) {
    showChatInfoDropdown();
  } else {
    hideChatInfoDropdown();
  }
}

function showChatInfoDropdown() {
  if (chatInfoDropdown) {
    chatInfoDropdown.classList.remove("hidden");
    updateDropdownOptions();
  }
}

function hideChatInfoDropdown() {
  if (chatInfoDropdown) {
    chatInfoDropdown.classList.add("hidden");
  }
}

function updateDropdownOptions() {
  // Show/hide options based on chat type
  if (selectedChatType === 'individual') {
    if (blockUser) blockUser.classList.remove("hidden");
    if (leaveGroup) leaveGroup.classList.add("hidden");
  } else if (selectedChatType === 'group') {
    if (blockUser) blockUser.classList.add("hidden");
    if (leaveGroup) leaveGroup.classList.remove("hidden");
  }
  
  // Update button text based on current state
  if (muteText) {
    muteText.textContent = isChatMuted ? "Unmute Notifications" : "Mute Notifications";
  }
  
  if (blockText) {
    blockText.textContent = isUserBlocked ? "Unblock User" : "Block User";
  }
}

// Chat Info Action Handlers
if (shareChatLink) {
  shareChatLink.addEventListener("click", function(e) {
    e.preventDefault();
    shareChatLinkAction();
  });
}

if (muteChat) {
  muteChat.addEventListener("click", function(e) {
    e.preventDefault();
    toggleMuteChat();
  });
}

if (blockUser) {
  blockUser.addEventListener("click", function(e) {
    e.preventDefault();
    toggleBlockUser();
  });
}

if (leaveGroup) {
  leaveGroup.addEventListener("click", function(e) {
    e.preventDefault();
    leaveGroupAction();
  });
}

if (deleteChat) {
  deleteChat.addEventListener("click", function(e) {
    e.preventDefault();
    deleteChatAction();
  });
}

// Chat Action Functions
async function shareChatLinkAction() {
  try {
    if (!selectedChatId || selectedChatId === 0) {
      AppState.showNotification("No chat selected", "error");
      return;
    }
    
    const shareUrl = `/chat/join/${selectedChatId}/${selectedRoomUUID}`;
    
    // Try to copy to clipboard
    if (navigator.clipboard) {
      await navigator.clipboard.writeText(shareUrl);
      AppState.showNotification("Chat link copied to clipboard!", "success");
    } else {
      // Fallback for older browsers
      const textArea = document.createElement("textarea");
      textArea.value = shareUrl;
      document.body.appendChild(textArea);
      textArea.select();
      document.execCommand("copy");
      document.body.removeChild(textArea);
      AppState.showNotification("Chat link copied to clipboard!", "success");
    }
    
    hideChatInfoDropdown();
  } catch (error) {
    console.error("Error sharing chat link:", error);
    AppState.showNotification("Failed to copy chat link", "error");
  }
}

async function toggleMuteChat() {
  try {
    if (!selectedChatId || selectedChatId === 0) {
      AppState.showNotification("No chat selected", "error");
      return;
    }
    
    // For now, just toggle the local state since API endpoint might not exist
    isChatMuted = !isChatMuted;
    updateDropdownOptions();
    
    // Try to call the API if it exists
    try {
      const response = await fetch(`/api/chats/${selectedChatId}/mute`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          muted: isChatMuted
        })
      });
      
      if (response.ok) {
        AppState.showNotification(
          isChatMuted ? "Chat muted" : "Chat unmuted", 
          "success"
        );
      } else {
        // API endpoint doesn't exist or failed, but we've already updated UI
        AppState.showNotification(
          isChatMuted ? "Chat muted (local)" : "Chat unmuted (local)", 
          "info"
        );
      }
    } catch (apiError) {
      // API endpoint doesn't exist, but we've already updated UI
      AppState.showNotification(
        isChatMuted ? "Chat muted (local)" : "Chat unmuted (local)", 
        "info"
      );
    }
    
  } catch (error) {
    AppState.showNotification("Failed to update mute status", "error");
  }
}

async function toggleBlockUser() {
  try {
    if (!selectedChatId || selectedChatId === 0) {
      AppState.showNotification("No chat selected", "error");
      return;
    }
    
    // Update modal content based on current block state
    if (blockUserAction) {
      blockUserAction.textContent = isUserBlocked ? "unblock" : "block";
    }
    
    if (confirmBlockUserText) {
      confirmBlockUserText.textContent = isUserBlocked ? "Unblock User" : "Block User";
    }
    
    // Show confirmation modal instead of using confirm dialog
    showModal(blockUserModal);
    hideChatInfoDropdown();
    
  } catch (error) {
    AppState.showNotification("Failed to show block user dialog", "error");
  }
}

// Actual action function that performs the block/unblock operation
async function performBlockUserAction() {
  try {
    const action = isUserBlocked ? "unblock" : "block";
    
    // For now, just toggle the local state since API endpoint might not exist
    isUserBlocked = !isUserBlocked;
    updateDropdownOptions();
    
    // Try to call the API if it exists
    try {
      const response = await fetch(`/api/chats/${action}/${selectedChatId}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        }
      });
      
      if (response.ok) {
        AppState.showNotification(
          isUserBlocked ? "User blocked" : "User unblocked", 
          "success"
        );
        
        if (isUserBlocked) {
          // Refresh chat list to reflect blocked status
          location.reload();
        }
      } else {
        // API endpoint doesn't exist or failed, but we've already updated UI
        AppState.showNotification(
          isUserBlocked ? "User blocked (local)" : "User unblocked (local)", 
          "info"
        );
      }
    } catch (apiError) {
      // API endpoint doesn't exist, but we've already updated UI
      AppState.showNotification(
        isUserBlocked ? "User blocked (local)" : "User unblocked (local)", 
        "info"
      );
    }
    
  } catch (error) {
    console.error(`Error ${isUserBlocked ? 'unblocking' : 'blocking'} user:`, error);
    AppState.showNotification(`Failed to ${isUserBlocked ? 'unblock' : 'block'} user`, "error");
  }
}

async function leaveGroupAction() {
  try {
    if (!selectedChatId || selectedChatId === 0) {
      AppState.showNotification("No group selected", "error");
      return;
    }
    
    // Show confirmation modal instead of using confirm dialog
    showModal(leaveGroupModal);
    hideChatInfoDropdown();
    
  } catch (error) {
    AppState.showNotification("Failed to show leave group dialog", "error");
  }
}

async function deleteChatAction() {
  try {
    if (!selectedChatId || selectedChatId === 0) {
      AppState.showNotification("No chat selected", "error");
      return;
    }
    
    // Update modal content based on chat type
    if (deleteChatType) {
      deleteChatType.textContent = selectedChatType === 'group' ? 'group' : 'chat';
    }
    
    // Show confirmation modal instead of using confirm dialog
    showModal(deleteChatModal);
    hideChatInfoDropdown();
    
  } catch (error) {
    AppState.showNotification("Failed to show delete chat dialog", "error");
  }
}

// Actual action functions that perform the operations
async function performLeaveGroupAction() {
  try {
    // Try to call the API if it exists
    try {
      const response = await fetch(`/api/chats/leave/${selectedChatId}`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        }
      });
      
      if (response.ok) {
        AppState.showNotification("Left group successfully", "success");
        
        // Return to chat list
        showChatList();
        welcomeMessage.classList.remove("hidden");
        messagesContainer.classList.add("hidden");
        messageInputArea.classList.add("hidden");
        $(`button[id="chatInfoBtn"]`).addClass('hidden');
      } else {
        throw new Error("Failed to leave group");
      }
    } catch (apiError) {
      AppState.showNotification("Failed to leave group", "error");
    }
    
  } catch (error) {
    AppState.showNotification("Failed to leave group", "error");
  }
}

async function performDeleteChatAction() {
  try {
    const action = selectedChatType === 'group' ? 'delete group' : 'delete chat';
    
    // Try to call the API if it exists
    try {
      const response = await fetch(`/api/chats/${selectedChatId}`, {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          token: AppState.getToken(),
          roomId: selectedChatId,
          type: selectedChatType
        })
      });
      
      if (response.ok) {
        AppState.showNotification(`Chat deleted successfully`, "success");
        showChatList();
        welcomeMessage.classList.remove("hidden");
        messagesContainer.classList.add("hidden");
        messageInputArea.classList.add("hidden");
        // $(`div[data-room-id="${selectedChatId}"]`).remove();
        $(`button[id="chatInfoBtn"]`).addClass('hidden');
      } else {
        throw new Error(`Failed to ${action}`);
      }
    } catch (apiError) {
      AppState.showNotification("Failed to delete chat", "error");
    }
    
  } catch (error) {
    AppState.showNotification(`Failed to delete ${selectedChatType}`, "error");
  }
}

function scrollToBottom(smooth = true, duration = 1000) {
    const chatDiv = document.getElementById('messagesArea');
    if (chatDiv) {
        if (smooth) {
            // Smooth scroll with animation
            const startPosition = chatDiv.scrollTop;
            const targetPosition = chatDiv.scrollHeight - chatDiv.clientHeight;
            const distance = targetPosition - startPosition;
            const startTime = performance.now();
            
            function easeOutCubic(t) {
                return 1 - Math.pow(1 - t, 3);
            }
            
            function animateScroll(currentTime) {
                const elapsed = currentTime - startTime;
                const progress = Math.min(elapsed / duration, 1);
                const easedProgress = easeOutCubic(progress);
                
                chatDiv.scrollTop = startPosition + (distance * easedProgress);
                
                if (progress < 1) {
                    requestAnimationFrame(animateScroll);
                }
            }
            
            requestAnimationFrame(animateScroll);
        } else {
            // Instant scroll (fallback)
            chatDiv.scrollTop = chatDiv.scrollHeight;
        }
    }
}

function closeModal() {
  hideModal(groupCreationModal);
}

// Individual Chat Button
function individualChatBtnClick() {
    hideModal(newChatModal);
    showModal(userSearchModal);
    if(!isMobileView) {
      $(`textarea[id="messageInput"]`).focus();
    }
}

function groupChatBtnClick() {
  hideModal(newChatModal);
  showModal(groupCreationModal);
  $(`input[id="groupName"]`).focus();
}

// Group Creation Form
groupCreationForm.addEventListener("submit", function (e) {
  e.preventDefault();

  const formData = new FormData(this);
  const groupName = formData.get("groupName");
  const groupDescription = formData.get("groupDescription");

  if (!groupName.trim()) {
    AppState.showNotification("Please enter a group name", "error");
    return;
  }

  // check if the group name is too long
  if(groupName.length > 60) {
    AppState.showNotification('Group name must be less than 60 characters', 'error');
    return;
  }

  if(groupDescription.length && groupDescription.length > 100) {
    AppState.showNotification('Group description must be less than 100 characters', 'error');
    return;
  }

  // Update chat header
  chatTitle.textContent = groupName;
  chatAvatar.innerHTML = `
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
    `;

  // Show chat area
  welcomeMessage.classList.add("hidden");
  messagesContainer.classList.remove("hidden");
  messageInputArea.classList.remove("hidden");

  // remove the main group chat button
  $(`div[id="mainGroupChatBtn"]`).remove();

  // Create group and load messages
  createGroup(groupName, groupDescription, 0);

  hideModal(groupCreationModal);
  this.reset();

  messagesContainer.innerHTML = `
  <div class="text-center py-8" id="no-message-notification">
      <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
  </div>`;

  showChatArea('group');
});

// Message Input Management
if (messageInput) {
  messageInput.addEventListener("input", function () {
    const length = this.value.length;
    charCount.textContent = length;

    // Auto-resize
    this.style.height = "auto";
    this.style.height = Math.min(this.scrollHeight, 120) + "px";
  });

  // Handle Enter key press - Multiple event listeners for Firefox compatibility
  messageInput.addEventListener("keydown", function (e) {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();
      handleMessageSubmit();
      return false;
    }
  });

  // Additional safety: handle input events
  messageInput.addEventListener("input", function (e) {
    // Prevent any form submission from input events
    if (e.target.form) {
      e.target.form.onsubmit = function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
      };
    }
  });

  // Focus input when chat area is shown on mobile
  if (isMobileView) {
    messageInput.addEventListener("focus", function () {
      // Scroll to bottom to ensure input is visible
      setTimeout(() => {
        window.scrollTo(0, document.body.scrollHeight);
      }, 100);
    });
  }
}

// Message Form Submission
if (messageForm) {
  // Change submit button to regular button to prevent form submission entirely
  const submitButton = messageForm.querySelector('button[type="submit"]');
  if (submitButton) {
    submitButton.type = 'button'; // Change from submit to button
    submitButton.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();
      handleMessageSubmit();
    });
  }

  // Completely disable form submission
  messageForm.onsubmit = function(e) {
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();
    return false;
  };
}

// Separate function to handle message submission
function handleMessageSubmit() {
  const message = messageInput.value.trim();
  messageUUID = AppState.generateUUID();
  // Check if there are files to upload
  if (selectedFiles.length > 0) {
    // Handle file upload with message
    handleFileUpload();
    return;
  }
  
  // Handle text-only message
  if (message.length === 0) return;

  // Add message to UI
  addMessageToUI(message, "sent", '', messageUUID, false, [], loggedInUserId);

  // Clear input
  messageInput.value = "";
  messageInput.style.height = "auto";
  charCount.textContent = "0";

  scrollToBottom();

  let msgPayload = {
    message: message,
    sender: loggedInUserId,
    receiver: selectedUserId,
    type: selectedChatType,
    roomId: selectedChatId,
    timestamp: new Date().getTime(),
    token: AppState.getToken(),
    uuid: messageUUID,
    userUUID: userUUID,
  };
  selectedUserId = parseInt(selectedUserId);
  selectedChatId = parseInt(selectedChatId);
  
  $(`div[id="no-message-notification"]`).addClass('hidden');
  
  $.post(`${baseUrl}/api/chats/send`, msgPayload, function (response) {
    if (response.status === "success") {
      selectedChatId = response.record.roomId;
      mostRecentMessageId = response.record.messageId;
      $(`div[id="no-message-notification"]`).remove();

      msgPayload.roomId = selectedChatId;
      msgPayload.type = 'chat';
      msgPayload.direction = 'sent';
      msgPayload.msgid = mostRecentMessageId;
      msgPayload.media = false;
      msgPayload.msgtype = selectedChatType;

      // send the receiver id as an array
      msgPayload.receiver = [loggedInUserId, selectedUserId];

      if(selectedChatType == 'group') {
        msgPayload.receiver = footerArray[selectedChatId].room.participants;
      }

      AppState.socketConnect.send({
          type: 'chat',
          data: msgPayload,
      });
    }
  }).catch((error) => {
      $(`div[id="no-message-notification"]`).removeClass('hidden');
  });
}

// Search Functionality
if (searchInput) {
  searchInput.addEventListener("input", function () {
    const query = this.value.toLowerCase();
    filterChats(query);
  });
}

function selectUser(userId) {
  // User Selection
  const userName = searchedUsersList?.find(
    (user) => user.user_id === parseInt(userId)
  )?.username;
  selectedUserId = parseInt(userId);

  // Update chat header
  chatTitle.textContent = userName;
  chatStatus.textContent = "Online";
  chatAvatar.innerHTML = userName?.charAt(0)?.toUpperCase() ?? '';

  // Show chat area
  welcomeMessage.classList.add("hidden");
  messagesContainer.classList.remove("hidden");
  messageInputArea.classList.remove("hidden");

  hideModal(userSearchModal);
  showChatArea();
}

function beginChat(roomId, type, roomUUID) {
  selectedChatId = roomId;
  selectedChatType = type;
  let roomInfo = footerArray[roomId];
  selectedRoomUUID = roomUUID;

  if(selectedUserId == roomInfo?.user_id && !isMobileView) {
    return;
  }

  if (isMobileView) {
    currentView = "chat-area";
    updateMobileView();
  }
  // Show chat area
  welcomeMessage.classList.add("hidden");
  messagesContainer.classList.remove("hidden");
  messageInputArea.classList.remove("hidden");

  // Hide modal
  hideModal(userSearchModal);
  selectedUserId = roomInfo?.user_id ?? 0;

  let title = roomInfo?.username ?? roomInfo?.name;

  $(`h3[id="chatTitle"]`).html(`${title}`);
  $(`p[id="chatStatus"]`).html(`${roomInfo?.state ?? 'Offline'} ${type == 'group' ? '|' : ''} ${roomInfo?.particiants ?? ''}`);
  $(`div[id="chatAvatar"]`).html(roomInfo?.username?.charAt(0)?.toUpperCase() ?? '');
  
  if(!isMobileView) {
    $(`textarea[id="messageInput"]`).focus();
  }
  // Load messages
  loadingMessages(roomId, selectedUserId);
}

if(selectedRoom) {
  beginChat(selectedRoom, 'group', selectedRoomUUID);
}

function debounce(fn, delay) {
  let timer;
  return function (...args) {
    clearTimeout(timer);
    timer = setTimeout(() => fn.apply(this, args), delay);
  };
}

const debouncedFilterUsers = debounce(function (query) {
  const individualChats = document.getElementById("individualChats");
  $.get(`${baseUrl}/api/users/search?query=${query}&userUUID=${userUUID}&first_part=true&token=${AppState.getToken()}`, function (response) {
    let users = "";
    $.each(response.users, function (index, user) {
      if (user.user_id !== loggedInUserId) {
        users += `
                <div onclick="return selectUser(${
                  user.user_id
                })" class="user-item p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200 border border-gray-200 dark:border-gray-600" data-user-id="${
          user.user_id
        }" data-user-name="${user.username}">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-semibold">
                            ${user.username.charAt(0).toUpperCase()}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">${
                              user.full_name
                            }</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 truncate">${
                              user.username
                            }</p>
                        </div>
                        <button class="px-3 sm:px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-sm font-semibold rounded-lg hover:from-blue-600 hover:to-purple-700 transition-all duration-200">
                            Chat
                        </button>
                    </div>
                </div>`;
      }
    });
    searchedUsersList = response.users;
    $(`div[id="userList"]`).html(users);
  });
}, 500); // Adjust debounce delay here

if (userSearchInput) {
  // add a debounce to the input
  userSearchInput.addEventListener("input", function () {
    const query = this.value.toLowerCase();
    debouncedFilterUsers(query);
  });
}

function loadingMessages(roomId, receiverId = 0) {

  // Simulate loading messages
  messagesContainer.innerHTML = `
        <div class="flex items-center justify-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        </div>`;

  $(`button[id="chatInfoBtn"]`).removeClass('hidden');
  if(selectedChatType == 'group') {
    $(`button[id="shareChatLink"]`).removeClass('hidden');
  } else {
    $(`button[id="shareChatLink"]`).addClass('hidden');
  }
  $.post(`${baseUrl}/api/chats/messages`, {
    newGroupInfo, roomId, receiverId, room: selectedChatType, token: AppState.getToken(), userUUID: userUUID
  }).then(response => {
    if (response.status === "success") {

      messagesContainer.innerHTML = '';
      
      response.data.forEach((message) => {
          if(message.msgid > mostRecentMessageId) {
              mostRecentMessageId = message.msgid;
          }
          addMessageToUI(message.message, message.type, message.time, message.uuid, message.has_media, message.media, loggedInUserId);
      });

      newGroupInfo = {};
      if((typeof response.record !== 'undefined') && selectedChatType == 'group') {
        selectedChatId = response.record.roomId;
        selectedRoomUUID = response.record.roomUUID;
        $(`p[id="chatStatus"]`).text('1 participant');
      }

      if(!response.data.length) {
        messagesContainer.innerHTML = `
          <div class="text-center py-8" id="no-message-notification">
              <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
          </div>
        `;
      }
    }
  }).catch(error => {
    console.error(error);
  }).catch((error) => {
    messagesContainer.innerHTML = `
    <div class="text-center py-8" id="no-message-notification">
        <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
    </div>`;
  });

  if(!localStorage.getItem(`d${selectedUserId}`)) {
    setTimeout(() => {
      const selfDestructElement = document.getElementById('selfDestructMessage');
      if (selfDestructElement) {
        selfDestructElement.classList.remove('hidden');
        // Add fade-in animation
        selfDestructElement.style.opacity = '0';
        selfDestructElement.style.transform = 'translateY(-10px)';
        selfDestructElement.style.transition = 'all 0.3s ease-out';
        
        setTimeout(() => {
          selfDestructElement.style.opacity = '1';
          selfDestructElement.style.transform = 'translateY(0)';
        }, 50);
      }
    }, 10);
  }

  setTimeout(() => {
    new MediaDisplay();
    scrollToBottom();
  }, 500);
}

// Self-destruct message dismiss functionality
function initializeSelfDestructMessage() {
  const dismissButton = document.getElementById('dismissSelfDestruct');
  const selfDestructElement = document.getElementById('selfDestructMessage');
  if (dismissButton && selfDestructElement) {
    dismissButton.addEventListener('click', function() {
      selfDestructElement.style.opacity = '0';
      selfDestructElement.style.transform = 'translateY(-10px)';
      selfDestructElement.style.transition = 'all 0.3s ease-out';
      setTimeout(() => {
        selfDestructElement.classList.add('hidden');
        // Reset styles
        selfDestructElement.style.opacity = '';
        selfDestructElement.style.transform = '';
        selfDestructElement.style.transition = '';
      }, 300);
      localStorage.setItem(`d${selectedUserId}`, 1);
    });
  }
}

// Helper Functions
function loadMessages(userId, type) {
  selectedChatType = type;
  selectedUserId = parseInt(userId);
  selectedUserInfo = searchedUsersList?.find(
    (user) => user.user_id === selectedUserId
  );

  selectedChatId = selectedUserInfo?.roomId || 0;

  $(`p[id="chatStatus"]`).text(selectedUserInfo?.online_status ?? "Offline");

  loadingMessages(selectedChatId, selectedUserInfo?.user_id ?? 0, type);
}

function addMessageToUI(content, type, time = '', uuid = '', has_media = false, media = [], senderId = 0) {
  const messageDiv = document.createElement("div");
  messageDiv.className = `flex ${
    type === "sent" ? "justify-end" : "justify-start"
  }`;

  mediaContent = '';
  if(has_media) {
    mediaContent = MediaManager.renderMedia(media, true);
  }

  messageDiv.innerHTML = `
        <div class="${type === "joined" ? "w-full" : "flex items-end space-x-2 max-w-[85%] sm:max-w-[70%]"}" data-type="${type}" data-uuid="${uuid}">
            ${
              type === "received"
                ? `
                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300">
                  ${senderId}
                </div>
            `
                : ""
            }
            <div class="flex flex-col ${
              type === "sent" ? "items-end" : (
                type === "joined" ? "items-center" : "items-start"
              )
            }">
                ${content.length > 0 ? `
                  <div class="rounded-2xl px-4 py-2 ${
                    type === "sent"
                      ? "bg-blue-500 text-white"
                      : "bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white"
                  }">
                    ${content.length > 0 ? `<p class="text-sm break-words">${content}</p>` : ''}
                  </div>` : ''
                }
                ${type === "joined" ? '' : 
                    `<span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        ${time ? time : new Date().toLocaleTimeString([], {
                          hour: "2-digit",
                          minute: "2-digit",
                        })}
                    </span>`
                }
                <div class="media-preview-container flex w-full" data-media-uuid="${uuid}">
                  ${mediaContent}
                </div>
            </div>
        </div>
    `;

  messagesContainer.appendChild(messageDiv);
}

function appendMediaToUI(media, uuid) {
  let mediaContent = '';
  mediaContent = MediaManager.renderMedia(media, true);
  $(`div[data-type="sent"] div[class~="media-preview-container"][data-media-uuid="${uuid}"]`).html(mediaContent);

  setTimeout(() => {
    new MediaDisplay();
    scrollToBottom();
  }, 500);
}

function filterChats(query) {
  const chatItems = document.querySelectorAll(".chat-item");
  chatItems.forEach((item) => {
    const name = item.querySelector("p").textContent.toLowerCase();
    if (name.includes(query)) {
      item.style.display = "block";
    } else {
      item.style.display = "none";
    }
  });
}

function createGroup(name, description, members) {
  newGroupInfo = {
    name: name,
    description: description,
    members: members
  };

  // Simulate group creation
  AppState.showNotification(`Group "${name}" created successfully!`, "success");

  // Add the new group to the chat list
  const groupChatItem = document.createElement("div");
  groupChatItem.className =
    "chat-item p-3 rounded-xl hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-all duration-200";
  groupChatItem.setAttribute("data-chat-id", "group-" + Date.now());
  groupChatItem.setAttribute("data-chat-type", "group");

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
  const groupChatsSection = document.querySelector(".space-y-2.mt-4");
  if (groupChatsSection) {
    groupChatsSection.appendChild(groupChatItem);
  }
}

// Add CSS animations and mobile optimizations
const style = document.createElement("style");
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

/* Media Preview Styles */
#mediaPreviewArea {
    animation: fadeIn 0.3s ease-out;
}

#mediaPreviewContainer {
    max-height: 200px;
    overflow-y: auto;
    scrollbar-width: thin;
    scrollbar-color: #cbd5e0 #f7fafc;
}

/* Custom Webkit Scrollbar for Chrome/Safari/Edge */
#mediaPreviewContainer::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

#mediaPreviewContainer::-webkit-scrollbar-track {
    background: linear-gradient(to bottom, #f7fafc, #edf2f7);
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
}

#mediaPreviewContainer::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, #cbd5e0, #a0aec0);
    border-radius: 10px;
    border: 1px solid #718096;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    transition: all 0.2s ease;
}

#mediaPreviewContainer::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, #a0aec0, #718096);
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    transform: scale(1.05);
}

#mediaPreviewContainer::-webkit-scrollbar-thumb:active {
    background: linear-gradient(to bottom, #718096, #4a5568);
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
}

#mediaPreviewContainer::-webkit-scrollbar-corner {
    background: #f7fafc;
    border-radius: 10px;
}

/* Dark mode support for scrollbar */
@media (prefers-color-scheme: dark) {
    #mediaPreviewContainer {
        scrollbar-color: #4a5568 #2d3748;
    }
    
    #mediaPreviewContainer::-webkit-scrollbar-track {
        background: linear-gradient(to bottom, #2d3748, #1a202c);
        border: 1px solid #4a5568;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
    }
    
    #mediaPreviewContainer::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #4a5568, #2d3748);
        border: 1px solid #718096;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
    }
    
    #mediaPreviewContainer::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #2d3748, #1a202c);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.5);
    }
    
    #mediaPreviewContainer::-webkit-scrollbar-thumb:active {
        background: linear-gradient(to bottom, #1a202c, #171923);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.5);
    }
    
    #mediaPreviewContainer::-webkit-scrollbar-corner {
        background: #2d3748;
    }
}

/* Firefox scrollbar styling */
@supports (scrollbar-color: red blue) {
    #mediaPreviewContainer {
        scrollbar-width: thin;
        scrollbar-color: #cbd5e0 #f7fafc;
    }
    
    @media (prefers-color-scheme: dark) {
        #mediaPreviewContainer {
            scrollbar-color: #4a5568 #2d3748;
        }
    }
}

/* Smooth scrolling behavior */
#mediaPreviewContainer {
    scroll-behavior: smooth;
    -webkit-overflow-scrolling: touch;
}

/* Custom scrollbar for mobile devices */
@media (max-width: 768px) {
    #mediaPreviewContainer::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    
    #mediaPreviewContainer::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    
    #mediaPreviewContainer::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 8px;
    }
    
    #mediaPreviewContainer::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }
    
    @media (prefers-color-scheme: dark) {
        #mediaPreviewContainer::-webkit-scrollbar-track {
            background: #374151;
        }
        
        #mediaPreviewContainer::-webkit-scrollbar-thumb {
            background: #6b7280;
        }
        
        #mediaPreviewContainer::-webkit-scrollbar-thumb:hover {
            background: #9ca3af;
        }
    }
}

/* Remove button styles */
.remove-file-btn {
    transition: all 0.2s ease;
}

.remove-file-btn:hover {
    transform: scale(1.1);
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

// File upload functionality with preview
const attachButton = document.getElementById('attachButton');
const fileInput = document.getElementById('fileInput');
const mediaPreviewArea = document.getElementById('mediaPreviewArea');
const mediaPreviewContainer = document.getElementById('mediaPreviewContainer');
const clearAllMediaBtn = document.getElementById('clearAllMedia');

// Store selected files for preview
let selectedFiles = [];

if (attachButton && fileInput) {
  // Handle attach button click
  attachButton.addEventListener('click', function() {
    fileInput.click();
  });
  
  // Handle file selection
  fileInput.addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    if (files.length > 0) {
      addFilesToPreview(files);
    }
  });
}

// Handle clear all media
if (clearAllMediaBtn) {
  clearAllMediaBtn.addEventListener('click', function() {
    clearAllMedia();
  });
}

// Add files to preview
function addFilesToPreview(files) {
  files.forEach(file => {
    // Check if file is already selected
    const isDuplicate = selectedFiles.some(existingFile => 
      existingFile.name === file.name && existingFile.size === file.size
    );
    
    if (!isDuplicate) {
      selectedFiles.push(file);
      createPreviewItem(file);
    }
  });
  
  // Show preview area if there are files
  if (selectedFiles.length > 0) {
    mediaPreviewArea.classList.remove('hidden');
  }
}

// Create preview item
function createPreviewItem(file) {
  const previewItem = document.createElement('div');
  previewItem.className = 'relative group';
  previewItem.setAttribute('data-file-name', file.name);
  
  let previewContent = '';
  
  if (file.type.startsWith('image/')) {
    previewContent = `
      <img src="${URL.createObjectURL(file)}" alt="${file.name}" 
           class="w-full h-20 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
    `;
  } else if (file.type.startsWith('video/')) {
    previewContent = `
      <video class="w-full h-20 object-cover rounded-lg border border-gray-200 dark:border-gray-600">
        <source src="${URL.createObjectURL(file)}" type="${file.type}">
      </video>
      <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-30 rounded-lg">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
      </div>
    `;
  }
  
  previewItem.innerHTML = `
    ${previewContent}
    <button type="button" class="remove-file-btn absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-200 hover:bg-red-600">
      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
      </svg>
    </button>
    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400 truncate">${file.name}</div>
  `;
  
  // Add remove functionality
  const removeBtn = previewItem.querySelector('.remove-file-btn');
  removeBtn.addEventListener('click', function() {
    removeFileFromPreview(file);
  });
  
  mediaPreviewContainer.appendChild(previewItem);
}

// Remove file from preview
function removeFileFromPreview(file) {
  // Remove from selectedFiles array
  selectedFiles = selectedFiles.filter(f => 
    !(f.name === file.name && f.size === file.size)
  );
  
  // Remove preview item
  const previewItem = mediaPreviewContainer.querySelector(`[data-file-name="${file.name}"]`);
  if (previewItem) {
    previewItem.remove();
  }
  
  // Hide preview area if no files left
  if (selectedFiles.length === 0) {
    mediaPreviewArea.classList.add('hidden');
  }
}

// Clear all media
function clearAllMedia() {
  selectedFiles = [];
  mediaPreviewContainer.innerHTML = '';
  mediaPreviewArea.classList.add('hidden');
  fileInput.value = '';
}

// Handle file upload with preview
function handleFileUpload() {
  if (selectedFiles.length === 0) {
    return; // No files to upload
  }
  
  const formData = new FormData();
  
  // Add files to FormData
  selectedFiles.forEach(file => {
    formData.append('media[]', file);
  });
  
  // Add other necessary data
  formData.append('message', messageInput.value.trim());
  formData.append('sender', loggedInUserId);
  formData.append('receiver', selectedUserId);
  formData.append('type', selectedChatType);
  formData.append('roomId', selectedChatId);
  formData.append('timestamp', new Date().getTime());
  formData.append('token', AppState.getToken());
  formData.append('uuid', messageUUID);
  formData.append('userUUID', userUUID);
  
  // Show loading state
  const submitButton = document.querySelector('#messageForm button[data-type="submit-message"]');
  const originalContent = submitButton.innerHTML;
  submitButton.innerHTML = `
    <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
    </svg>
  `;
  submitButton.disabled = true;

  // Add message to UI
  addMessageToUI(messageInput.value.trim(), "sent", '', messageUUID, false, [], loggedInUserId);
  
  // Upload files
  $.ajax({
    url: `${baseUrl}/api/chats/send`,
    type: 'POST',
    data: formData,
    processData: false,
    contentType: false,
    success: function(response) {
      if (response.status === "success") {
        // Add message to UI with media
        // addMessageWithMediaToUI(messageInput.value.trim(), "sent", selectedFiles);
        
        // Clear input and preview
        clearAllMedia();
        
        // Update chat data
        selectedChatId = response.record.roomId;
        mostRecentMessageId = response.record.messageId;
        
        // Send via WebSocket
        let msgPayload = {
          message: messageInput.value.trim(),
          sender: loggedInUserId,
          receiver: [loggedInUserId, selectedUserId],
          type: 'chat',
          roomId: selectedChatId,
          timestamp: new Date().getTime(),
          media: response.record.media || [],
          direction: 'sent',
          msgid: mostRecentMessageId,
          media: true,
          uuid: messageUUID,
          userUUID: userUUID,
          msgtype: selectedChatType,
          files: response.record.media || []
        };

        if(selectedChatType == 'group') {
          msgPayload.receiver = footerArray[selectedChatId].room.participants;
        }

        appendMediaToUI(response.record.media || [], messageUUID);

        messageInput.value = "";
        
        AppState.socketConnect.send({
          type: 'chat',
          data: msgPayload,
        });
      }
    },
    error: function(xhr, status, error) {
      console.error('File upload failed:', error);
      // Show error message
      addMessageToUI('Failed to upload file(s)', "error");
    },
    complete: function() {
      // Restore button state
      submitButton.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
      </svg>`;
      submitButton.disabled = false;
    }
  });
}

// Enhanced addMessageToUI function to handle media
function addMessageWithMediaToUI(message, type, files = null) {
  const messageElement = document.createElement('div');
  messageElement.className = `flex ${type === 'sent' ? 'justify-end' : 'justify-start'} mb-4`;
  
  let mediaHTML = '';
  if (files && files.length > 0) {
    mediaHTML = '<div class="mt-2 space-y-2">';
    for (let i = 0; i < files.length; i++) {
      const file = files[i];
      if (file && file.type && file.type.startsWith('image/')) {
        mediaHTML += `
          <div class="max-w-xs">
            <img src="${URL.createObjectURL(file)}" alt="Uploaded image" class="rounded-lg max-w-full h-auto">
          </div>
        `;
      } else if (file && file.type && file.type.startsWith('video/')) {
        mediaHTML += `
          <div class="max-w-xs">
            <video controls class="rounded-lg max-w-full h-auto">
              <source src="${URL.createObjectURL(file)}" type="${file.type}">
              Your browser does not support the video tag.
            </video>
          </div>
        `;
      }
    }
    mediaHTML += '</div>';
  }
  
  messageElement.innerHTML = `
    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg ${
      type === 'sent' 
        ? 'bg-gradient-to-r from-blue-500 to-purple-600 text-white' 
        : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white'
    }">
      <p class="text-sm">${message || ''}</p>
      ${mediaHTML}
      <p class="text-xs opacity-75 mt-1">${new Date().toLocaleTimeString()}</p>
    </div>
  `;
  
  messagesContainer.appendChild(messageElement);
}

// Initialize self-destruct message functionality when the page loads
document.addEventListener('DOMContentLoaded', function() {
  initializeSelfDestructMessage();
});

// Chat state variables
let isChatMuted = false;
let isUserBlocked = false;
