// Microphone Permission Manager
var longitude = '', latitude = '', radius = 35, userUUID = '';
const MicrophoneManager = {
    permissionState: null,
    stream: null,

    async requestPermission() {
        if (this.permissionState === 'granted') {
            return this.stream;
        }

        if (this.permissionState === 'denied') {
            throw new Error('Microphone access was denied');
        }

        try {
            this.stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            this.permissionState = 'granted';
            return this.stream;
        } catch (error) {
            this.permissionState = 'denied';
            throw error;
        }
    },

    async getStream() {
        if (this.stream) {
            return this.stream;
        }
        return this.requestPermission();
    },

    stopStream() {
        if (this.stream) {
            this.stream.getTracks().forEach(track => track.stop());
            this.stream = null;
        }
    }
};

// App State Management
const AppState = {
    user: null,
    location: null,
    theme: 'light',
    notifications: [],
    isOnline: navigator.onLine,
    socketConnect: false,
    init() {
        this.loadUser();
        this.checkLocation();
        this.loadTheme();
        this.setupEventListeners();
        this.menuButtonControl();
    },
    logout() {
        localStorage.removeItem('user');
        localStorage.removeItem('userLocation');
        this.clearLocationData();
        $.post(`${baseUrl}/api/auth/logout`, {
            token: localStorage.getItem('token'),
            webapp: true,
            noloc: true
        }).then(() => {
            AppState.showNotification('Logged out successfully', 'success');
            setTimeout(() => {
                localStorage.removeItem('token');
                document.cookie = 'user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                document.cookie = 'token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                document.cookie = 'user_token=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
                window.location.href = `${baseUrl}/login`;
            }, 500);
        });
        return true;
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
    getUserId() {
        return this.user?.user_id || 0;
    },
    loadTheme() {
        const theme = 'light'; //localStorage.getItem('theme') || 'light';
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
        if (!('geolocation' in navigator)) {
            // Fallback to default location if geolocation is not supported
            return;
        }

        // Check if we have stored location data first
        const storedLocation = localStorage.getItem('userLocation');
        if (storedLocation) {
            try {
                const locationData = JSON.parse(storedLocation);
                if (locationData.latitude && locationData.longitude) {
                    this.location = locationData;
                    longitude = locationData.longitude;
                    latitude = locationData.latitude;
                    this.updateLocationUI();
                    return;
                }
            } catch (e) {
                // Invalid stored data, continue with fresh request
            }
        }

        // Check permission state first
        if ('permissions' in navigator) {
            try {
                const permission = await navigator.permissions.query({ name: 'geolocation' });
                if (permission.state === 'denied') {
                    return;
                }
                if (permission.state === 'granted') {
                    await this.getCurrentLocation();
                    return;
                }
                
                // Permission state is 'prompt', will show browser prompt
            } catch (e) {
                // Permissions API not supported, fall back to direct geolocation
            }
        }

        // Fallback: try to get location (will show prompt if needed)
        await this.getCurrentLocation();
    },
    async getCurrentLocation() {
        try {
            const position = await new Promise((resolve, reject) => {
                const options = {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000
                };
                navigator.geolocation.getCurrentPosition(resolve, reject, options);
            });

            if (position && position.coords) {
                this.location = {
                    latitude: position.coords.latitude,
                    longitude: position.coords.longitude
                };
                localStorage.setItem('userLocation', JSON.stringify(this.location));
                longitude = this.location.longitude;
                latitude = this.location.latitude;
                this.updateLocationUI();
            }
        } catch (error) {
            console.log('Location access error:', error.message);
        }
    },
    updateLocationUI() {
        const locationElement = document.querySelector('.location-display');
        if (locationElement && this.location) {
            locationElement.textContent = `${this.location.latitude.toFixed(6)}, ${this.location.longitude.toFixed(6)}`;
            longitude = this.location.longitude;
            latitude = this.location.latitude;
        }
    },
    showNotification(message, type = 'info', timer = 3000) {
        const notification = {
            id: Date.now(),
            message,
            type
        };
        this.notifications.push(notification);
        this.renderNotification(notification, timer);
    },
    renderNotification(notification, timer = 3000) {
        const toast = document.createElement('div');
        toast.className = `toast notification-${notification.type}`;
        toast.textContent = notification.message;
        document.body.appendChild(toast);
        setTimeout(() => {
            toast.remove();
            this.notifications = this.notifications.filter(n => n.id !== notification.id);
        }, timer);
    },
    generateUUID() {
        const bytes = crypto.getRandomValues(new Uint8Array(16));
    
        // Set version to 4 => xxxx4xxx
        bytes[6] = (bytes[6] & 0x0f) | 0x40;
    
        // Set variant to 10xxxxxx
        bytes[8] = (bytes[8] & 0x3f) | 0x80;
    
        const hex = Array.from(bytes).map(b => b.toString(16).padStart(2, '0'));
    
        return [
        hex.slice(0, 4).join(''),
        hex.slice(4, 6).join(''),
        hex.slice(6, 8).join(''),
        hex.slice(8, 10).join(''),
        hex.slice(10, 16).join('')
        ].join('-');
    },
    // Function to manually refresh location (clears cache and gets fresh location)
    async refreshLocation() {
        // Clear stored location to force fresh request
        localStorage.removeItem('userLocation');
        
        // Show loading state
        this.showNotification('Updating location...', 'info', 2000);
        
        // Get fresh location
        await this.getCurrentLocation();
        
        this.showNotification('Location updated successfully!', 'success', 2000);
    },
    logPageView() {
        $.post(`${baseUrl}/api/analytics/pageview`, {
            page: location.pathname,
            userUUID: userUUID,
            noloc: true,
            user_id: loggedInUserId,
            referer: document.referrer
        });
    },
    // Function to clear location data and reset to default
    clearLocationData() {
        localStorage.removeItem('userLocation');
        this.location = null;
        latitude = '';
        longitude = '';
    },
    menuButtonControl() {
        // Modern Menu Management
        if(document.getElementById('menuButton')) {
            document.getElementById('menuButton').addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            
            // Dispatch a custom event to toggle the menu
            const toggleEvent = new CustomEvent('toggleMenu', {
                detail: { action: 'toggle' }
            });
            document.dispatchEvent(toggleEvent);
            });
        }
    },
    // Platform Updates Manager
    hidePlatformUpdates() {
        const updatesElement = document.getElementById('platformUpdates');
        if (updatesElement) {
            updatesElement.style.transition = 'all 0.3s ease';
            updatesElement.style.opacity = '0';
            updatesElement.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                updatesElement.remove();
                localStorage.setItem('hideUpdates', true);
            }, 300);
        }
    }
};

const MediaManager = {
    postMediaPreview: null,

    init() {
        this.postMediaPreview = document.getElementById('postMediaPreview');
        this.previewContainer = document.getElementById('previewContainer');
        this.audioPreview = document.getElementById('audioPreview');
        this.audioPlayer = document.getElementById('audioPlayer');
    },
    renderMedia(mediaFiles = [], returnData = false) {
        let html = '<div class="media-display-container space-y-4 mt-3">';
        html += '<div class="media-grid grid grid-cols-3 gap-2">';
        if(typeof mediaFiles.images !== 'undefined') {
            if(mediaFiles.images?.files.length > 0) {
                mediaFiles.images?.files.forEach((img, key) => {
                    let _300thumb = mediaFiles.images?.thumbnails[key][0] ?? img;
                    let image = img;
                    html += `
                        <div class="media-item image-item" data-type="image" data-src="${baseUrl}/assets/uploads/${image}" data-thumbnail="${baseUrl}/assets/uploads/${_300thumb}">
                            <div class="relative group cursor-pointer overflow-hidden rounded-lg bg-gray-100 aspect-square">
                                <img src="${baseUrl}/assets/uploads/${_300thumb}" alt="Sample Image" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </div>
                                <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                    IMG
                                </div>
                            </div>
                        </div>`;
                });
            }
        }
        if(typeof mediaFiles.video !== 'undefined') {
            if(mediaFiles.video?.files.length > 0) {
                mediaFiles.video?.files.forEach((video, key) => {
                    let _300thumb = '';
                    if(typeof mediaFiles.video?.thumbnails !== 'undefined') {
                        _300thumb = mediaFiles.video?.thumbnails[key][0] ?? `video-playback.png`;
                    } else {
                        _300thumb = `video-playback.png`;
                    }
                    html += `<div class="media-item video-item" data-type="video" data-src="${baseUrl}/assets/uploads/${video}" data-thumbnail="${baseUrl}/assets/uploads/${_300thumb}">
                        <div class="relative group cursor-pointer overflow-hidden rounded-lg bg-gray-100">
                            <img src="${baseUrl}/assets/uploads/${_300thumb}" style="min-height: 100px;" alt="Video Thumbnail" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                <div class="bg-white bg-opacity-90 rounded-full p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    <svg class="w-6 h-6 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="absolute bottom-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                Video
                            </div>
                        </div>
                    </div>`;
                });
            }
        }
        html += '</div>';
        if(typeof mediaFiles.audio !== 'undefined') {
            if(mediaFiles.audio?.files.length > 0) {
                mediaFiles.audio?.files.forEach((audio, key) => {
                    html += `
                    <audio controls class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg p-4 border border-gray-200" download=false>
                        <source src="${baseUrl}/assets/uploads/${audio}" type="audio/mpeg">
                        Your browser does not support the audio element.
                    </audio>`;
                });
            }
        }

        html += '</div>';

        if(returnData) return html;

        $(`#postMediaPreview`).html(html);
        new MediaDisplay();
    }
}

