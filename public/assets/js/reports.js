// Reports Manager
const ReportsManager = {
    currentPage: 1,
    itemsPerPage: 10,
    currentFilters: {
        status: 'all',
        type: 'all',
        reason: 'all',
        search: ''
    },
    currentReport: null,
    currentVote: null,

    init() {
        this.setupEventListeners();
        this.loadReports();
        this.loadStats();
    },

    setupEventListeners() {
        // Filter change events
        document.getElementById('statusFilter').addEventListener('change', (e) => {
            this.currentFilters.status = e.target.value;
            this.currentPage = 1;
            this.loadReports();
        });

        document.getElementById('typeFilter').addEventListener('change', (e) => {
            this.currentFilters.type = e.target.value;
            this.currentPage = 1;
            this.loadReports();
        });

        document.getElementById('reasonFilter').addEventListener('change', (e) => {
            this.currentFilters.reason = e.target.value;
            this.currentPage = 1;
            this.loadReports();
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                this.searchReports();
            }
        });

        // Pagination
        document.getElementById('prevPage').addEventListener('click', () => {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.loadReports();
            }
        });

        document.getElementById('nextPage').addEventListener('click', () => {
            this.currentPage++;
            this.loadReports();
        });
    },

    async loadReports() {
        try {
            this.showLoading();
            
            const queryParams = new URLSearchParams({
                page: this.currentPage,
                limit: this.itemsPerPage,
                status: this.currentFilters.status,
                type: this.currentFilters.type,
                reason: this.currentFilters.reason,
                search: this.currentFilters.search
            });

            const response = await fetch(`/api/reports?${queryParams}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load reports');
            }

            const data = await response.json();
            this.renderReports(data.reports || []);
            this.updatePagination(data.total || 0);
            
        } catch (error) {
            console.error('Error loading reports:', error);
            this.showError('Failed to load reports');
        } finally {
            this.hideLoading();
        }
    },

    async loadStats() {
        try {
            const response = await fetch('/api/reports/stats', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const stats = await response.json();
                this.updateStats(stats);
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    },

    renderReports(reports) {
        const container = document.getElementById('reportsContainer');
        
        if (reports.length === 0) {
            this.showEmptyState();
            return;
        }

        this.hideEmptyState();
        
        container.innerHTML = reports.map(report => this.createReportCard(report)).join('');
    },

    createReportCard(report) {
        const statusColors = {
            pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
            reviewed: 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200',
            resolved: 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200'
        };

        const reasonColors = {
            spam: 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-200',
            harassment: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200',
            inappropriate: 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-200',
            misinformation: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-200',
            violence: 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-200',
            other: 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-200'
        };

        const voteCount = report.upvotes - report.downvotes;
        const voteStatus = voteCount >= 3 ? 'approved' : voteCount <= -3 ? 'removed' : 'pending';

        return `
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300">
                <div class="p-6">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    Report #${report.report_id}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Reported ${this.formatTimeAgo(report.created_at)}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 text-xs font-medium rounded-full ${statusColors[report.status]}">
                                ${report.status.charAt(0).toUpperCase() + report.status.slice(1)}
                            </span>
                            <span class="px-3 py-1 text-xs font-medium rounded-full ${reasonColors[report.reason]}">
                                ${report.reason.charAt(0).toUpperCase() + report.reason.slice(1)}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reported Content</h4>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-900 dark:text-white text-sm line-clamp-3">
                                    ${report.content_preview || 'Content preview not available'}
                                </p>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Voting Status</h4>
                            <div class="flex items-center space-x-4">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">${report.upvotes || 0}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Upvotes</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">${report.downvotes || 0}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Downvotes</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-bold ${voteCount >= 3 ? 'text-green-600 dark:text-green-400' : voteCount <= -3 ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400'}">
                                        ${voteCount}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Net Votes</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button onclick="ReportsManager.viewReportDetail(${report.report_id})" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Details
                            </button>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            ${this.canVote(report) ? `
                                <button onclick="ReportsManager.showVoteModal(${report.report_id}, 'up')" class="inline-flex items-center px-4 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                    </svg>
                                    Approve
                                </button>
                                <button onclick="ReportsManager.showVoteModal(${report.report_id}, 'down')" class="inline-flex items-center px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14H5.236a2 2 0 01-1.789-2.894l3.5-7A2 2 0 018.736 3h4.018c.163 0 .326.02.485.06L17 4m-7 10v2a2 2 0 002 2h.095c.5 0 .905-.405.905-.905 0-.714.211-1.412.608-2.006L17 13V4m-7 10h2m5-10h2a2 2 0 012 2v6a2 2 0 01-2 2h-2.5"></path>
                                    </svg>
                                    Remove
                                </button>
                            ` : `
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    ${report.user_has_voted ? 'You have already voted' : 'Voting closed'}
                                </span>
                            `}
                        </div>
                    </div>
                </div>
            </div>
        `;
    },

    canVote(report) {
        return report.status === 'pending' && !report.user_has_voted;
    },

    async viewReportDetail(reportId) {
        try {
            const response = await fetch(`/api/reports/${reportId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load report details');
            }

            const report = await response.json();
            this.showReportDetail(report);
        } catch (error) {
            console.error('Error loading report details:', error);
            this.showError('Failed to load report details');
        }
    },

    showReportDetail(report) {
        const modal = document.getElementById('reportDetailModal');
        const content = document.getElementById('reportDetailContent');
        
        content.innerHTML = `
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Information</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Report ID:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">#${report.report_id}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Status:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">${report.status}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Reason:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">${report.reason}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Reported:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">${this.formatDate(report.created_at)}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Voting Results</h4>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Upvotes:</span>
                                <span class="text-sm font-medium text-green-600 dark:text-green-400">${report.upvotes || 0}</span>
                            </div>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Downvotes:</span>
                                <span class="text-sm font-medium text-red-600 dark:text-red-400">${report.downvotes || 0}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Net Votes:</span>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">${(report.upvotes || 0) - (report.downvotes || 0)}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reported Content</h4>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-900 dark:text-white">${report.content || 'Content not available'}</p>
                    </div>
                </div>
                
                ${report.description ? `
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Details</h4>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-900 dark:text-white">${report.description}</p>
                    </div>
                </div>
                ` : ''}
            </div>
        `;
        
        modal.classList.remove('hidden');
    },

    closeDetailModal() {
        document.getElementById('reportDetailModal').classList.add('hidden');
    },

    showVoteModal(reportId, vote) {
        this.currentReport = reportId;
        this.currentVote = vote;
        
        const modal = document.getElementById('voteModal');
        const text = document.getElementById('voteConfirmationText');
        
        text.textContent = `Are you sure you want to ${vote === 'up' ? 'approve' : 'remove'} this content?`;
        
        modal.classList.remove('hidden');
    },

    closeVoteModal() {
        document.getElementById('voteModal').classList.add('hidden');
        this.currentReport = null;
        this.currentVote = null;
    },

    async confirmVote() {
        if (!this.currentReport || !this.currentVote) return;
        
        try {
            const response = await fetch(`/api/reports/${this.currentReport}/vote`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    vote: this.currentVote
                })
            });

            if (!response.ok) {
                throw new Error('Failed to submit vote');
            }

            const result = await response.json();
            
            if (result.status === 'success') {
                this.showSuccess(`Vote submitted successfully`);
                this.closeVoteModal();
                this.loadReports(); // Refresh the list
            } else {
                throw new Error(result.message || 'Failed to submit vote');
            }
        } catch (error) {
            console.error('Error submitting vote:', error);
            this.showError(error.message || 'Failed to submit vote');
        }
    },

    searchReports() {
        this.currentFilters.search = document.getElementById('searchInput').value;
        this.currentPage = 1;
        this.loadReports();
    },

    refreshReports() {
        this.loadReports();
        this.loadStats();
    },

    updateStats(stats) {
        document.getElementById('pendingCount').textContent = stats.pending || 0;
        document.getElementById('resolvedToday').textContent = stats.resolved_today || 0;
        document.getElementById('removedCount').textContent = stats.removed || 0;
        document.getElementById('approvedCount').textContent = stats.approved || 0;
    },

    updatePagination(total) {
        const pagination = document.getElementById('pagination');
        const showingCount = document.getElementById('showingCount');
        const totalCount = document.getElementById('totalCount');
        const prevButton = document.getElementById('prevPage');
        const nextButton = document.getElementById('nextPage');
        
        if (total === 0) {
            pagination.classList.add('hidden');
            return;
        }
        
        pagination.classList.remove('hidden');
        
        const start = (this.currentPage - 1) * this.itemsPerPage + 1;
        const end = Math.min(start + this.itemsPerPage - 1, total);
        
        showingCount.textContent = `${start}-${end}`;
        totalCount.textContent = total;
        
        prevButton.disabled = this.currentPage === 1;
        nextButton.disabled = end >= total;
    },

    showLoading() {
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('emptyState').classList.add('hidden');
    },

    hideLoading() {
        document.getElementById('loadingState').classList.add('hidden');
    },

    showEmptyState() {
        document.getElementById('emptyState').classList.remove('hidden');
    },

    hideEmptyState() {
        document.getElementById('emptyState').classList.add('hidden');
    },

    showSuccess(message) {
        // You can implement a toast notification system here
        console.log('Success:', message);
    },

    showError(message) {
        // You can implement a toast notification system here
        console.error('Error:', message);
    },

    formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(diff / 3600000);
        const days = Math.floor(diff / 86400000);
        
        if (minutes < 60) return `${minutes}m ago`;
        if (hours < 24) return `${hours}h ago`;
        return `${days}d ago`;
    },

    formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    ReportsManager.init();
    
    // Setup vote confirmation
    document.getElementById('confirmVoteBtn').addEventListener('click', () => {
        ReportsManager.confirmVote();
    });
}); 