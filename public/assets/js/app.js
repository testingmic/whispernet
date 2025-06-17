// PWA Service Worker Registration
if ('serviceWorker' in navigator && userLoggedin) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register(`${baseUrl}/assets/js/sw.js`)
            .then(registration => {
                console.log('ServiceWorker registration successful');
            })
            .catch(err => {
                console.log('ServiceWorker registration failed: ', err);
            });
    });
}

// App State Management
const AppState = {
    user: null,
    location: null,
    theme: 'light',
    notifications: [],
    isOnline: navigator.onLine,
    init() {
        this.loadUser();
        this.loadTheme();
        this.setupEventListeners();
        this.checkLocation();
    },
    loadUser() {
        // Load user data from localStorage or API
        const userData = localStorage.getItem('user');
        if (userData) {
            this.user = JSON.parse(userData);
        }
    },
    getToken() {
        return localStorage.getItem('token');
    },
    loadTheme() {
        const theme = localStorage.getItem('theme') || 'light';
        this.setTheme(theme);
    },
    setTheme(theme) {
        this.theme = theme;
        document.documentElement.classList.toggle('dark', theme === 'dark');
        localStorage.setItem('theme', theme);
    },
    setupEventListeners() {
        // Online/Offline status
        window.addEventListener('online', () => this.isOnline = true);
        window.addEventListener('offline', () => this.isOnline = false);

        // Theme toggle
        const themeToggle = document.querySelector('.theme-toggle');
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                this.setTheme(this.theme === 'light' ? 'dark' : 'light');
            });
        }
    },
    async checkLocation() {
        if ('geolocation' in navigator && userLoggedin) {
            try {
                const position = await new Promise((resolve, reject) => {
                    navigator.geolocation.getCurrentPosition(resolve, reject);
                });
                this.location = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };
                this.updateLocationUI();
            } catch (error) {
                console.error('Error getting location:', error);
                this.showNotification('Please enable location services to see local posts', 'error');
            }
        }
    },
    updateLocationUI() {
        const locationElement = document.querySelector('.location-display');
        if (locationElement && this.location) {
            locationElement.textContent = `${this.location.latitude.toFixed(4)}, ${this.location.longitude.toFixed(4)}`;
        }
    },
    showNotification(message, type = 'info') {
        const notification = {
            id: Date.now(),
            message,
            type
        };
        this.notifications.push(notification);
        this.renderNotification(notification);
    },
    renderNotification(notification) {
        const toast = document.createElement('div');
        toast.className = `toast notification-${notification.type}`;
        toast.textContent = notification.message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
            this.notifications = this.notifications.filter(n => n.id !== notification.id);
        }, 3000);
    }
};