class MediaDisplay {
    constructor() {
        this.currentIndex = 0;
        this.mediaItems = [];
        this.modal = document.getElementById('fullViewModal');
        this.modalContent = document.getElementById('modalContent');
        this.loadingSpinner = document.getElementById('loadingSpinner');
        this.closeBtn = document.getElementById('closeModal');
        this.prevBtn = document.getElementById('prevBtn');
        this.nextBtn = document.getElementById('nextBtn');
        
        this.init();
    }

    init() {
        // Collect all media items
        this.mediaItems = Array.from(document.querySelectorAll('.media-item'));
        // Add click listeners to media items
        this.mediaItems.forEach((item, index) => {
            item.addEventListener('click', () => this.openFullView(index));
        });

        if(!this.closeBtn) return;

        // Modal controls
        this.closeBtn.addEventListener('click', () => this.closeFullView());
        this.prevBtn.addEventListener('click', () => this.navigate(-1));
        this.nextBtn.addEventListener('click', () => this.navigate(1));
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (!this.modal.classList.contains('hidden')) {
                switch(e.key) {
                    case 'Escape':
                        this.closeFullView();
                        break;
                    case 'ArrowLeft':
                        this.navigate(-1);
                        break;
                    case 'ArrowRight':
                        this.navigate(1);
                        break;
                }
            }
        });

        // Click outside to close
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.closeFullView();
            }
        });

        // Audio player functionality
        this.initAudioPlayers();
    }

    openFullView(index) {
        this.currentIndex = index;
        const item = this.mediaItems[index];
        const type = item.dataset.type;
        const src = item.dataset.src;

        this.modal.classList.remove('hidden');
        this.showLoading();

        if (type === 'image') {
            this.loadImage(src);
        } else if (type === 'video') {
            this.loadVideo(src);
        } else if (type === 'audio') {
            this.loadAudio(src);
        }

        this.updateNavigationButtons();
    }

    loadImage(src) {
        const img = new Image();
        img.onload = () => {
            this.hideLoading();
            this.modalContent.innerHTML = `
                <img src="${src}" alt="Full size image" class="max-w-full max-h-full object-contain">
            `;
        };
        img.onerror = () => {
            this.hideLoading();
            this.showError('Failed to load image');
        };
        img.src = src;
    }

    loadVideo(src) {
        this.hideLoading();
        this.modalContent.innerHTML = `
            <video controls autoplay class="max-w-full max-h-full">
                <source src="${src}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        `;
    }

    loadAudio(src) {
        this.hideLoading();
        this.modalContent.innerHTML = `
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Audio Player</h3>
                    <audio controls class="w-full">
                        <source src="${src}" type="audio/mpeg">
                        Your browser does not support the audio tag.
                    </audio>
                </div>
            </div>
        `;
    }

    navigate(direction) {
        const newIndex = this.currentIndex + direction;
        if (newIndex >= 0 && newIndex < this.mediaItems.length) {
            this.openFullView(newIndex);
        }
    }

    updateNavigationButtons() {
        this.prevBtn.style.display = this.currentIndex > 0 ? 'block' : 'none';
        this.nextBtn.style.display = this.currentIndex < this.mediaItems.length - 1 ? 'block' : 'none';
    }

    closeFullView() {
        this.modal.classList.add('hidden');
        this.modalContent.innerHTML = '';
    }

    showLoading() {
        this.loadingSpinner.classList.remove('hidden');
    }

    hideLoading() {
        this.loadingSpinner.classList.add('hidden');
    }

    showError(message) {
        this.modalContent.innerHTML = `
            <div class="bg-white rounded-lg p-8 text-center">
                <svg class="w-16 h-16 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-gray-600">${message}</p>
            </div>
        `;
    }

    initAudioPlayers() {
        document.querySelectorAll('.play-audio-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                const audioItem = btn.closest('.audio-item');
                const audioSrc = audioItem.dataset.src;
                
                // Create temporary audio element
                const audio = new Audio(audioSrc);
                audio.play();
                
                // Update button state
                btn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                `;
                
                audio.addEventListener('ended', () => {
                    btn.innerHTML = `
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M8 5v14l11-7z"/>
                        </svg>
                    `;
                });
            });
        });
    }
}

// Post Management
const PostManager = {
    posts: [],
    loadedPostIds: [],
    currentPage: 1,
    isLoading: false,
    lastPostId: 0,
    postLimit: 100,
    lastOldPostId: 0,
    unreadPostsCount: 0,
    unreadPosts: [],
    userLocation: [],
    init() {
        this.loadInitialFeed();
        this.setupPostInteractions();
        this.loadPost();
    },
    closeCreateModal() {
        $('#postCreationForm')?.addClass('hidden');
        document?.getElementById('createPostForm')?.reset();
        ImprovedPostCreationForm.resetForm();
    },
    openCreateModal() {
        $('#postCreationForm').removeClass('hidden');
        $(`form[id="createPostFormUnique"] textarea[id="content"]`).val('');
        $(`form[id="createPostFormUnique"] textarea[id="content"]`).focus();
    },
    loadPost() {
        const postContainer = document.getElementById('postContainer');
        if (!postContainer) return;
        const postId = postContainer.getAttribute('data-posts-id');
        if(!postId) return;
        PostCommentManager.postId = postId;
        $.get(`${baseUrl}/api/posts/view/${postId}`, {
            token: AppState.getToken(),
            longitude,
            latitude,
            userUUID
        }).then(data => {
            if(data.status == 'success') {
                postContainer.innerHTML = '';
                postContainer.appendChild(this.createPostElement(data.data, true));
                MediaManager.renderMedia(data.data.post_media);
                // comments
                const commentsContainer = document.getElementById('commentsList');
                if(commentsContainer) {
                    commentsContainer.innerHTML = '';
                    if(data.data.comments.length > 0) {
                        data.data.comments.forEach(comment => {
                            commentsContainer.appendChild(this.createCommentElement(comment));
                        });
                        $(`span[id="commentCount"]`).html(`(${data.data.comments.length})`);
                    } else {
                        commentsContainer.innerHTML = '<p class="text-gray-500 dark:text-gray-800" id="commentsLoading">No replies yet</p>';
                    }
                }
            } else {
                AppState.showNotification(data.message, 'error');
            }
        });
    },
    createCommentElement(comment) {
        const div = document.createElement('div');
        PostCommentManager.commentsList.push(comment.comment_id);
        div.className = 'comment-card bg-white rounded-lg shadow-sm p-4 mb-4 bg-gradient-to-r border-t border-blue-500  hover:border-blue-400 hover:shadow-md transition-all duration-300';
        div.setAttribute('data-comment-id', comment.comment_id);
        div.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-500 text-sm">${comment.username[0].toUpperCase()}${comment.username[1].toUpperCase()}</span>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900 dark:text-white">${comment.username}</div>
                        <div class="text-xs text-gray-500 flex items-center space-x-1">
                            <span title="${comment.created_at}" class="text-xs text-gray-500 mr-2 flex items-center space-x-1">
                                ${comment.ago}
                            </span>
                            ${comment.city ? `
                            <span class="text-xs text-gray-500 flex items-center space-x-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                ${comment.city}
                            </span>
                            ` : ''}
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-gray-800 mb-3 dark:text-white whitespace-pre-wrap text-sm">${comment.content}</p>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button class="flex text-sm items-center space-x-1 text-gray-500 hover:text-blue-500" data-comments-id="${comment.comment_id}" onclick="return PostManager.handleVote('comments', ${comment.comment_id}, 'up', ${comment.user_id})">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                        <span data-comments-id-upvotes="${comment.comment_id}">${comment.upvotes}</span>
                    </button>
                    <button class="flex text-sm items-center space-x-1 text-gray-500 hover:text-red-500" data-comments-id="${comment.comment_id}" onclick="return PostManager.handleVote('comments', ${comment.comment_id}, 'down', ${comment.user_id})">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 10v2a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/>
                        </svg>
                        <span data-comments-id-downvotes="${comment.comment_id}">${comment.downvotes}</span>
                    </button>
                    ${comment?.manage?.delete ? `
                        <button class="delete-button text-sm flex items-center space-x-1 text-red-500 hover:text-red-500" data-comments-id="${comment.comment_id}" onclick="return PostManager.handleDelete('comments', ${comment.comment_id})">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    ` : ''}
                </div>
            </div>`;
        return div;
    },
    async loadInitialFeed() {
        if($('#feedContainer').length == 0) return;
        if(localStorage.getItem('hideUpdates')) {
            AppState.hidePlatformUpdates();
        }
        await AppState.checkLocation();
        const container = document.getElementById('#feedContainer');
        if (container) container.innerHTML = '';
        PostManager.loadMorePosts();
    },
    setupPostInteractions() {
        // Add existing event listeners
        document.addEventListener('click', (e) => {
            if (e.target.matches('.vote-button')) {
                this.handleVote(e.target);
            }
        });

        // Add new context menu handlers
        document.addEventListener('click', (e) => {
            const menuButton = e.target.closest('.post-menu-button');
            const menu = e.target.closest('.post-context-menu');
            
            // If clicking on a menu button
            if (menuButton) {
                e.preventDefault();
                e.stopPropagation();
                
                const currentMenu = menuButton.nextElementSibling;
                const isCurrentMenuVisible = !currentMenu.classList.contains('hidden');
                
                // Hide all other menus first
                this.hideContextMenu();
                
                // Toggle this menu (show if it was hidden, keep hidden if it was visible)
                if (!isCurrentMenuVisible) {
                    currentMenu.classList.remove('hidden');
                }
                return;
            }
            
            // If clicking inside a menu, don't close it
            if (menu) {
                e.stopPropagation();
                return;
            }
            
            // Close all menus when clicking outside
            this.hideContextMenu();
        });

        // Close context menu on scroll
        window.addEventListener('scroll', () => {
            this.hideContextMenu();
        });
    },
    async showOldPosts() {
        this.loadMorePosts(false, false, this.lastOldPostId, 50);
    },
    async loadLatestPosts() {
        // load unread posts
        this.loadMorePosts(true, true);
        // filter out unread post by unique post_id
        this.unreadPosts = Array.from(new Map(this.unreadPosts.map(p => [p.post_id, p])).values());
        this.unreadPostsCount = this.unreadPosts.length;
    },
    async loadMorePosts(dontTrigger = false, unreadCounter = false, lastOldPostId = 0, limit = 20) {
        if (this.isLoading) return;
        this.isLoading = true;
        try {
            let whereClause = lastOldPostId ? `&previous_record_id=${lastOldPostId}` : ``;
            limit = lastOldPostId ? limit : this.postLimit;

            if(typeof requestLimit !== 'undefined') {
                limit = requestLimit;
            }

            if(typeof requestData !== 'undefined') {
                whereClause = `&request_data=${requestData}`;
            }

            radius = postRadius;

            const response = await fetch(`${baseUrl}/api/posts/nearby?last_record_id=${this.currentPage}${whereClause}&longitude=${longitude}&latitude=${latitude}&token=${AppState.getToken()}&limit=${limit}&userUUID=${userUUID}&radius=${radius}`);
            const data = await response.json();

            if(data.status == 'error') {
                if(typeof data.must_login !== 'undefined') {
                    AppState.logout();
                    return;
                }
            }
            this.posts = [...this.posts, ...data.data];
            this.lastPostId = data?.data[0]?.post_id || 0;
            this.renderPosts(data.data, dontTrigger, unreadCounter, lastOldPostId);

            // if requestLimit is not defined, load latest posts
            if(!dontTrigger && !lastOldPostId && typeof requestLimit === 'undefined') {
                setInterval(() => this.loadLatestPosts(), 30000);
            }

            if(data.data.length == 0) {
                document.getElementById('oldPostsContainer').classList.add('hidden');
            }
            if(data?.location) {
                this.userLocation = data?.location ?? this.userLocation;
                if(this.userLocation && $(`.location-display`).length > 0) {
                    if(this.userLocation.city) {
                        $(`.location-display`).html(`${this.userLocation.city}, ${this.userLocation.country}`);
                    }
                    if(this.userLocation.latitude && this.userLocation.longitude) {
                        longitude = this.userLocation.longitude;
                        latitude = this.userLocation.latitude;

                        if(!localStorage.getItem('userLocation')) {
                            let newLoc = {
                                latitude: data?.location.latitude,
                                longitude: data?.location.longitude
                            };
                            localStorage.setItem('userLocation', JSON.stringify(newLoc));
                        }

                        $(`.position-display`).html(`${this.userLocation.latitude}, ${this.userLocation.longitude}`);
                    }
                }
            }
        } catch (error) {
            console.log(error);
        } finally {
            this.isLoading = false;
        }
    },
    showUnreadPosts() {
        this.renderPosts(this.unreadPosts, true, false);
        this.unreadPosts = [];
        this.unreadPostsCount = 0;
        document.getElementById('unreadPostsCountContainer').classList.add('hidden');
    },
    renderPosts(posts, sendToTop = false, unreadCounter = false, lastOldPostId = 0) {
        const container = document.getElementById('feedContainer');
        if (!container) return;
        $('.loading-skeleton').remove();

        posts.forEach((post, key) => {
            if(this.loadedPostIds.includes(post.post_id)) {
                return;
            }
            if(!unreadCounter) {
                this.loadedPostIds.push(post.post_id);
            }
            if(key == 0 && !lastOldPostId) {
                this.currentPage = post.post_id;
            }
            if(this.lastOldPostId == 0) {
                this.lastOldPostId = post.post_id;
            }
            if(post.post_id < this.lastOldPostId) {
                this.lastOldPostId = post.post_id;
            }
            if(unreadCounter) {
                this.unreadPosts.push(post);
                this.unreadPostsCount = this.unreadPosts.length;
            } else {
                const postElement = this.createPostElement(post);
                if(sendToTop) {
                    container.insertBefore(postElement, container.firstChild);
                } else {
                    container.appendChild(postElement);
                }
            }
        });

        if(lastOldPostId && posts.length == 0) {
            document.getElementById('noPostsContainer').classList.remove('hidden');
            document.getElementById('oldPostsContainer').classList.add('hidden');
        }
        if(this.unreadPostsCount > 0) {
            // filter out unread post by unique post_id
            this.unreadPosts = Array.from(new Map(this.unreadPosts.map(p => [p.post_id, p])).values());
            this.unreadPostsCount = this.unreadPosts.length;
            // show unread posts count
            if(unreadCounter) {
                document.getElementById('unreadPostsCount').innerHTML = this.unreadPostsCount;
                document.getElementById('unreadPostsCountContainer').classList.remove('hidden');
            } else {
                document.getElementById('unreadPostsCountContainer').classList.add('hidden');
            }
        }
    },
    changeDirection(postId) {
        return window.location.href = `${baseUrl}/posts/view/${postId}`;
    },
    createPostElement(post, single = false) {
        const div = document.createElement('div');
        div.className = `post-card bg-white border rounded-lg shadow-sm p-2 ${single ? '' : ' mb-2 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 rounded-lg hover:border-blue-400'} cursor-pointer hover:shadow-md transition-all duration-300 relative`;
        div.setAttribute('data-post-id', post.post_id);
        
        div.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center space-x-2 post-header-clickable">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                        <span class="text-sm font-semibold text-white">${post.username[0].toUpperCase()}${post.username[1].toUpperCase()}</span>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-900">${post.username}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-800 flex items-center space-x-1">
                            <span title="${post.created_at}" class="text-xs text-gray-500 dark:text-gray-800 mr-2 flex items-center space-x-1">
                                ${post.ago}
                            </span>
                            ${post.city ? `
                            <span class="text-xs text-gray-500 dark:text-gray-800 flex items-center space-x-1">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                ${post.city}
                            </span>
                            ` : ''}
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <button class="post-menu-button text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                        </svg>
                    </button>
                    <div class="post-context-menu hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50 py-1">
                        ${!single ? `
                        <a class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center space-x-2" href="${baseUrl}/posts/view/${post.post_id}">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>View Post</span>
                        </a>` : ''}
                        ${post?.user_id !== AppState.user.user_id ? `
                        <button class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center space-x-2" onclick="event.stopPropagation(); PostManager.handleBookmark(${post.post_id}, ${post.manage.save})">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                            </svg>
                            <span class="save-post-${post.post_id}">${post.manage.bookmarked ? 'Remove Post' : 'Save Post'}</span>
                        </button>` : ''}
                        ${post?.manage?.report ? `
                        <button class="hidden w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center space-x-2" onclick="event.stopPropagation(); PostManager.handleReport(${post.post_id})">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                            <span>Report Post</span>
                        </button>
                        ` : ''}
                        ${post?.manage?.delete ? `
                        <button class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center space-x-2" onclick="event.stopPropagation(); PostManager.handleDelete('posts', ${post.post_id})">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span>Delete Post</span>
                        </button>
                        ` : ''}
                    </div>
                </div>
            </div>
            <div class="post-content-clickable text-gray-800 dark:text-gray-900 mb-3 text-sm leading-relaxed">${post.content}</div>
            ${post.has_media ? `
                <div class="post-content-clickable flex flex-wrap gap-2 text-sm text-gray-500 mb-2">
                    ${post.media_types.includes('images') ? `
                    <span class="inline-flex items-center px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                        </svg>
                        Images
                    </span>` : ''}
                    ${post.media_types.includes('video') ? `
                    <span class="inline-flex items-center px-2 py-1 bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/>
                        </svg>
                        Videos
                    </span>` : ''}
                    ${post.media_types.includes('audio') ? `
                    <span class="inline-flex items-center px-2 py-1 bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path>
                        </svg>
                        Audio
                    </span>` : ''}
                </div>` : ''
            }
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button ${!single ? `onclick="return PostManager.changeDirection(${post.post_id})"` : ''} class="flex items-center space-x-1 text-gray-500 hover:text-blue-500 dark:text-gray-800 dark:hover:text-blue-400 transition-colors" onclick="event.stopPropagation();" data-posts-id="${post.post_id}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                        <span class="comments-counter-${post.post_id}">${post.comments_count}</span>
                    </button>
                    <button class="flex items-center space-x-1 text-gray-500 hover:text-blue-500 dark:text-gray-800 dark:hover:text-blue-400 transition-colors" onclick="event.stopPropagation(); return PostManager.handleVote('posts', ${post.post_id}, 'up', ${post.user_id})" data-posts-id="${post.post_id}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                        <span data-posts-id-upvotes="${post.post_id}">${post.upvotes}</span>
                    </button>
                    <button class="flex items-center space-x-1 text-gray-500 hover:text-red-500 dark:text-gray-800 dark:hover:text-red-400 transition-colors" onclick="event.stopPropagation(); return PostManager.handleVote('posts', ${post.post_id}, 'down', ${post.user_id})" data-posts-id="${post.post_id}">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 10v2a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"/>
                        </svg>
                        <span data-posts-id-downvotes="${post.post_id}">${post.downvotes}</span>
                    </button>
                    <button ${!single ? `onclick="return PostManager.changeDirection(${post.post_id})"` : ''} class="flex items-center space-x-1 text-gray-500 dark:text-gray-800" onclick="event.stopPropagation();" data-posts-id="${post.post_id}">
                        <svg class="h-4 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                        <span>${post.views || 0} views</span>
                    </button>
                </div>
            </div>
        `;

        // Add click handler for post navigation
        if (!single) {
            const clickableElements = div.querySelectorAll('.post-header-clickable, .post-content-clickable');
            clickableElements.forEach(element => {
                element.addEventListener('click', (e) => {
                    if (!e.target.closest('.post-menu-button') && !e.target.closest('.post-context-menu')) {
                        PostManager.changeDirection(post.post_id);
                    }
                });
            });
        }

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
    async handleVote(section, recordId, direction, ownerId) {
        try {
            const response = await fetch(`${baseUrl}/api/posts/vote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ section, recordId, direction, token: AppState.getToken(), longitude, latitude, ownerId })
            });
            const data = await response.json();
            this.updateVoteCounts(recordId, data, section);
        } catch (error) {
            // AppState.showNotification(`Post ${direction}voted already.`, 'success');
        }
    },
    updateVoteCounts(postId, data, section) {
        const upvoteCount = document.querySelector(`[data-${section}-id-upvotes="${postId}"]`);
        const downvoteCount = document.querySelector(`[data-${section}-id-downvotes="${postId}"]`);
        upvoteCount.innerHTML = data.record.upvotes;
        downvoteCount.innerHTML = data.record.downvotes;
    },
    async handleReport(button, section) {
        const postId = button.closest('.post-card').querySelector(`[data-${section}-id]`).dataset.postId;
        try {
            await fetch(`${baseUrl}/api/posts/report/${postId}`, {
                method: 'POST',
                body: JSON.stringify({ token: AppState.getToken(), longitude, latitude, userUUID })
            });
            AppState.showNotification('Post reported successfully', 'success');
        } catch (error) {
            AppState.showNotification('Error reporting post', 'error');
        }
    },
    async handleBookmark(postId, isBookmarked) {
        try {
            const response = await fetch(`${baseUrl}/api/posts/bookmark/${postId}?userUUID=${userUUID}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ token: AppState.getToken() })
            });
            
            if (!response.ok) throw new Error('Failed to bookmark post');
            
            const data = await response.json();
            document.querySelector(`.save-post-${postId}`).innerHTML = data.message;
            this.hideContextMenu();
        } catch (error) {
            AppState.showNotification('Failed to save post', 'error');
        }
    },
    async handleDelete(item, postId) {
        try {
            let path = item === 'posts' ? '' : 'deletecomment/';
            let field = item === 'posts' ? 'post' : 'comment';
            const response = await fetch(`${baseUrl}/api/posts/${path}${postId}?userUUID=${userUUID}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ token: AppState.getToken() })
            });
            
            if (!response.ok) throw new Error('Failed to delete post');
            
            const data = await response.json();
            AppState.showNotification(data.message || 'Post deleted successfully', 'success');
            
            // Remove post from DOM
            $(`[data-${field}-id="${postId}"]`).remove();
            
            this.hideContextMenu();
        } catch (error) {
            AppState.showNotification('Failed to delete post', 'error');
        }
    },
    hideContextMenu() {
        const menus = document.querySelectorAll('.post-context-menu');
        menus.forEach(menu => menu.classList.add('hidden'));
    },
    setupPostInteractions() {
        // Add existing event listeners
        document.addEventListener('click', (e) => {
            if (e.target.matches('.vote-button')) {
                this.handleVote(e.target);
            }
        });

        // Add new context menu handlers
        document.addEventListener('click', (e) => {
            const menuButton = e.target.closest('.post-menu-button');
            const menu = e.target.closest('.post-context-menu');
            
            // If clicking on a menu button
            if (menuButton) {
                e.preventDefault();
                e.stopPropagation();
                
                const currentMenu = menuButton.nextElementSibling;
                const isCurrentMenuVisible = !currentMenu.classList.contains('hidden');
                
                // Hide all other menus first
                this.hideContextMenu();
                
                // Toggle this menu (show if it was hidden, keep hidden if it was visible)
                if (!isCurrentMenuVisible) {
                    currentMenu.classList.remove('hidden');
                }
                return;
            }
            
            // If clicking inside a menu, don't close it
            if (menu) {
                e.stopPropagation();
                return;
            }
            
            // Close all menus when clicking outside
            this.hideContextMenu();
        });

        // Close context menu on scroll
        window.addEventListener('scroll', () => {
            this.hideContextMenu();
        });
    }
};

