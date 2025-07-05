<div class="min-h-[calc(100vh-100px)] bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-1xl font-bold text-gray-900 dark:text-white">Feedbacks</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Review and manage user feedbacks</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="FeedbackManager.refreshData()" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Feedback</p>
                        <p id="totalFeedback" class="text-2xl font-bold text-gray-900 dark:text-white">0</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                        <p id="pendingFeedback" class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">0</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">In Progress</p>
                        <p id="inProgressFeedback" class="text-2xl font-bold text-blue-600 dark:text-blue-400">0</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Resolved</p>
                        <p id="resolvedFeedback" class="text-2xl font-bold text-green-600 dark:text-green-400">0</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input type="text" id="searchInput" placeholder="Search feedback..." 
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                    <select id="typeFilter" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">All Types</option>
                        <option value="suggestion">Suggestion</option>
                        <option value="bug_report">Bug Report</option>
                        <option value="improvement">Improvement</option>
                        <option value="general">General</option>
                        <option value="praise">Praise</option>
                        <option value="complaint">Complaint</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                    <select id="priorityFilter" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Feedback List -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Feedback</h3>
            </div>
            <div id="feedbackList" class="divide-y divide-gray-200 dark:divide-gray-700"></div>
        </div>

        <!-- Loading State -->
        <div id="loadingState" class="hidden text-center py-8">
            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Loading feedback...</p>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="hidden text-center py-8">
            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No feedback found</h3>
            <p class="text-gray-600 dark:text-gray-400">Try adjusting your filters or search terms</p>
        </div>
    </div>
</div>

<!-- Feedback Detail Modal -->
<div id="feedbackModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden">
    <div class="min-h-screen px-4 text-center flex items-center justify-center">
        <div class="inline-block w-full max-w-4xl p-6 sm:p-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Feedback Details</h3>
                <button onclick="FeedbackManager.closeModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div id="feedbackModalContent">
                <!-- Modal content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<div id="additionalHeight" class="h-20"></div>