// Chat Manager
const ChatManager = {
    init() {
        // Only initialize chat features if we're on a chat page
        if (!document.querySelector('.chat-container')) return;

        this.setupChatList();
        this.setupMessageInput();
        this.setupMediaUpload();
        this.setupAudioRecording();
        this.setupEmojiPicker();
    },

    setupChatList() {
        const chatItems = document.querySelectorAll('.chat-item');
        const chatList = document.querySelector('.chat-list');
        const chatView = document.querySelector('.chat-view');
        const backButton = document.querySelector('.back-button');

        if (!chatItems.length || !chatList || !chatView) return;

        chatItems.forEach(item => {
            item.addEventListener('click', () => {
                // Hide chat list and show chat view
                chatList.classList.add('hidden');
                chatView.classList.remove('hidden');
                
                // Update active state
                chatItems.forEach(i => i.classList.remove('bg-gray-100', 'dark:bg-gray-700'));
                item.classList.add('bg-gray-100', 'dark:bg-gray-700');
                
                // Update chat header
                const name = item.querySelector('.font-medium').textContent;
                const status = item.querySelector('.text-xs').textContent;
                document.querySelector('.chat-header h2').textContent = name;
                document.querySelector('.chat-header p').textContent = status;
            });
        });

        if (backButton) {
            backButton.addEventListener('click', () => {
                chatList.classList.remove('hidden');
                chatView.classList.add('hidden');
            });
        }
    },

    setupMessageInput() {
        const messageForm = document.querySelector('#messageForm');
        const messageInput = document.querySelector('#messageInput');
        const submitButton = document.querySelector('#messageSubmit');

        if (!messageForm || !messageInput || !submitButton) return;

        // Auto-resize textarea
        messageInput.addEventListener('input', () => {
            messageInput.style.height = 'auto';
            messageInput.style.height = messageInput.scrollHeight + 'px';
        });

        // Enable/disable submit button based on input
        messageInput.addEventListener('input', () => {
            submitButton.disabled = !messageInput.value.trim();
        });

        // Handle form submission
        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            try {
                submitButton.disabled = true;
                const response = await fetch('/api/chat/message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message })
                });

                if (!response.ok) throw new Error('Failed to send message');

                // Add message to UI
                this.addMessageToUI({
                    content: message,
                    is_sent: true,
                    created_at: new Date().toISOString()
                });

                // Clear input
                messageInput.value = '';
                messageInput.style.height = 'auto';
                submitButton.disabled = true;
            } catch (error) {
                console.error('Error sending message:', error);
                NotificationManager.show('Failed to send message', 'error');
            } finally {
                submitButton.disabled = false;
            }
        });
    },

    setupMediaUpload() {
        const mediaButton = document.querySelector('#mediaButton');
        const mediaInput = document.querySelector('#mediaInput');
        const mediaPreview = document.querySelector('#mediaPreview');

        if (!mediaButton || !mediaInput || !mediaPreview) return;

        mediaButton.addEventListener('click', () => {
            mediaInput.click();
        });

        mediaInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (!file) return;

            // Show preview
            const reader = new FileReader();
            reader.onload = (e) => {
                if (file.type.startsWith('image/')) {
                    mediaPreview.innerHTML = `
                        <img src="${e.target.result}" class="max-h-48 rounded-lg" alt="Preview">
                    `;
                } else if (file.type.startsWith('video/')) {
                    mediaPreview.innerHTML = `
                        <video src="${e.target.result}" class="max-h-48 rounded-lg" controls></video>
                    `;
                }
                mediaPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        });
    },

    setupAudioRecording() {
        const recordButton = document.querySelector('#recordButton');
        const audioRecorder = document.querySelector('#audioRecorder');
        const stopButton = document.querySelector('#stopButton');
        const audioPreview = document.querySelector('#audioPreview');

        if (!recordButton || !audioRecorder || !stopButton || !audioPreview) return;

        let mediaRecorder;
        let audioChunks = [];

        recordButton.addEventListener('click', async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                
                mediaRecorder.addEventListener('dataavailable', (e) => {
                    audioChunks.push(e.data);
                });

                mediaRecorder.addEventListener('stop', () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    const audioUrl = URL.createObjectURL(audioBlob);
                    
                    audioPreview.innerHTML = `
                        <audio src="${audioUrl}" controls class="w-full"></audio>
                    `;
                    audioPreview.classList.remove('hidden');
                });

                mediaRecorder.start();
                recordButton.classList.add('hidden');
                audioRecorder.classList.remove('hidden');
            } catch (error) {
                console.error('Error starting recording:', error);
                NotificationManager.show('Failed to start recording', 'error');
            }
        });

        stopButton.addEventListener('click', () => {
            mediaRecorder.stop();
            recordButton.classList.remove('hidden');
            audioRecorder.classList.add('hidden');
        });
    },

    setupEmojiPicker() {
        const emojiButton = document.querySelector('#emojiButton');
        const emojiPicker = document.querySelector('#emojiPicker');
        const messageInput = document.querySelector('#messageInput');

        if (!emojiButton || !emojiPicker || !messageInput) return;

        emojiButton.addEventListener('click', () => {
            emojiPicker.classList.toggle('hidden');
        });

        // Close emoji picker when clicking outside
        document.addEventListener('click', (e) => {
            if (!emojiButton.contains(e.target) && !emojiPicker.contains(e.target)) {
                emojiPicker.classList.add('hidden');
            }
        });

        // Add emoji to input
        emojiPicker.addEventListener('click', (e) => {
            if (e.target.classList.contains('emoji')) {
                const emoji = e.target.textContent;
                const start = messageInput.selectionStart;
                const end = messageInput.selectionEnd;
                const text = messageInput.value;
                messageInput.value = text.substring(0, start) + emoji + text.substring(end);
                messageInput.focus();
                messageInput.selectionStart = messageInput.selectionEnd = start + emoji.length;
            }
        });
    },

    addMessageToUI(message) {
        const messagesContainer = document.querySelector('.messages-container');
        if (!messagesContainer) return;

        const messageElement = document.createElement('div');
        messageElement.className = `flex ${message.is_sent ? 'justify-end' : 'justify-start'} mb-4`;
        
        messageElement.innerHTML = `
            <div class="flex items-end ${message.is_sent ? 'flex-row-reverse' : 'flex-row'}">
                <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center mr-2">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">
                        ${message.is_sent ? 'You' : 'User'[0]}
                    </span>
                </div>
                <div class="max-w-[70%] ${message.is_sent ? 'bg-blue-600 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'} rounded-lg px-4 py-2">
                    <p class="text-sm">${message.content}</p>
                    <span class="text-xs ${message.is_sent ? 'text-blue-200' : 'text-gray-500 dark:text-gray-400'} mt-1 block">
                        ${new Date(message.created_at).toLocaleTimeString()}
                    </span>
                </div>
            </div>
        `;

        messagesContainer.appendChild(messageElement);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
};