const ImprovedPostCreationForm = {
    mediaRecorder: null,
    audioChunks: [],
    recordingTimer: null,
    recordingStartTime: null,
    uploadedFiles: [],
    MAX_RECORDING_TIME: 30, // 30 seconds
    MAX_IMAGE_SIZE: 5 * 1024 * 1024, // 2MB in bytes
    isRecording: false,
    isPaused: false,
    totalRecordingTime: 0,
    stream: null,
    max_content_length: 300,

    init() {
        this.form = document.getElementById('createPostFormUnique');
        this.textarea = document.getElementById('content');
        this.charCount = document.getElementById('charCount');
        this.fileUpload = document.getElementById('fileUpload');
        this.audioRecordBtn = document.getElementById('audioRecordBtn');
        this.audioStatus = document.getElementById('audioStatus');
        this.audioTimer = document.getElementById('audioTimer');
        this.emojiBtn = document.getElementById('emojiBtn');
        this.emojiPicker = document.getElementById('emojiPicker');
        this.mediaPreview = document.getElementById('mediaPreview');
        this.previewContainer = document.getElementById('previewContainer');
        this.audioPreview = document.getElementById('audioPreview');
        this.audioPlayer = document.getElementById('audioPlayer');
        this.submitBtn = document.getElementById('submitBtn');
        this.max_content_length = 300;

        this.formSetup();
    },
    formSetup() {
        // Character counter
        this.textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length}/${ImprovedPostCreationForm.max_content_length}`;
            
            if (length > ImprovedPostCreationForm.max_content_length) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });

        // File upload preview
        this.fileUpload.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            // Check if adding these files would exceed the limit
            if (ImprovedPostCreationForm.uploadedFiles?.length + files?.length > 4) {
                ImprovedPostCreationForm.showNotification('You can only upload up to 4 files. Please remove some files first.', 'error');
                return;
            }

            files.forEach((file, index) => {
                // Check file type
                if (!file.type.startsWith('image/') && !file.type.startsWith('video/')) {
                    ImprovedPostCreationForm.showNotification(`File "${file.name}" is not a valid image or video file.`, 'error');
                    return;
                }

                // Check file size based on type
                if (file.type.startsWith('image/') && file.size > ImprovedPostCreationForm.MAX_IMAGE_SIZE) {
                    ImprovedPostCreationForm.showNotification(`Image "${file.name}" is too large. Maximum size is 5MB.`, 'error');
                    return;
                }

                // Check file size for videos (10MB limit)
                if (file.type.startsWith('video/') && file.size > 20 * 1024 * 1024) {
                    ImprovedPostCreationForm.showNotification(`Video "${file.name}" is too large. Maximum size is 20MB.`, 'error');
                    return;
                }

                const fileId = Date.now() + index;
                
                if (file.type.startsWith('video/')) {
                    // Handle video files - generate thumbnail
                    ImprovedPostCreationForm.generateVideoThumbnail(file, fileId);
                } else {
                    // Handle image files - use existing logic
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        ImprovedPostCreationForm.uploadedFiles.push({
                            id: fileId,
                            file: file,
                            preview: e.target.result,
                            thumbnail: null // Images don't need separate thumbnails
                        });
                        
                        ImprovedPostCreationForm.createFilePreview(fileId, file, e.target.result);
                        ImprovedPostCreationForm.updateUploadButton();
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

        // Emoji picker
        this.emojiBtn.addEventListener('click', function() {
            ImprovedPostCreationForm.emojiPicker.classList.toggle('hidden');
        });

        // Add emoji to textarea
        document.querySelectorAll('.emoji-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const emoji = this.textContent;
                const cursorPos = ImprovedPostCreationForm.textarea.selectionStart;
                const textBefore = ImprovedPostCreationForm.textarea.value.substring(0, cursorPos);
                const textAfter = ImprovedPostCreationForm.textarea.value.substring(cursorPos);
                ImprovedPostCreationForm.textarea.value = textBefore + emoji + textAfter;
                ImprovedPostCreationForm.textarea.focus();
                ImprovedPostCreationForm.textarea.setSelectionRange(cursorPos + emoji.length, cursorPos + emoji.length);
                
                // Trigger input event for character counter
                ImprovedPostCreationForm.textarea.dispatchEvent(new Event('input'));
            });
        });

        // Close emoji picker when clicking outside
        document.addEventListener('click', function(e) {
            if (!ImprovedPostCreationForm.emojiBtn.contains(e.target) && !ImprovedPostCreationForm.emojiPicker.contains(e.target)) {
                ImprovedPostCreationForm.emojiPicker.classList.add('hidden');
            }
        });

        // Add click event listener to submit button as additional safeguard
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Trigger form submission manually
            ImprovedPostCreationForm.form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
            
            return false;
        });

        // Form submission
        this.form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            // Validate content
            if (!ImprovedPostCreationForm.textarea.value.trim() && ImprovedPostCreationForm.uploadedFiles.length === 0) {
                ImprovedPostCreationForm.showNotification('Please add some content or upload media before posting.', 'error');
                return false;
            }
            
            // Stop recording if it's currently active
            if (ImprovedPostCreationForm.mediaRecorder && ImprovedPostCreationForm.mediaRecorder.state === 'recording') {
                ImprovedPostCreationForm.stopRecording();
                // Wait a moment for the recording to finish processing
                setTimeout(() => {
                    ImprovedPostCreationForm.processFormSubmission();
                }, 500);
            } else {
                ImprovedPostCreationForm.processFormSubmission();
            }
            
            return false;
        });

        // Audio recording
        this.audioRecordBtn.addEventListener('click', function() {
            if (!ImprovedPostCreationForm.isRecording) {
                ImprovedPostCreationForm.startRecording();
            } else {
                ImprovedPostCreationForm.stopRecording();
            }
        });

        // Pause/Resume recording
        const audioPauseBtn = document.getElementById('audioPauseBtn');
        audioPauseBtn.addEventListener('click', function() {
            if (ImprovedPostCreationForm.isPaused) {
                ImprovedPostCreationForm.resumeRecording();
            } else {
                ImprovedPostCreationForm.pauseRecording();
            }
        });
    },

    createFilePreview(fileId, file, previewUrl) {
        const previewGrid = document.getElementById('imagePreviewGrid');
        previewGrid.classList.remove('hidden');

        const previewContainer = document.createElement('div');
        previewContainer.className = 'relative group';
        previewContainer.id = `preview-${fileId}`;

        const isVideo = file.type.startsWith('video/');
        
        previewContainer.innerHTML = `
            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden w-20 h-20">
                <img src="${previewUrl}" class="w-full h-full object-cover" alt="Preview">
                ${isVideo ? `
                    <div class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center">
                        <div class="bg-white bg-opacity-90 rounded-full p-1">
                            <svg class="w-4 h-4 text-gray-800" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="absolute top-1 right-1 bg-black bg-opacity-50 text-white text-xs px-1 py-0.5 rounded">
                        VID
                    </div>
                ` : ''}
            </div>
            <button type="button" class="remove-file absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 transition-colors opacity-0 group-hover:opacity-100" data-file-id="${fileId}">
                ×
            </button>
            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate">
                ${file.name}
            </div>
        `;

        previewGrid.appendChild(previewContainer);

        // Add remove functionality
        const removeBtn = previewContainer.querySelector('.remove-file');
        removeBtn.addEventListener('click', function() {
            ImprovedPostCreationForm.removeFile(fileId);
        });
    },

    removeFile(fileId) {
        // Remove from uploadedFiles array
        this.uploadedFiles = this.uploadedFiles.filter(file => file.id !== fileId);
        
        // Remove from DOM
        const previewElement = document.getElementById(`preview-${fileId}`);
        if (previewElement) {
            previewElement.remove();
        }

        // Hide grid if no files
        if (this.uploadedFiles.length === 0) {
            document.getElementById('imagePreviewGrid').classList.add('hidden');
        }

        this.updateUploadButton();
    },

    updateUploadButton() {
        const uploadLabel = fileUpload.parentElement;
        const remainingSlots = 4 - this.uploadedFiles.length;
        
        if (remainingSlots === 0) {
            uploadLabel.classList.add('opacity-50', 'cursor-not-allowed');
            uploadLabel.classList.remove('hover:bg-gray-200');
        } else {
            uploadLabel.classList.remove('opacity-50', 'cursor-not-allowed');
            uploadLabel.classList.add('hover:bg-gray-200');
        }
    },

    // Generate thumbnail from video file
    generateVideoThumbnail(videoFile, fileId) {
        const video = document.createElement('video');
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        
        // Set canvas dimensions for thumbnail
        canvas.width = 320;
        canvas.height = 240;
        
        video.onloadedmetadata = () => {
            // Seek to 1 second or 25% of video duration (whichever is smaller)
            const seekTime = Math.min(1, video.duration * 0.25);
            video.currentTime = seekTime;
        };
        
        video.onseeked = () => {
            // Draw video frame to canvas
            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            
            // Convert canvas to blob
            canvas.toBlob((thumbnailBlob) => {
                // Create thumbnail file
                const thumbnailFile = new File([thumbnailBlob], `thumbnail_${videoFile.name}.jpg`, { 
                    type: 'image/jpeg' 
                });
                
                // Create preview URL for display
                const previewUrl = URL.createObjectURL(thumbnailBlob);
                
                // Add to uploaded files array
                this.uploadedFiles.push({
                    id: fileId,
                    file: videoFile,
                    preview: previewUrl,
                    thumbnail: thumbnailFile
                });
                
                // Create preview in UI
                this.createFilePreview(fileId, videoFile, previewUrl);
                this.updateUploadButton();
                
                // Clean up
                URL.revokeObjectURL(video.src);
            }, 'image/jpeg', 0.8); // 80% quality
        };
        
        video.onerror = () => {
            this.showNotification(`Failed to generate thumbnail for video "${videoFile.name}"`, 'error');
        };
        
        // Load video file
        video.src = URL.createObjectURL(videoFile);
        video.load();
    },

    async startRecording() {
        try {
            this.stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            this.mediaRecorder = new MediaRecorder(this.stream);
            this.audioChunks = [];
            this.totalRecordingTime = 0;

            this.mediaRecorder.ondataavailable = (event) => {
                this.audioChunks.push(event.data);
            };

            this.mediaRecorder.onstop = () => {
                const audioBlob = new Blob(this.audioChunks, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(audioBlob);
                this.audioPlayer.src = audioUrl;
                this.audioPreview.classList.remove('hidden');
                
                // Stop all tracks
                if (this.stream) {
                    this.stream.getTracks().forEach(track => track.stop());
                    this.stream = null;
                }
            };

            this.mediaRecorder.start();
            this.isRecording = true;
            this.isPaused = false;
            this.recordingStartTime = Date.now();
            
            // Update UI - Change to stop icon
            this.audioRecordBtn.innerHTML = `
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                </svg>
            `;
            this.audioRecordBtn.classList.add('bg-red-500', 'text-white');
            this.audioRecordBtn.classList.remove('bg-red-100', 'text-red-600');
            document.getElementById('audioPauseBtn').classList.remove('hidden');
            this.audioStatus.textContent = '';
            this.audioTimer.classList.remove('hidden');
            
            this.recordingTimer = setInterval(() => this.updateTimer(), 1000);
        } catch (error) {
            console.error('Error accessing microphone:', error);
            this.showNotification('Unable to access microphone. Please check permissions.', 'error');
        }
    },

    pauseRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state === 'recording') {
            this.mediaRecorder.pause();
            this.isPaused = true;
            
            // Calculate total recording time so far
            this.totalRecordingTime += Math.floor((Date.now() - this.recordingStartTime) / 1000);
            
            // Update UI - Change pause button to play icon
            const audioPauseBtn = document.getElementById('audioPauseBtn');
            audioPauseBtn.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 0 1 0 1.971l-11.54 6.347a1.125 1.125 0 0 1-1.667-.985V5.653z" />
                </svg>
            `;
            this.audioStatus.textContent = 'Paused';
            this.audioRecordBtn.classList.add('bg-gray-500');
            this.audioRecordBtn.classList.remove('bg-red-500');
            
            clearInterval(this.recordingTimer);
        }
    },

    resumeRecording() {
        if (this.mediaRecorder && this.mediaRecorder.state === 'paused') {
            this.mediaRecorder.resume();
            this.isPaused = false;
            
            // Reset recording start time for the resumed session
            this.recordingStartTime = Date.now();
            
            // Update UI - Change play button back to pause icon
            const audioPauseBtn = document.getElementById('audioPauseBtn');
            audioPauseBtn.innerHTML = `
                <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            `;
            this.audioStatus.textContent = '';
            this.audioRecordBtn.classList.remove('bg-gray-500');
            this.audioRecordBtn.classList.add('bg-red-500');
            
            this.recordingTimer = setInterval(() => this.updateTimer(), 1000);
        }
    },

    stopRecording() {
        if (this.mediaRecorder && (this.mediaRecorder.state === 'recording' || this.mediaRecorder.state === 'paused')) {
            this.mediaRecorder.stop();
            this.isRecording = false;
            this.isPaused = false;
            
            // Update UI - Change back to microphone icon
            this.audioRecordBtn.innerHTML = `
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                </svg>
            `;
            this.audioRecordBtn.classList.remove('bg-red-500', 'bg-gray-500', 'text-white');
            this.audioRecordBtn.classList.add('bg-red-100', 'text-red-600');
            document.getElementById('audioPauseBtn').classList.add('hidden');
            this.audioStatus.textContent = 'Record new audio';
            this.audioTimer.classList.add('hidden');
            
            clearInterval(this.recordingTimer);
        }
    },

    updateTimer() {
        const currentSessionTime = Math.floor((Date.now() - this.recordingStartTime) / 1000);
        const totalTime = this.totalRecordingTime + currentSessionTime;
        
        const minutes = Math.floor(totalTime / 60);
        const seconds = totalTime % 60;
        this.audioTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        // Check if recording time limit reached
        if (totalTime >= this.MAX_RECORDING_TIME) {
            this.showNotification('Recording stopped automatically (30 second limit reached).', 'info');
            this.stopRecording();
        }
    },
    
    processFormSubmission() {
        // Disable submit button
        ImprovedPostCreationForm.submitBtn.disabled = true;
        ImprovedPostCreationForm.submitBtn.innerHTML = `
            <span class="flex items-center space-x-1.5 sm:space-x-2 text-sm sm:text-base font-medium">
                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                <span>Sharing...</span>
              </span>
        `;

        // Prepare form data
        const formData = new FormData();
        formData.append('content', ImprovedPostCreationForm.textarea.value.trim());
        
        // Add uploaded files
        ImprovedPostCreationForm.uploadedFiles.forEach((fileData, index) => {
            formData.append(`media[${index}]`, fileData.file);
            
            // Add thumbnail if it exists (for videos)
            if (fileData.thumbnail) {
                formData.append(`thumbnails[${index}]`, fileData.thumbnail);
            }
        });

        // Add audio if recorded
        if (ImprovedPostCreationForm?.audioPlayer?.src && ImprovedPostCreationForm?.audioPlayer?.src !== '') {
            // Convert audio blob to file
            fetch(ImprovedPostCreationForm?.audioPlayer?.src)
                .then(res => res.blob())
                .then(blob => {
                    const audioFile = new File([blob], 'audio-message.wav', { type: 'audio/wav' });
                    formData.append('audio', audioFile);
                    ImprovedPostCreationForm?.submitFormData(formData);
                });
        } else {
            ImprovedPostCreationForm?.submitFormData(formData);
        }
    },

    submitFormData(formData) {
        formData.append('longitude', longitude);
        formData.append('latitude', latitude);
        formData.append('token', AppState.getToken());
        formData.append('userUUID', userUUID);
        // Send AJAX request to API endpoint
        fetch('/api/posts/create', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.status == 'success') {
                PostManager.showUnreadPosts();
                // Show success message
                AppState.showNotification('Post created successfully!', 'success');
                ImprovedPostCreationForm.resetForm();
                const feedContainer = document.getElementById('feedContainer');
                const postElement = PostManager.createPostElement(data.record);
                feedContainer.insertBefore(postElement, feedContainer.firstChild);
                PostManager.closeCreateModal();
                PostManager.loadedPostIds.push(data.record.post_id);
                PostManager.currentPage = data.record.post_id;
            } else {
                // Show error message
                showNotification(data.message || 'Failed to create post. Please try again.', 'error');
                ImprovedPostCreationForm.submitBtn.disabled = false;
            }
            ImprovedPostCreationForm.submitBtn.innerHTML = `
                    <span class="flex items-center space-x-1.5 sm:space-x-2 text-sm sm:text-base font-medium">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span>Share</span>
                    </span>
                `;
        })
        .catch(error => {;
            showNotification('An error occurred while creating the post. Please try again.', 'error');
            ImprovedPostCreationForm.submitBtn.disabled = false;
            ImprovedPostCreationForm.submitBtn.innerHTML = `
                <span class="flex items-center space-x-1.5 sm:space-x-2 text-sm sm:text-base font-medium">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <span>Share</span>
                </span>
            `;
        });
    },

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        // Set background color based on type
        if (type === 'success') {
            notification.classList.add('bg-green-500', 'text-white');
        } else if (type === 'error') {
            notification.classList.add('bg-red-500', 'text-white');
        } else {
            notification.classList.add('bg-blue-500', 'text-white');
        }
        
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button type="button" class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    },

    resetForm() {
        // Reset form fields
        this.form.reset();
        this.textarea.value = '';
        this.charCount.textContent = `0/${this.max_content_length}`;
        
        // Clear uploaded files
        this.uploadedFiles = [];
        document.getElementById('imagePreviewGrid').innerHTML = '';
        document.getElementById('imagePreviewGrid').classList.add('hidden');
        
        // Clear audio
        audioPlayer.src = '';
        audioPreview.classList.add('hidden');
        
        // Reset upload button 
        this.updateUploadButton();
        
        // Re-enable submit button
        this.submitBtn.disabled = false;
        this.submitBtn.innerHTML = `
            <span class="flex items-center space-x-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                <span>Post</span>
            </span>`;
    }

};