<script>
const FeedbackManager = {
    currentFilters: {
        search: '',
        type: '',
        priority: '',
        status: ''
    },

    init() {
        this.loadData();
        this.setupEventListeners();
    },

    setupEventListeners() {
        // Search input
        document.getElementById('searchInput').addEventListener('input', (e) => {
            this.currentFilters.search = e.target.value;
            this.debounce(this.loadData.bind(this), 300)();
        });

        // Filter selects
        ['typeFilter', 'priorityFilter', 'statusFilter'].forEach(id => {
            document.getElementById(id).addEventListener('change', (e) => {
                this.currentFilters[id.replace('Filter', '')] = e.target.value;
                this.loadData();
            });
        });
    },

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    async loadData() {
        this.showLoading();
        
        try {

            $(`div[id^="feedbackList"]`).html('');

            const params = new URLSearchParams(this.currentFilters);
            const response = await fetch(`/api/feedback/admin?${params}&token=${AppState.getToken()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load feedback data');
            }

            const data = await response.json();
            this.updateStatistics(data.data.stats);
            this.renderFeedbackList(data.data.feedback);
            
        } catch (error) {
            console.error('Error loading feedback:', error);
            this.showError('Failed to load feedback data');
        } finally {
            this.hideLoading();
        }
    },

    updateStatistics(stats) {
        document.getElementById('totalFeedback').textContent = stats.total || 0;
        document.getElementById('pendingFeedback').textContent = stats.by_status?.pending || 0;
        document.getElementById('inProgressFeedback').textContent = stats.by_status?.in_progress || 0;
        document.getElementById('resolvedFeedback').textContent = stats.by_status?.resolved || 0;
    },

    renderFeedbackList(feedback) {
        const container = document.getElementById('feedbackList');
        
        if (!feedback || feedback.length === 0) {
            this.showEmptyState();
            return;
        }

        this.hideEmptyState();
        
        container.innerHTML = feedback.map(item => this.createFeedbackItem(item)).join('');
    },

    createFeedbackItem(item) {
        const priorityColors = {
            low: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            medium: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            high: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400'
        };

        const statusColors = {
            pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            in_progress: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            resolved: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            closed: 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400'
        };

        const typeIcons = {
            suggestion: 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
            bug_report: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z',
            improvement: 'M13 10V3L4 14h7v7l9-11h-7z',
            general: 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z',
            praise: 'M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
            complaint: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z'
        };

        return `
            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${typeIcons[item.type] || typeIcons.general}"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-white">${item.subject}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">by ${item.username} • ${this.formatDate(item.created_at)}</p>
                            </div>
                        </div>
                        
                        <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-2">${item.description}</p>
                        
                        <div class="flex items-center space-x-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${priorityColors[item.priority]}">
                                ${item.priority.charAt(0).toUpperCase() + item.priority.slice(1)} Priority
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusColors[item.status]}">
                                ${item.status.replace('_', ' ').charAt(0).toUpperCase() + item.status.replace('_', ' ').slice(1)}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                                ${item.type.replace('_', ' ').charAt(0).toUpperCase() + item.type.replace('_', ' ').slice(1)}
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-2 ml-4">
                        <button onclick="FeedbackManager.viewDetails(${item.id})" 
                            class="px-3 py-1.5 text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                            View Details
                        </button>
                        <select onchange="FeedbackManager.updateStatus(${item.id}, this.value)" 
                            class="px-3 py-1.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                            <option value="pending" ${item.status === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="in_progress" ${item.status === 'in_progress' ? 'selected' : ''}>In Progress</option>
                            <option value="resolved" ${item.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                            <option value="closed" ${item.status === 'closed' ? 'selected' : ''}>Closed</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
    },

    async viewDetails(feedbackId) {
        try {
            const response = await fetch(`/api/feedback/${feedbackId}?token=${AppState.getToken()}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load feedback details');
            }

            const data = await response.json();
            this.showModal(data.data.feedback);
            
        } catch (error) {
            console.error('Error loading feedback details:', error);
            this.showError('Failed to load feedback details');
        }
    },

    async updateStatus(feedbackId, status) {
        try {
            const response = await fetch(`/api/feedback/status/${feedbackId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ status, token: AppState.getToken() })
            });

            if (!response.ok) {
                throw new Error('Failed to update status');
            }

            // Reload data to update statistics
            this.loadData();
            
        } catch (error) {
            console.error('Error updating status:', error);
            this.showError('Failed to update status');
        }
    },

    showModal(feedback) {
        const modal = document.getElementById('feedbackModal');
        const content = document.getElementById('feedbackModalContent');
        
        content.innerHTML = `
            <div class="space-y-6">
                <div>
                    <h4 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">${feedback.subject}</h4>
                    <div class="flex items-center space-x-4 text-sm text-gray-600 dark:text-gray-400">
                        <span>by ${feedback.username}</span>
                        <span>•</span>
                        <span>${this.formatDate(feedback.created_at)}</span>
                    </div>
                </div>
                
                <div>
                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</h5>
                    <p class="text-gray-900 dark:text-white whitespace-pre-wrap">${feedback.description}</p>
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</h5>
                        <p class="text-gray-900 dark:text-white">${feedback.feedback_type.replace('_', ' ').charAt(0).toUpperCase() + feedback.feedback_type.replace('_', ' ').slice(1)}</p>
                    </div>
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</h5>
                        <p class="text-gray-900 dark:text-white">${feedback.priority.charAt(0).toUpperCase() + feedback.priority.slice(1)}</p>
                    </div>
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</h5>
                        <p class="text-gray-900 dark:text-white">${feedback.status.replace('_', ' ').charAt(0).toUpperCase() + feedback.status.replace('_', ' ').slice(1)}</p>
                    </div>
                    <div>
                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact Preference</h5>
                        <p class="text-gray-900 dark:text-white">${feedback.contact_preference === 'yes' ? 'Yes' : 'No'}</p>
                    </div>
                </div>
                
                ${feedback.email ? `
                <div>
                    <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contact Email</h5>
                    <p class="text-gray-900 dark:text-white">${feedback.email}</p>
                </div>
                ` : ''}
            </div>
        `;
        
        modal.classList.remove('hidden');
    },

    closeModal() {
        document.getElementById('feedbackModal').classList.add('hidden');
    },

    showLoading() {
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('feedbackList').classList.add('hidden');
    },

    hideLoading() {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('feedbackList').classList.remove('hidden');
    },

    showEmptyState() {
        document.getElementById('emptyState').classList.remove('hidden');
        document.getElementById('feedbackList').classList.add('hidden');
    },

    hideEmptyState() {
        document.getElementById('emptyState').classList.add('hidden');
        document.getElementById('feedbackList').classList.remove('hidden');
    },

    showError(message) {
        // You can implement a toast notification here
        console.error(message);
    },

    refreshData() {
        this.loadData();
    },

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    FeedbackManager.init();
});
</script> 