// Post Management
const PostManager = {
    posts: [],
    currentPage: 1,
    isLoading: false,
    init() {
        this.setupInfiniteScroll();
        this.setupPostInteractions();
    },
    setupInfiniteScroll() {
        const options = {
            root: null,
            rootMargin: '0px',
            threshold: 1.0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !this.isLoading) {
                    this.loadMorePosts();
                }
            });
        }, options);

        const sentinel = document.querySelector('.scroll-sentinel');
        if (sentinel) {
            observer.observe(sentinel);
        }
    },
    setupPostInteractions() {
        document.addEventListener('click', (e) => {
            if (e.target.matches('.vote-button')) {
                this.handleVote(e.target);
            } else if (e.target.matches('.report-button')) {
                this.handleReport(e.target);
            }
        });
    },
    async loadMorePosts() {
        if (this.isLoading) return;
        this.isLoading = true;
        try {
            const response = await fetch(`${baseUrl}/api/posts?page=${this.currentPage}`);
            const data = await response.json();
            this.posts = [...this.posts, ...data.posts];
            this.renderPosts(data.posts);
            this.currentPage++;
        } catch (error) {
            console.error('Error loading posts:', error);
            AppState.showNotification('Error loading posts', 'error');
        } finally {
            this.isLoading = false;
        }
    },
    renderPosts(posts) {
        const container = document.querySelector('.posts-container');
        if (!container) return;

        posts.forEach(post => {
            const postElement = this.createPostElement(post);
            container.appendChild(postElement);
        });
    },
    createPostElement(post) {
        const div = document.createElement('div');
        div.className = 'post-card bg-white rounded-lg shadow-sm p-4 mb-4';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-sm">AN</span>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">Anonymous User</div>
                        <div class="text-xs text-gray-500">${this.formatTimestamp(post.created_at)}</div>
                    </div>
                </div>
                <button class="report-button text-gray-400 hover:text-gray-500">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                    </svg>
                </button>
            </div>
            <p class="text-gray-800 mb-3">${post.content}</p>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button class="vote-button flex items-center space-x-1 text-gray-500 hover:text-blue-500" data-post-id="${post.id}" data-vote="up">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                        <span>${post.upvotes}</span>
                    </button>
                    <button class="vote-button flex items-center space-x-1 text-gray-500 hover:text-red-500" data-post-id="${post.id}" data-vote="down">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 10v2a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/>
                        </svg>
                        <span>${post.downvotes}</span>
                    </button>
                </div>
            </div>
        `;
        return div;
    },
    formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return `${Math.floor(diff / 60000)}m ago`;
        if (diff < 86400000) return `${Math.floor(diff / 3600000)}h ago`;
        return date.toLocaleDateString();
    },
    async handleVote(button) {
        const postId = button.dataset.postId;
        const voteType = button.dataset.vote;
        try {
            const response = await fetch(`${baseUrl}/api/posts/${postId}/vote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ vote: voteType })
            });
            const data = await response.json();
            this.updateVoteCounts(postId, data);
        } catch (error) {
            console.error('Error voting:', error);
            AppState.showNotification('Error submitting vote', 'error');
        }
    },
    updateVoteCounts(postId, data) {
        const postElement = document.querySelector(`[data-post-id="${postId}"]`).closest('.post-card');
        if (postElement) {
            const upvoteCount = postElement.querySelector('[data-vote="up"] span');
            const downvoteCount = postElement.querySelector('[data-vote="down"] span');
            if (upvoteCount) upvoteCount.textContent = data.upvotes;
            if (downvoteCount) downvoteCount.textContent = data.downvotes;
        }
    },
    async handleReport(button) {
        const postId = button.closest('.post-card').querySelector('[data-post-id]').dataset.postId;
        try {
            await fetch(`${baseUrl}/api/posts/${postId}/report`, {
                method: 'POST'
            });
            AppState.showNotification('Post reported successfully', 'success');
        } catch (error) {
            console.error('Error reporting post:', error);
            AppState.showNotification('Error reporting post', 'error');
        }
    }
};