// Authentication Manager
const AuthManager = {
    init() {
        this.loginUUID();
        this.loginCheck();
        this.setupAuthForms();
        this.setupPasswordReset();
    },

    setupAuthForms() {
        // Login Form Handler
        $('#loginForm').on('submit', (e) => {
            e.preventDefault();

            // add loading state to button
            $('#loginButton')
                .prop('disabled', true)
                .html('<span class="loading-spinner"></span> Logging in...');

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
                    longitude,
                    latitude,
                    userUUID,
                    webapp: true,
                    remember_me: rememberMe
                }
            });

            if (response.success) {
                // Store user data and token
                localStorage.setItem('user', JSON.stringify(response.data));
                localStorage.setItem('token', response.data.token);
                document.cookie = `user_token=${response.data.token}; path=/;`;
                
                // Update AppState
                AppState.user = response.data;
                
                // Show success message
                AppState.showNotification('Login successful!', 'success');
                
                // Redirect to feed
                window.location.href = `${baseUrl}/dashboard`;
            }
        } catch (error) {
            $('#loginButton')
                .prop('disabled', false)
                .html(`<span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Sign in`);
            AppState.showNotification(error.responseJSON?.message || 'Login failed. Please try again.', 'error');
        }
    },

    async handleSignup() {
        const fullName = $('#full_name').val();
        const username = $('#username').val();
        const email = $('#email').val();
        const password = $('#password').val();
        const confirmPassword = $('#password_confirm').val();
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
                url: `${baseUrl}/api/auth/register`,
                method: 'POST',
                data: {
                    username,
                    email,
                    password,
                    longitude,
                    latitude,
                    userUUID,
                    full_name: fullName,
                    password_confirm: confirmPassword,
                    terms_accepted: termsAccepted
                }
            });

            if (response.success) {
                AppState.showNotification('Account created successfully! Please check your email to verify your account.', 'success');
                // Redirect to login page
                localStorage.setItem('user', JSON.stringify(response.data));
                localStorage.setItem('token', response.data.token);
                window.location.href = `${baseUrl}`;
            }
        } catch (error) {
            let parsedError = JSON.parse(error.responseText);
            if(typeof parsedError?.data === 'object') {
                $.each(parsedError?.data, (key, value) => {
                    AppState.showNotification(value, 'error', 6000);
                });
            } else {
                AppState.showNotification(parsedError?.data || 'Failed to create account. Please try again.', 'error', 6000);
            }
        }
    },

    async handleForgotPassword() {
        const email = $('#email').val();

        try {
            const response = await $.ajax({
                url: `${baseUrl}/api/auth/forgot-password`,
                method: 'POST',
                data: { email, longitude, latitude, userUUID }
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
    async loginUUID() {
        userUUID = localStorage.getItem('userUUID');
        if(!userUUID) {
            userUUID = document.cookie.split('; ')?.find(row => row.startsWith('userUUID='))?.split('=')[1];
        }
        if(!userUUID) {
            userUUID = AppState.generateUUID();
            document.cookie = `userUUID=${userUUID}; path=/;`;
            localStorage.setItem('userUUID', userUUID);
        }
        return userUUID;
    },
    async loginCheck() {
        if($('#loginForm').length > 0) {
            let token = localStorage.getItem('token');
            if(!token) {
                token = document.cookie.split('; ')?.find(row => row.startsWith('user_token='))?.split('=')[1];
            }
            if(!token) return;
            $.post(`${baseUrl}/api/auth/confirm`, { token: token, webapp: true, longitude, latitude, userUUID }, (response) => {
                if(response.success) {
                    window.location.href = `${baseUrl}`;
                }
            }).catch((error) => {
                localStorage.removeItem('token');
            });
        }
    },
    logout() {
        // Clear local storage
        localStorage.removeItem('user');
        localStorage.removeItem('token');
        AppState.clearLocationData();
        
        // Update AppState
        AppState.user = null;
        
        // Show notification
        AppState.showNotification('Logged out successfully', 'success');
        
        // Redirect to login page
        window.location.href = `${baseUrl}/login`;
    }
};

// Notification Manager
const NotificationManager = {
    init() {
        this.setupNotificationDropdown();
        this.setupNotificationActions();
        this.startPolling();
    },

    setupNotificationDropdown() {
        const dropdown = document.querySelector('[x-data]');
        if (!dropdown) return;

        // Handle mark all as read
        const markAllReadBtn = dropdown.querySelector('[title="Mark all as read"]');
        if (markAllReadBtn) {
            markAllReadBtn.addEventListener('click', async (e) => {
                e.preventDefault();
                await this.markAllAsRead();
            });
        }
    },

    setupNotificationActions() {
        // Handle mark as read buttons
        document.querySelectorAll('[title="Mark as read"]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const notificationId = e.target.closest('[data-notification-id]')?.dataset.notificationId;
                if (notificationId) {
                    await this.markAsRead(notificationId);
                }
            });
        });

        // Handle delete buttons
        document.querySelectorAll('[title="Delete notification"]').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                e.preventDefault();
                const notificationId = e.target.closest('[data-notification-id]')?.dataset.notificationId;
                if (notificationId) {
                    await this.deleteNotification(notificationId);
                }
            });
        });
    },

    async markAsRead(notificationId) {
        try {
            const response = await fetch(`${baseUrl}/api/notifications/mark-read/${notificationId}?userUUID=${userUUID}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            if (data.success) {
                // Update UI
                const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (notification) {
                    notification.classList.remove('bg-blue-50', 'dark:bg-blue-900/20');
                    const markReadBtn = notification.querySelector('[title="Mark as read"]');
                    if (markReadBtn) {
                        markReadBtn.remove();
                    }
                }
            }
        } catch (error) {
        }
    },

    async markAllAsRead() {
        try {
            const response = await fetch(`${baseUrl}/api/notifications/mark-all-read?userUUID=${userUUID}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            if (data.success) {
                // Update UI
                document.querySelectorAll('.bg-blue-50, .dark\\:bg-blue-900\\/20').forEach(el => {
                    el.classList.remove('bg-blue-50', 'dark:bg-blue-900/20');
                });
                document.querySelectorAll('[title="Mark as read"]').forEach(btn => btn.remove());
            }
        } catch (error) {
        }
    },

    async deleteNotification(notificationId) {
        try {
            const response = await fetch(`${baseUrl}/api/notifications/${notificationId}?userUUID=${userUUID}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();
            if (data.success) {
                // Remove notification from UI
                const notification = document.querySelector(`[data-notification-id="${notificationId}"]`);
                if (notification) {
                    notification.remove();
                }
            }
        } catch (error) {
        }
    },

    async refreshNotifications() {
        try {
            if(!Boolean(AppState.user)) {
                return;
            }
            const response = $.get(`${baseUrl}/api/notifications/recent`, {
                token: AppState.getToken(),
                noloc: true,
                userUUID
            }).then((response) => {
                const container = document.querySelector('.notifications-container');
                if (container) {
                    const badge = document.querySelector('.notification-badge');
                    badge.style.display = response.data?.unread_count > 0 ? 'block' : 'none';
                    // Update notifications list
                    this.renderNotifications(response.data?.notifications || [], container);
                }
            })
        } catch (error) {
            console.error('Error refreshing notifications:', error);
        }
    },

    renderNotifications(notifications, container) {
        if (notifications.length === 0) {
            container.innerHTML = `
                <div class="px-4 py-3 text-center text-sm text-gray-500 dark:text-gray-800">
                    No new notifications
                </div>
            `;
            return;
        }

        container.innerHTML = notifications.map(notification => `
            <a href="${notification.link}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 ${notification.read ? '' : 'bg-blue-50 dark:bg-blue-900/20'}" data-notification-id="${notification.id}">
                <div class="flex items-start space-x-3">
                    <div class="flex-shrink-0">
                        ${this.getNotificationIcon(notification.type)}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm text-gray-900 dark:text-white">
                            ${notification.message}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-800">
                            ${notification.time_ago}
                        </p>
                    </div>
                </div>
            </a>
        `).join('');

        this.setupNotificationActions();
    },

    getNotificationIcon(type) {
        const icons = {
            like: `
                <div class="w-8 h-8 rounded-full bg-red-100 dark:bg-red-900/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                </div>
            `,
            comment: `
                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                </div>
            `,
            follow: `
                <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/20 flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
            `
        };

        return icons[type] || `
            <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                <svg class="w-4 h-4 text-gray-600 dark:text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        `;
    },

    startPolling() {
        // update the unread count and refresh the notifications
        this.refreshNotifications();
        // Poll for new notifications every 30 seconds
        setInterval(() => {
            this.refreshNotifications();
        }, 60000);
    },

    show(message, type = 'info', duration = 3000) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 transform transition-all duration-300 translate-x-full opacity-0`;
        
        // Set notification content based on type
        const icons = {
            success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>`,
            error: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>`,
            warning: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>`,
            info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>`
        };

        // Set background color based on type
        const bgColors = {
            success: 'bg-green-500',
            error: 'bg-red-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500'
        };

        notification.innerHTML = `
            <div class="flex items-center p-4 mb-4 text-white rounded-lg shadow-lg ${bgColors[type]}">
                <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-lg">
                    ${icons[type]}
                </div>
                <div class="ml-3 text-sm font-normal">${message}</div>
                <button type="button" class="ml-auto -mx-1.5 -my-1.5 text-white hover:text-gray-200 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-white/10 inline-flex h-8 w-8 items-center justify-center">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;

        // Add to document
        document.body.appendChild(notification);

        // Add close button functionality
        const closeButton = notification.querySelector('button');
        closeButton.addEventListener('click', () => {
            this.hideNotification(notification);
        });

        // Show notification with animation
        requestAnimationFrame(() => {
            notification.classList.remove('translate-x-full', 'opacity-0');
        });

        // Auto hide after duration
        setTimeout(() => {
            this.hideNotification(notification);
        }, duration);
    },

    hideNotification(notification) {
        notification.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }
};

// Post Comment Manager
const PostCommentManager = {
    commentsList: [],
    postId: null,
    postOwnerId: null,
    sendingComment: false,
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

                this.sendingComment = true;
                const postId = window.location.pathname.split('/').pop();
                const response = await fetch('/api/posts/comment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        token: AppState.getToken(),
                        postId: postId,
                        content: content
                    })
                });

                if (!response.ok) {
                    throw new Error('Failed to post comment');
                }

                const data = await response.json();

                const commentsLoading = document?.getElementById('commentsLoading');
                if(commentsLoading) {
                    commentsLoading.innerHTML = '';
                }

                const commentsContainer = document.getElementById('commentsList');
                commentsContainer.appendChild(PostManager.createCommentElement(data.record));
                
                // Clear the input
                commentInput.value = '';
                submitButton.disabled = true;
                submitButton.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Post
                    </div>`;

                // Show success notification
                NotificationManager.show('Comment posted successfully', 'success');

                $.post(`${baseUrl}/api/posts/notify`, { token: AppState.getToken(), postId: postId });
                this.sendingComment = false;
            } catch (error) {
                submitButton.disabled = false;
                submitButton.innerHTML = 'Post';
                NotificationManager.show('Failed to post comment. Please try again.', 'error');
            }
        });

        this.getCommentsList();
    },
    getCommentsFromServer() {
        if(this.sendingComment) {
            return;
        }
        $.get(`${baseUrl}/api/posts/comments`, {
            token: AppState.getToken(),
            postId: this.postId,
            longitude,
            latitude,
            userUUID
        }).then(data => {
            if(data.status == 'success') {
                const commentsContainer = document.getElementById('commentsList');
                if(commentsContainer) {
                    $(`span[class^="comments-counter-${this.postId}"]`).text(data.data.length);
                    data.data.forEach(comment => {
                        if(!this.commentsList.includes(comment.comment_id)){
                            commentsContainer.appendChild(PostManager.createCommentElement(comment));
                        }
                    });
                    $(`span[id="commentCount"]`).html(`(${data.data.length})`);
                }
            }
        });
    },
    getCommentsList() {
        // get comments list from server every 5 seconds 
        setInterval(() => {
            this.getCommentsFromServer();
        }, 5000);
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
                formData.append('token', AppState.getToken());
                formData.append('userUUID', userUUID);
                formData.append('noloc', true);
                const response = await fetch('/api/users/update', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();
                
                if (data.status == 'success') {
                    NotificationManager.show('Profile updated successfully', 'success');
                } else {
                    throw new Error(data.message || 'Failed to update profile');
                }
            } catch (error) {
                NotificationManager.show(error.message || 'Failed to update profile', 'error');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    },

    setupSettingsToggles() {
        const toggles = document.querySelectorAll('[data-setting]');
        
        toggles.forEach(toggle => {
            toggle.addEventListener('click', async () => {
                const setting = toggle.dataset.setting;
                const currentValue = parseInt(toggle.dataset.value);
                const newValue = currentValue === 1 ? 0 : 1;
                
                // Update UI immediately
                toggle.dataset.value = newValue;
                toggle.classList.toggle('bg-blue-600');
                toggle.classList.toggle('bg-gray-200', 'dark:bg-gray-700');
                toggle.setAttribute('aria-checked', newValue === '1');
                
                const toggleSpan = toggle.querySelector('span');
                toggleSpan.classList.toggle('translate-x-5');
                toggleSpan.classList.toggle('translate-x-0');
                
                const iconSpan = toggleSpan.querySelector('span');
                iconSpan.classList.toggle('opacity-0');
                iconSpan.classList.toggle('opacity-100');

                try {
                    const response = await fetch(`${baseUrl}/api/users/update?userUUID=${userUUID}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            setting: setting,
                            value: newValue,
                            token: AppState.getToken(),
                            userUUID: userUUID,
                            noloc: true
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Failed to update setting');
                    }

                    const data = await response.json();
                    
                    if (data.status == 'success') {
                        // If it's dark mode setting, update the theme
                        if (setting === 'dark_mode') {
                            AppState.setTheme(newValue === '1' ? 'dark' : 'light');
                        }
                        
                        NotificationManager.show('Setting updated successfully', 'success');
                    } else {
                        throw new Error(data.message || 'Failed to update setting');
                    }
                } catch (error) {
                    // Revert UI changes on error
                    toggle.dataset.value = currentValue;
                    toggle.classList.toggle('bg-blue-600');
                    toggle.classList.toggle('bg-gray-200', 'dark:bg-gray-700');
                    toggle.setAttribute('aria-checked', currentValue === 1);
                    
                    const toggleSpan = toggle.querySelector('span');
                    toggleSpan.classList.toggle('translate-x-5');
                    toggleSpan.classList.toggle('translate-x-0');
                    
                    const iconSpan = toggleSpan.querySelector('span');
                    iconSpan.classList.toggle('opacity-0');
                    iconSpan.classList.toggle('opacity-100');

                    NotificationManager.show(error.message, 'error');
                }
            });
        });
    },

    setupProfilePictureUpload() {
        const uploadButton = document.querySelector('button[type="button"][id="mediaUpload"]');
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
                formData.append('token', AppState.getToken());
                formData.append('userUUID', userUUID);
                formData.append('noloc', true);

                try {
                    const response = await fetch('/api/users/update', {
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
                    NotificationManager.show(error.message || 'Failed to update profile picture', 'error');
                }
            });

            document.body.appendChild(input);
            input.click();
            document.body.removeChild(input);
        });
    }
};

