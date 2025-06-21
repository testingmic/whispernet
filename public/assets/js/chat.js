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

// Mobile view management
let isMobileView = window.innerWidth < 1024;
let currentView = "chat-list"; // 'chat-list' or 'chat-area'

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

function showChatArea() {
  if (isMobileView) {
    currentView = "chat-area";
    updateMobileView();
  }
  // Load messages for this user
  loadMessages(selectedUserId, selectedChatType);
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

function scrollToBottom() {
    const chatDiv = document.getElementById('messagesArea');
    if (chatDiv) {
      chatDiv.scrollTop = chatDiv.scrollHeight;
    }
}

function closeModal() {
  hideModal(groupCreationModal);
}

// Individual Chat Button
function individualChatBtnClick() {
    hideModal(newChatModal);
    showModal(userSearchModal);
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
  const members = formData.getAll("members[]");

  if (!groupName.trim()) {
    showNotification("Please enter a group name", "error");
    return;
  }

  if (members.length === 0) {
    showNotification("Please select at least one member", "error");
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
  welcomeMessage.classList.add("hidden");
  messagesContainer.classList.remove("hidden");
  messageInputArea.classList.remove("hidden");

  // Create group and load messages
  createGroup(groupName, groupDescription, members);

  hideModal(groupCreationModal);
  this.reset();

  // Reset all checkboxes
  document.querySelectorAll('input[name="members[]"]').forEach((checkbox) => {
    checkbox.checked = false;
  });

  showChatArea();
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

  messageInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter" && !e.shiftKey) {
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();
      handleMessageSubmit();
      return false;
    }
  });

  messageInput.addEventListener("keyup", function (e) {
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
  addMessageToUI(message, "sent", '', messageUUID);

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

      // send the receiver id as an array
      msgPayload.receiver = [loggedInUserId, selectedUserId];

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

function beginChat(roomId, type) {
  selectedChatId = roomId;
  selectedChatType = type;
  let roomInfo = footerArray[roomId];

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

  console.log(roomInfo);
  $(`h3[id="chatTitle"]`).text(roomInfo?.username ?? roomInfo?.full_name);
  $(`p[id="chatStatus"]`).text(roomInfo?.state ?? 'Offline');
  $(`div[id="chatAvatar"]`).html(roomInfo?.username?.charAt(0)?.toUpperCase() ?? '');

  $(`textarea[id="messageInput"]`).focus();
  // Load messages
  loadingMessages(roomId, selectedUserId);
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
  $.get(`${baseUrl}/api/users/search?query=${query}&userUUID=${userUUID}`, function (response) {
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

  $.get(`${baseUrl}/api/chats/messages?roomId=${roomId}&receiverId=${receiverId}&room=${selectedChatType}&token=${AppState.getToken()}&userUUID=${userUUID}`, function (response) {
    if (response.status === "success") {
      messagesContainer.innerHTML = '';
      response.data.forEach((message) => {
          if(message.msgid > mostRecentMessageId) {
              mostRecentMessageId = message.msgid;
          }
          addMessageToUI(message.message, message.type, message.time, message.uuid);
      });

      if(!response.data.length) {
        messagesContainer.innerHTML = `
          <div class="text-center py-8" id="no-message-notification">
              <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
          </div>
        `;
      }
      scrollToBottom();
    }
  }).catch((error) => {
      messagesContainer.innerHTML = `
      <div class="text-center py-8" id="no-message-notification">
          <p class="text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
      </div>`;
  });
  setTimeout(() => {
    $(`div[id="selfDestructMessage"]`).removeClass('hidden');
  }, 1000);
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

  loadingMessages(selectedChatId, selectedUserInfo?.user_id ?? 0);
}

function addMessageToUI(content, type, time = '', uuid = '') {
  const messageDiv = document.createElement("div");
  messageDiv.className = `flex ${
    type === "sent" ? "justify-end" : "justify-start"
  }`;

  messageDiv.innerHTML = `
        <div class="flex items-end space-x-2 max-w-[85%] sm:max-w-[70%]" data-uuid="${uuid}">
            ${
              type === "received"
                ? `
                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300">
                    U
                </div>
            `
                : ""
            }
            <div class="flex flex-col ${
              type === "sent" ? "items-end" : "items-start"
            }">
                <div class="rounded-2xl px-4 py-2 ${
                  type === "sent"
                    ? "bg-blue-500 text-white"
                    : "bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white"
                }">
                    <p class="text-sm break-words">${content}</p>
                </div>
                <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    ${time ? time : new Date().toLocaleTimeString([], {
                      hour: "2-digit",
                      minute: "2-digit",
                    })}
                </span>
            </div>
        </div>
    `;

  messagesContainer.appendChild(messageDiv);
  messagesContainer.scrollTop = messagesContainer.scrollHeight;
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
  // Simulate group creation
  showNotification(`Group "${name}" created successfully!`, "success");

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

// Notification function
function showNotification(message, type = "info") {
  const notification = document.createElement("div");
  notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;

  const bgColor =
    type === "success"
      ? "bg-green-500"
      : type === "error"
      ? "bg-red-500"
      : "bg-blue-500";
  notification.classList.add(bgColor, "text-white");

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
    notification.classList.remove("translate-x-full");
  }, 100);

  setTimeout(() => {
    notification.classList.add("translate-x-full");
    setTimeout(() => {
      notification.remove();
    }, 300);
  }, 5000);
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
  addMessageToUI(messageInput.value.trim(), "sent", '', messageUUID);
  
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
        messageInput.value = "";
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
          files: response.record.media || []
        };
        
        AppState.socketConnect.send({
          type: 'chat',
          data: msgPayload,
        });
        
        scrollToBottom();
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
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
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