// Authentication Manager
const AuthManager = {
    init() {
        this.setupAuthForms();
        this.setupPasswordReset();
    },

    setupAuthForms() {
        // Login Form Handler
        $('#loginForm').on('submit', (e) => {
            e.preventDefault();
            this.handleLogin();
        });

        // Signup Form Handler
        $('#signupForm').on('submit', (e) => {
            e.preventDefault();
            this.handleSignup();
        });
    },

    setupPasswordReset() {
        // Forgot Password Form Handler
        $('#forgotPasswordForm').on('submit', (e) => {
            e.preventDefault();
            this.handleForgotPassword();
        });
    },

    async handleLogin() {
        const email = $('#email').val();
        const password = $('#password').val();
        const rememberMe = $('#remember_me').is(':checked');

        try {
            const response = await $.ajax({
                url: `${baseUrl}/api/auth/login`,
                method: 'POST',
                data: {
                    email,
                    password,
                    webapp: true,
                    remember_me: rememberMe
                }
            });

            if (response.success) {
                // Store user data and token
                localStorage.setItem('user', JSON.stringify(response.data));
                localStorage.setItem('token', response.data.token);
                
                // Update AppState
                AppState.user = response.data;
                
                // Show success message
                AppState.showNotification('Login successful!', 'success');
                
                // Redirect to feed
                window.location.href = `${baseUrl}/feed`;
            }
        } catch (error) {
            console.error('Login error:', error);
            AppState.showNotification(error.responseJSON?.message || 'Login failed. Please try again.', 'error');
        }
    },

    async handleSignup() {
        const fullName = $('#full_name').val();
        const username = $('#username').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const confirmPassword = $('#confirm_password').val();
        const termsAccepted = $('#terms').is(':checked');

        // Validate passwords match
        if (password !== confirmPassword) {
            AppState.showNotification('Passwords do not match', 'error');
            return;
        }

        // Validate terms acceptance
        if (!termsAccepted) {
            AppState.showNotification('Please accept the terms and conditions', 'error');
            return;
        }

        try {
            const response = await $.ajax({
                url: `${baseUrl}/api/auth/signup`,
                method: 'POST',
                data: {
                    full_name: fullName,
                    username,
                    email,
                    password,
                    terms_accepted: termsAccepted
                }
            });

            if (response.success) {
                AppState.showNotification('Account created successfully! Please check your email to verify your account.', 'success');
                // Redirect to login page
                window.location.href = `${baseUrl}/login`;
            }
        } catch (error) {
            console.error('Signup error:', error);
            AppState.showNotification(error.responseJSON?.message || 'Signup failed. Please try again.', 'error');
        }
    },

    async handleForgotPassword() {
        const email = $('#email').val();

        try {
            const response = await $.ajax({
                url: `${baseUrl}/api/auth/forgot-password`,
                method: 'POST',
                data: { email }
            });

            if (response.success) {
                AppState.showNotification('Password reset instructions have been sent to your email.', 'success');
                // Clear the form
                $('#forgotPasswordForm')[0].reset();
            }
        } catch (error) {
            console.error('Forgot password error:', error);
            AppState.showNotification(error.responseJSON?.message || 'Failed to process request. Please try again.', 'error');
        }
    },

    async resetPassword(token, newPassword, confirmPassword) {
        // Validate passwords match
        if (newPassword !== confirmPassword) {
            AppState.showNotification('Passwords do not match', 'error');
            return;
        }

        try {
            const response = await $.ajax({
                url: `${baseUrl}/api/auth/reset-password`,
                method: 'POST',
                data: {
                    token,
                    password: newPassword
                }
            });

            if (response.success) {
                AppState.showNotification('Password has been reset successfully!', 'success');
                // Redirect to login page
                window.location.href = `${baseUrl}/login`;
            }
        } catch (error) {
            console.error('Reset password error:', error);
            AppState.showNotification(error.responseJSON?.message || 'Failed to reset password. Please try again.', 'error');
        }
    },

    logout() {
        // Clear local storage
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        
        // Update AppState
        AppState.user = null;
        
        // Show notification
        AppState.showNotification('Logged out successfully', 'success');
        
        // Redirect to login page
        window.location.href = `${baseUrl}/login`;
    }
};