// Password Toggle Functionality
const PasswordToggle = {
  init() {
    this.setupPasswordToggles();
  },

  setupPasswordToggles() {
    const toggleButtons = document.querySelectorAll('.password-toggle-btn');
    
    toggleButtons.forEach(button => {
      // Ensure button is always visible and interactive
      button.style.display = 'flex';
      button.style.visibility = 'visible';
      button.style.opacity = '1';
      button.style.pointerEvents = 'auto';
      
      button.addEventListener('click', (e) => {
        e.preventDefault();
        e.stopPropagation();
        
        const targetId = button.getAttribute('data-target');
        const input = document.getElementById(targetId);
        const eyeClosed = button.querySelector('.password-eye-closed');
        const eyeOpen = button.querySelector('.password-eye-open');
        
        if (input && eyeClosed && eyeOpen) {
          this.togglePasswordVisibility(input, eyeClosed, eyeOpen, button);
        }
      });

      // Add keyboard support for accessibility
      button.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          button.click();
        }
      });

      // Add ARIA attributes for accessibility
      button.setAttribute('role', 'button');
      button.setAttribute('tabindex', '0');
      button.setAttribute('aria-label', 'Toggle password visibility');

      // Ensure button remains visible when input is focused
      const targetId = button.getAttribute('data-target');
      const input = document.getElementById(targetId);
      if (input) {
        input.addEventListener('focus', () => {
          button.style.visibility = 'visible';
          button.style.opacity = '1';
          button.style.pointerEvents = 'auto';
        });
        
        input.addEventListener('blur', () => {
          button.style.visibility = 'visible';
          button.style.opacity = '1';
          button.style.pointerEvents = 'auto';
        });
      }
    });
  },

  togglePasswordVisibility(input, eyeClosed, eyeOpen, button) {
    const isPassword = input.type === 'password';
    
    if (isPassword) {
      // Show password
      input.type = 'text';
      eyeClosed.classList.add('hidden');
      eyeOpen.classList.remove('hidden');
      
      // Add visual feedback
      button.classList.add('text-blue-600', 'dark:text-blue-400');
      button.setAttribute('aria-label', 'Hide password');
      
      // Auto-hide password after 3 seconds for security
      setTimeout(() => {
        if (input.type === 'text') {
          this.togglePasswordVisibility(input, eyeClosed, eyeOpen, button);
        }
      }, 3000);
      
    } else {
      // Hide password
      input.type = 'password';
      eyeClosed.classList.remove('hidden');
      eyeOpen.classList.add('hidden');
      
      // Remove visual feedback
      button.classList.remove('text-blue-600', 'dark:text-blue-400');
      button.setAttribute('aria-label', 'Show password');
    }
  }
};

