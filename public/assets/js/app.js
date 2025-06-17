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

// Chat Functionality
const ChatManager = {
    activeChat: null,
    messages: new Map(),
    init() {
        this.setupWebSocket();
        this.setupMessageHandlers();
    },
    setupWebSocket() {
        let userId = AppState.user.user_id;
        let token = AppState.getToken();
        this.ws = new WebSocket(`ws://localhost:3000?userId=${userId}&token=${token}`);
        this.ws.onopen = () => {
            this.ws.send(JSON.stringify({
                endpoint: 'setup/login',
                type: 'setup',
                baseUrl: baseUrl
            }));
        };
          
        this.ws.onmessage = (event) => this.handleIncomingMessage(event);
        this.ws.onclose = () => this.handleConnectionClose();
    },
    setupMessageHandlers() {
        const messageForm = document.querySelector('#messageForm');
        if (messageForm) {
            messageForm.addEventListener('submit', (e) => {
                e.preventDefault();
                const input = messageForm.querySelector('input');
                if (input.value.trim()) {
                    this.sendMessage(input.value);
                    input.value = '';
                }
            });
        }
    },
    sendMessage(content) {
        if (!this.activeChat) return;
        const message = {
            type: 'message',
            chatId: this.activeChat.id,
            content,
            timestamp: new Date().toISOString()
        };
        this.ws.send(JSON.stringify(message));
        this.addMessageToUI(message, true);
    },
    handleIncomingMessage(event) {
        const message = JSON.parse(event.data);
        if (message.type === 'message') {
            this.addMessageToUI(message, false);
        }
    },
    addMessageToUI(message, isSent) {
        const chatContainer = document.querySelector('.chat-messages');
        if (!chatContainer) return;

        const messageElement = document.createElement('div');
        messageElement.className = `message flex ${isSent ? 'justify-end' : 'justify-start'}`;
        messageElement.innerHTML = `
            <div class="max-w-xs ${isSent ? 'bg-blue-500 text-white' : 'bg-gray-100'} rounded-lg px-4 py-2">
                <p class="text-sm">${message.content}</p>
                <p class="text-xs ${isSent ? 'text-blue-100' : 'text-gray-500'} mt-1">
                    ${new Date(message.timestamp).toLocaleTimeString()}
                </p>
            </div>
        `;
        chatContainer.appendChild(messageElement);
        chatContainer.scrollTop = chatContainer.scrollHeight;
    },
    handleConnectionClose() {
        AppState.showNotification('Connection lost. Reconnecting...', 'error');
        setTimeout(() => this.setupWebSocket(), 3000);
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

// Initialize the app
document.addEventListener('DOMContentLoaded', () => {
    AppState.init();
    ChatManager.init();
    PostManager.init();
    AuthManager.init();
}); 