// Post Creation Manager
const PostCreationManager = {
    init() {
        // Only initialize if we're on a page with post creation
        if (document.getElementById('createPostForm')) {
            this.setupPostCreation();
            this.setupMediaUpload();
            this.setupTags();
            this.setupRichText();
        }
    },

    setupPostCreation() {
        const createPostForm = document.getElementById('createPostForm');
        const createPostBtn = document.getElementById('createPostBtn');
        const postContent = document.getElementById('postContent');
        const postButton = document.getElementById('postButton');
        const mediaPreview = document.getElementById('mediaPreview');
        const tagsInput = document.getElementById('tagsInput');
        const tagsContainer = document.getElementById('tagsContainer');

        if (!createPostForm || !createPostBtn || !postContent || !postButton) return;

        // Toggle post creation form
        createPostBtn.addEventListener('click', () => {
            createPostForm.classList.toggle('hidden');
            if (!createPostForm.classList.contains('hidden')) {
                postContent.focus();
            }
        });

        // Enable/disable post button based on content
        postContent.addEventListener('input', () => {
            postButton.disabled = !postContent.value.trim();
        });

        // Handle form submission
        createPostForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            await this.handlePostCreation();
        });
    },

    setupMediaUpload() {
        const mediaInput = document.getElementById('mediaInput');
        const mediaPreview = document.getElementById('mediaPreview');
        const removeMediaBtn = document.getElementById('removeMediaBtn');

        if (!mediaInput || !mediaPreview || !removeMediaBtn) return;

        mediaInput.addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        mediaPreview.innerHTML = `<img src="${e.target.result}" alt="Media Preview" class="max-w-full h-auto">`;
                    };
                    reader.readAsDataURL(file);
                } else if (file.type.startsWith('video/')) {
                    // Handle video preview
                    const video = document.createElement('video');
                    video.src = URL.createObjectURL(file);
                    video.controls = true;
                    mediaPreview.innerHTML = '';
                    mediaPreview.appendChild(video);
                }
            }
        });

        removeMediaBtn.addEventListener('click', () => {
            mediaPreview.innerHTML = '';
            mediaInput.value = '';
        });
    },

    setupTags() {
        const tagsInput = document.getElementById('tagsInput');
        const tagsContainer = document.getElementById('tagsContainer');

        if (!tagsInput || !tagsContainer) return;

        const tags = new Set();

        tagsInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ',') {
                e.preventDefault();
                const tag = tagsInput.value.trim();
                if (tag && !tags.has(tag)) {
                    this.addTag(tag);
                    tags.add(tag);
                    tagsInput.value = '';
                }
            }
        });

        tagsInput.addEventListener('blur', () => {
            const tag = tagsInput.value.trim();
            if (tag && !tags.has(tag)) {
                this.addTag(tag);
                tags.add(tag);
                tagsInput.value = '';
            }
        });
    },

    addTag(tag) {
        const tagsContainer = document.getElementById('tagsContainer');
        const tagElement = document.createElement('div');
        tagElement.className = 'inline-flex items-center px-2 py-1 rounded-full text-sm bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        tagElement.innerHTML = `
            ${tag}
            <button type="button" class="ml-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;

        tagElement.querySelector('button').addEventListener('click', () => {
            tagElement.remove();
            tags.delete(tag);
        });

        tagsContainer.insertBefore(tagElement, document.getElementById('tagsInput'));
    },

    setupRichText() {
        const postContent = document.getElementById('postContent');
        const boldBtn = document.getElementById('boldBtn');
        const italicBtn = document.getElementById('italicBtn');
        const underlineBtn = document.getElementById('underlineBtn');

        if (!postContent || !boldBtn || !italicBtn || !underlineBtn) return;

        const toolbar = document.createElement('div');
        toolbar.className = 'rich-text-toolbar';

        const boldBtnElement = document.createElement('button');
        boldBtnElement.className = 'btn btn-sm btn-outline btn-primary';
        boldBtnElement.textContent = 'B';
        boldBtnElement.addEventListener('click', () => {
            document.execCommand('bold', false, null);
            postContent.focus();
        });

        const italicBtnElement = document.createElement('button');
        italicBtnElement.className = 'btn btn-sm btn-outline btn-primary';
        italicBtnElement.textContent = 'I';
        italicBtnElement.addEventListener('click', () => {
            document.execCommand('italic', false, null);
            postContent.focus();
        });

        const underlineBtnElement = document.createElement('button');
        underlineBtnElement.className = 'btn btn-sm btn-outline btn-primary';
        underlineBtnElement.textContent = 'U';
        underlineBtnElement.addEventListener('click', () => {
            document.execCommand('underline', false, null);
            postContent.focus();
        });

        toolbar.appendChild(boldBtnElement);
        toolbar.appendChild(italicBtnElement);
        toolbar.appendChild(underlineBtnElement);

        postContent.parentNode.insertBefore(toolbar, postContent.nextSibling);
    },

    async handlePostCreation() {
        const form = document.getElementById('createPostForm');
        const formData = new FormData(form);
        const mediaFile = document.getElementById('mediaInput').files[0];
        const tags = Array.from(document.querySelectorAll('#tagsContainer > div')).map(tag => tag.textContent.trim());

        if (mediaFile) {
            formData.append('media', mediaFile);
        }
        formData.append('tags', JSON.stringify(tags));

        try {
            const response = await fetch(`${baseUrl}/api/posts`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Authorization': `Bearer ${AppState.getToken()}`
                }
            });

            const data = await response.json();

            if (data.success) {
                AppState.showNotification('Post created successfully!', 'success');
                form.reset();
                document.getElementById('createPostForm').classList.add('hidden');
                document.getElementById('mediaPreview').classList.add('hidden');
                document.getElementById('tagsContainer').innerHTML = '<input type="text" id="tagsInput" class="flex-1 min-w-[120px] px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-full text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Add tags...">';
                this.setupTags(); // Reinitialize tags functionality
                PostManager.loadMorePosts(); // Refresh the feed
            }
        } catch (error) {
            console.error('Error creating post:', error);
            AppState.showNotification('Failed to create post. Please try again.', 'error');
        }
    }
};