(function() {
    const pageLoader = document.getElementById('pageLoader');
    const progressBar = document.getElementById('progressBar');
    let progress = 0;
    let progressInterval;
    let isLoaderHidden = false;
    
    function hideLoader() {
      if (isLoaderHidden) return;
      isLoaderHidden = true;
      
      if (progressInterval) {
        clearInterval(progressInterval);
      }
      
      // Complete the progress bar
      if (progressBar) {
        progressBar.style.width = '100%';
      }
      
      // Hide loader with fade out animation
      setTimeout(() => {
        if (pageLoader) {
          pageLoader.style.transition = 'opacity 0.5s ease-out';
          pageLoader.style.opacity = '0';
          
          setTimeout(() => {
            if (pageLoader && pageLoader.parentNode) {
              pageLoader.parentNode.removeChild(pageLoader);
            }
          }, 500);
        }
      }, 300);
    }
    
    // Start progress simulation when DOM is ready
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', startLoader);
    } else {
      startLoader();
    }
    
    function startLoader() {
      if (!pageLoader || !progressBar) return;
      
      // Simulate loading progress
      progressInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 90) progress = 90;
        progressBar.style.width = progress + '%';
      }, 100);
      
      // Hide loader when page is fully loaded
      if (document.readyState === 'complete') {
        hideLoader();
      } else {
        window.addEventListener('load', hideLoader);
      }
      
      // Fallback: Hide loader after 3 seconds if load event doesn't fire
      setTimeout(() => {
        if (!isLoaderHidden) {
          hideLoader();
        }
      }, 3000);
    }
})();

const AudioVideoManager = {

    init() {
        this.begin();
    },
    begin() {
        if(!Boolean(AppState.user)) {
            return;
        }
        this.startAudioRecording(
            (recorder, stream) => {
                window.currentRecorder = recorder;
            },
            (audioBlob) => {
                this.uploadAudio(audioBlob);
            }
        );
    },

    // Audio Recording Handler
    startAudioRecording(onDataAvailable, onStop) {
        let mediaRecorder, audioChunks = [];

        MicrophoneManager.getStream()
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);
                mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    onStop(audioBlob);
                };
                mediaRecorder.start();
                onDataAvailable && onDataAvailable(mediaRecorder, stream);
            })
            .catch(error => {
                console.error('Error accessing microphone:', error);
                NotificationManager.show('Microphone access is required for recording', 'error');
            });
    },

    // Video Call Handler (WebRTC)
    async startVideoCall(localVideoElem, remoteVideoElem, signalingSend, signalingOnMessage) {
        const peer = new RTCPeerConnection();
        const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        stream.getTracks().forEach(track => peer.addTrack(track, stream));
        localVideoElem.srcObject = stream;

        peer.ontrack = e => {
            if (remoteVideoElem.srcObject !== e.streams[0]) {
                remoteVideoElem.srcObject = e.streams[0];
            }
        };

        // Signaling
        peer.onicecandidate = e => {
            if (e.candidate) signalingSend({ candidate: e.candidate });
        };

        signalingOnMessage(async msg => {
            if (msg.offer) {
                await peer.setRemoteDescription(new RTCSessionDescription(msg.offer));
                const answer = await peer.createAnswer();
                await peer.setLocalDescription(answer);
                signalingSend({ answer });
            } else if (msg.answer) {
                await peer.setRemoteDescription(new RTCSessionDescription(msg.answer));
            } else if (msg.candidate) {
                await peer.addIceCandidate(new RTCIceCandidate(msg.candidate));
            }
        });

        // To start call (caller):
        async function call() {
            const offer = await peer.createOffer();
            await peer.setLocalDescription(offer);
            signalingSend({ offer });
        }

        return { peer, stream, call };
    },

    uploadAudio(audioBlob) {
        const formData = new FormData();
        formData.append('audio', audioBlob, 'recording.webm');

        fetch('/api/upload-audio', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.audioUrl) {
                // Optionally replay the uploaded audio
                this.replayAudio(data.audioUrl);
            } else {
                NotificationManager.show('Upload failed', 'error');
            }
        })
        .catch(() => NotificationManager.show('Upload error', 'error'));
    },

    replayAudio(audioUrl) {
        const audio = document.createElement('audio');
        audio.controls = true;
        audio.src = audioUrl;
        document.getElementById('audioReplayContainer').innerHTML = '';
        document.getElementById('audioReplayContainer').appendChild(audio);
        audio.play();
    }
}

// Initialize the app
document.addEventListener('DOMContentLoaded', () => {
    AppState.init();
    AuthManager.init();
    PostManager.init();
    PostCommentManager.init();
    ProfileManager.init();
    MediaManager.init();
    NotificationManager.init(); 
    ImprovedPostCreationForm.init();
    AppState.logPageView();
    PasswordToggle.init();
});

if($(`div[id="postMediaPreview"]`)) {
    new MediaDisplay();
}