// Notification Manager
const NotificationManager = {
    show(message, type = 'info') {
        // Create notification container if it doesn't exist
        let container = document.getElementById('notification-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notification-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-4';
            document.body.appendChild(container);
        }

        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification ${type} transform transition-all duration-300 ease-in-out translate-x-full`;
        
        // Set background color based on type
        const bgColor = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            info: 'bg-blue-500',
            warning: 'bg-yellow-500'
        }[type] || 'bg-blue-500';

        notification.innerHTML = `
            <div class="flex items-center p-4 rounded-lg shadow-lg ${bgColor} text-white">
                <div class="flex-1">${message}</div>
                <button class="ml-4 text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        `;

        // Add to container
        container.appendChild(notification);

        // Animate in
        requestAnimationFrame(() => {
            notification.classList.remove('translate-x-full');
        });

        // Add click handler for close button
        const closeButton = notification.querySelector('button');
        closeButton.addEventListener('click', () => {
            this.hide(notification);
        });

        // Auto-hide after 5 seconds
        setTimeout(() => {
            this.hide(notification);
        }, 5000);
    },

    hide(notification) {
        notification.classList.add('translate-x-full');
        notification.addEventListener('transitionend', () => {
            notification.remove();
        });
    }
};

// Post Comment Manager
const PostCommentManager = {
    init() {
        this.setupCommentForm();
    },

    setupCommentForm() {
        const commentForm = document.getElementById('commentForm');
        if (!commentForm) return;

        const commentInput = commentForm.querySelector('textarea[id="commentInput"]');
        const submitButton = commentForm.querySelector('button[type="submit"]');

        // Enable/disable submit button based on input
        commentInput.addEventListener('input', () => {
            submitButton.disabled = !commentInput.value.trim();
        });

        // Handle form submission
        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const content = commentInput.value.trim();
            if (!content) return;

            try {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                `;

                const postId = window.location.pathname.split('/').pop();
                const response = await fetch('/api/posts/comment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        post_id: postId,
                        content: content
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to post comment');
                }

                const data = await response.json();
                
                // Add the new comment to the UI
                this.addCommentToUI(data.comment);
                
                // Clear the input
                commentInput.value = '';
                submitButton.disabled = true;
                submitButton.innerHTML = 'Post';

                // Show success notification
                NotificationManager.show('Comment posted successfully', 'success');
            } catch (error) {
                console.error('Error posting comment:', error);
                submitButton.disabled = false;
                submitButton.innerHTML = 'Post';
                NotificationManager.show('Failed to post comment. Please try again.', 'error');
            }
        });
    },

    addCommentToUI(comment) {
        const commentsContainer = document.querySelector('.comments-list');
        if (!commentsContainer) return;

        const commentElement = document.createElement('div');
        commentElement.className = 'comment-item bg-white dark:bg-gray-800 rounded-lg p-4 mb-4';
        commentElement.innerHTML = `
            <div class="flex items-start space-x-3">
                <img src="${comment.user.avatar || '/assets/images/default-avatar.png'}" 
                    alt="${comment.user.name}" 
                    class="w-10 h-10 rounded-full">
                <div class="flex-1">
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">${comment.user.name}</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Just now</p>
                        </div>
                    </div>
                    <p class="mt-1 text-gray-700 dark:text-gray-300">${comment.content}</p>
                    <div class="mt-2 flex items-center space-x-4">
                        <button class="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 text-sm">
                            Like
                        </button>
                        <button class="text-gray-500 hover:text-blue-600 dark:hover:text-blue-400 text-sm">
                            Reply
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Add the new comment at the top of the list
        commentsContainer.insertBefore(commentElement, commentsContainer.firstChild);
    }
};

// New Message Manager
const NewMessageManager = {
    init() {
        console.log('Initializing NewMessageManager...');
        this.modal = document.getElementById('newMessageModal');
        this.searchInput = document.getElementById('userSearchInput');
        this.searchResults = document.getElementById('userSearchResults');
        
        if (!this.modal || !this.searchInput || !this.searchResults) {
            console.error('Required modal elements not found:', {
                modal: !!this.modal,
                searchInput: !!this.searchInput,
                searchResults: !!this.searchResults
            });
            return;
        }
        
        this.setupEventListeners();
        console.log('NewMessageManager initialized successfully');
    },

    setupEventListeners() {
        // Open modal when clicking New Message button
        const newMessageBtn = document.getElementById('newMessageBtn');
        console.log('New Message button found:', !!newMessageBtn);
        
        if (newMessageBtn) {
            newMessageBtn.addEventListener('click', (e) => {
                console.log('New Message button clicked');
                e.preventDefault();
                this.openModal();
            });
        }

        // Close modal when clicking close buttons or outside
        const closeButtons = document.querySelectorAll('.close-modal');
        closeButtons.forEach(button => {
            button.addEventListener('click', () => this.closeModal());
        });

        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.closeModal();
            }
        });

        // Handle user search
        if (this.searchInput) {
            let debounceTimer;
            this.searchInput.addEventListener('input', (e) => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    this.searchUsers(e.target.value);
                }, 300);
            });
        }

        // Close modal on escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !this.modal.classList.contains('hidden')) {
                this.closeModal();
            }
        });
    },

    openModal() {
        console.log('Opening modal...');
        if (!this.modal) {
            console.error('Modal element not found');
            return;
        }
        
        this.modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Focus the search input after a short delay to ensure the modal is visible
        setTimeout(() => {
            this.searchInput.focus();
        }, 100);
        console.log('Modal opened');
    },

    closeModal() {
        console.log('Closing modal...');
        if (!this.modal) {
            console.error('Modal element not found');
            return;
        }
        
        this.modal.classList.add('hidden');
        document.body.style.overflow = '';
        this.searchInput.value = '';
        this.searchResults.innerHTML = '';
        console.log('Modal closed');
    },

    async searchUsers(query) {
        if (!query.trim()) {
            this.searchResults.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(`/api/users/search?q=${encodeURIComponent(query)}`);
            const users = await response.json();

            this.searchResults.innerHTML = users.length ? '' : '<div class="p-4 text-center text-gray-500 dark:text-gray-400">No users found</div>';

            users.forEach(user => {
                const userElement = document.createElement('div');
                userElement.className = 'p-3 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors';
                userElement.innerHTML = `
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                            <span class="text-sm font-medium text-gray-600 dark:text-gray-300">${user.name.charAt(0)}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">${user.name}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${user.email}</p>
                        </div>
                    </div>
                `;

                userElement.addEventListener('click', () => this.startConversation(user));
                this.searchResults.appendChild(userElement);
            });
        } catch (error) {
            console.error('Error searching users:', error);
            this.searchResults.innerHTML = '<div class="p-4 text-center text-red-500">Error searching users</div>';
        }
    },

    async startConversation(user) {
        try {
            const response = await fetch('/api/chat/start', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ userId: user.id })
            });

            if (!response.ok) throw new Error('Failed to start conversation');

            const chat = await response.json();
            this.closeModal();
            
            // Redirect to the new chat
            window.location.href = `/chat/${chat.id}`;
        } catch (error) {
            console.error('Error starting conversation:', error);
            NotificationManager.show('Failed to start conversation', 'error');
        }
    }
};

// Profile Manager
const ProfileManager = {
    init() {
        this.setupProfileForm();
        this.setupSettingsToggles();
        this.setupProfilePictureUpload();
    },

    setupProfileForm() {
        const form = document.getElementById('editProfileForm');
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            try {
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Saving...
                `;

                const formData = new FormData(form);
                const response = await fetch('/profile/update', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                if (data.success) {
                    NotificationManager.show('Profile updated successfully', 'success');
                    setTimeout(() => {
                        window.location.href = '/profile';
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Failed to update profile');
                }
            } catch (error) {
                console.error('Error updating profile:', error);
                NotificationManager.show(error.message || 'Failed to update profile', 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    },

    setupSettingsToggles() {
        const toggles = document.querySelectorAll('[role="switch"]');
        toggles.forEach(toggle => {
            toggle.addEventListener('click', async () => {
                const setting = toggle.dataset.setting;
                const isChecked = toggle.getAttribute('aria-checked') === 'true';
                
                try {
                    const response = await fetch('/profile/settings', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            [setting]: !isChecked
                        })
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        toggle.setAttribute('aria-checked', !isChecked);
                        toggle.classList.toggle('bg-blue-600', !isChecked);
                        toggle.classList.toggle('bg-gray-200', isChecked);
                        toggle.classList.toggle('dark:bg-gray-700', isChecked);
                        
                        const span = toggle.querySelector('span');
                        span.classList.toggle('translate-x-5', !isChecked);
                        span.classList.toggle('translate-x-0', isChecked);
                        
                        NotificationManager.show('Settings updated successfully', 'success');
                    } else {
                        throw new Error(data.message || 'Failed to update settings');
                    }
                } catch (error) {
                    console.error('Error updating settings:', error);
                    NotificationManager.show(error.message || 'Failed to update settings', 'error');
                }
            });
        });
    },

    setupProfilePictureUpload() {
        const uploadButton = document.querySelector('button[type="button"]');
        if (!uploadButton) return;

        uploadButton.addEventListener('click', () => {
            const input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.style.display = 'none';
            
            input.addEventListener('change', async (e) => {
                const file = e.target.files[0];
                if (!file) return;

                if (file.size > 2 * 1024 * 1024) {
                    NotificationManager.show('File size must be less than 2MB', 'error');
                    return;
                }

                const formData = new FormData();
                formData.append('profile_picture', file);

                try {
                    const response = await fetch('/profile/update', {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        // Update profile picture preview
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            const profilePicture = document.querySelector('.rounded-full');
                            if (profilePicture) {
                                profilePicture.style.backgroundImage = `url(${e.target.result})`;
                                profilePicture.style.backgroundSize = 'cover';
                                profilePicture.style.backgroundPosition = 'center';
                            }
                        };
                        reader.readAsDataURL(file);
                        
                        NotificationManager.show('Profile picture updated successfully', 'success');
                    } else {
                        throw new Error(data.message || 'Failed to update profile picture');
                    }
                } catch (error) {
                    console.error('Error updating profile picture:', error);
                    NotificationManager.show(error.message || 'Failed to update profile picture', 'error');
                }
            });

            document.body.appendChild(input);
            input.click();
            document.body.removeChild(input);
        });
    }
};

// Initialize the app
document.addEventListener('DOMContentLoaded', () => {
    AppState.init();
    ChatManager.init();
    PostManager.init();
    AuthManager.init();
    PostCreationManager.init();
    PostCommentManager.init();
    NewMessageManager.init();
    ProfileManager.init();
}); 