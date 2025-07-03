// Analytics Manager
const AnalyticsManager = {
    currentTimeRange: 'month',
    charts: {},
    refreshInterval: null,

    init() {
        this.setupEventListeners();
        this.loadAnalytics();
        this.startRealTimeUpdates();
        
        // Handle window resize to prevent chart stretching
        window.addEventListener('resize', this.handleResize.bind(this));
    },

    setupEventListeners() {
        // Time range change
        document.getElementById('timeRange').addEventListener('change', (e) => {
            this.currentTimeRange = e.target.value;
            this.loadAnalytics();
        });
    },

    async loadAnalytics() {
        try {
            this.showLoading();
            
            const response = await fetch(`/api/analytics/dashboard?timeRange=${this.currentTimeRange}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load analytics data');
            }

            const data = await response.json();
            this.updateMetrics(data.data.metrics);
            this.updateCharts(data.data.charts);
            this.updateTopContent(data.data.topContent);
            this.updateLocations(data.data.locations);
            this.updateTags(data.data.tags);
            
        } catch (error) {
            console.error('Error loading analytics:', error);
            this.showError('Failed to load analytics data');
        } finally {
            this.hideLoading();
        }
    },

    updateMetrics(metrics) {
        // Update key metrics
        document.getElementById('totalUsers').textContent = this.formatNumber(metrics.totalUsers);
        document.getElementById('totalPosts').textContent = this.formatNumber(metrics.totalPosts);
        document.getElementById('totalComments').textContent = this.formatNumber(metrics.totalComments);
        document.getElementById('totalVotes').textContent = this.formatNumber(metrics.totalVotes);
        document.getElementById('totalPageViews').textContent = this.formatNumber(metrics.totalPageViews);
        document.getElementById('activeUsers').textContent = this.formatNumber(metrics.activeUsers);
        document.getElementById('moderatorsCount').textContent = this.formatNumber(metrics.moderatorsCount);
        document.getElementById('totalTags').textContent = this.formatNumber(metrics.totalTags);

        // Update growth indicators
        document.getElementById('usersGrowth').textContent = `${metrics.totalUsersGrowth >= 0 ? '+' : ''}${metrics.totalUsersGrowth}% from last period`;
        document.getElementById('postsGrowth').textContent = `${metrics.totalPostsGrowth >= 0 ? '+' : ''}${metrics.totalPostsGrowth}% from last period`;
        document.getElementById('commentsGrowth').textContent = `${metrics.totalCommentsGrowth >= 0 ? '+' : ''}${metrics.totalCommentsGrowth}% from last period`;
        document.getElementById('votesGrowth').textContent = `${metrics.totalVotesGrowth >= 0 ? '+' : ''}${metrics.totalVotesGrowth}% from last period`;

        // Update growth colors
        const growthElements = ['usersGrowth', 'postsGrowth', 'commentsGrowth', 'votesGrowth'];
        growthElements.forEach(id => {
            const element = document.getElementById(id);
            const value = parseFloat(element.textContent);
            element.className = `text-xs ${value >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'}`;
        });
    },

    updateCharts(chartData) {
        this.createUserGrowthChart(chartData.userGrowth);
        this.createPostsActivityChart(chartData.postsActivity);
        this.createEngagementChart(chartData.engagement);
        this.createGenderChart(chartData.gender);
        this.createRealtimeActivityChart(chartData.realtimeActivity);
    },

    createUserGrowthChart(data) {
        const ctx = document.getElementById('userGrowthChart').getContext('2d');
        
        if (this.charts.userGrowth) {
            this.charts.userGrowth.destroy();
        }

        this.charts.userGrowth = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'New Users',
                    data: data.values,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#3b82f6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)'
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)'
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                elements: {
                    point: {
                        hoverRadius: 8
                    }
                }
            }
        });
    },

    createPostsActivityChart(data) {
        const ctx = document.getElementById('postsActivityChart').getContext('2d');
        
        if (this.charts.postsActivity) {
            this.charts.postsActivity.destroy();
        }

        this.charts.postsActivity = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Posts Created',
                    data: data.values,
                    backgroundColor: 'rgba(34, 197, 94, 0.8)',
                    borderColor: '#22c55e',
                    borderWidth: 1,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)'
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)'
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    },

    createEngagementChart(data) {
        const ctx = document.getElementById('engagementChart').getContext('2d');
        
        if (this.charts.engagement) {
            this.charts.engagement.destroy();
        }

        this.charts.engagement = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Posts', 'Comments', 'Votes', 'Views'],
                datasets: [{
                    data: [data.posts, data.comments, data.votes, data.views],
                    backgroundColor: [
                        '#3b82f6',
                        '#8b5cf6',
                        '#f59e0b',
                        '#10b981'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#6b7280',
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    },

    createGenderChart(data) {
        const ctx = document.getElementById('genderChart').getContext('2d');
        
        if (this.charts.gender) {
            this.charts.gender.destroy();
        }

        this.charts.gender = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female', 'Other', 'Not Specified'],
                datasets: [{
                    data: [data.male, data.female, data.other, data.notSpecified],
                    backgroundColor: [
                        '#3b82f6',
                        '#ec4899',
                        '#8b5cf6',
                        '#6b7280'
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#6b7280',
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10
                    }
                }
            }
        });
    },

    createRealtimeActivityChart(data) {
        const ctx = document.getElementById('realtimeActivityChart').getContext('2d');
        
        if (this.charts.realtimeActivity) {
            this.charts.realtimeActivity.destroy();
        }

        this.charts.realtimeActivity = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Active Users',
                    data: data.values,
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#ef4444',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)'
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(156, 163, 175, 0.1)'
                        },
                        ticks: {
                            color: '#6b7280'
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                elements: {
                    point: {
                        hoverRadius: 6
                    }
                }
            }
        });
    },

    updateTopContent(content) {
        const container = document.getElementById('topContent');
        
        if (!content || content.length === 0) {
            container.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-8">No content data available</p>';
            return;
        }

        container.innerHTML = content.map((item, index) => `
            <div class="flex items-center space-x-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                    ${index + 1}
                </div>
                <div class="flex-1">
                    <h4 class="font-medium text-gray-900 dark:text-white">${item.title}</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">by ${item.author}</p>
                </div>
                <div class="text-right">
                    <div class="flex items-center space-x-4 text-sm">
                        <span class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                            </svg>
                            ${this.formatNumber(item.votes)}
                        </span>
                        <span class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            ${this.formatNumber(item.comments)}
                        </span>
                        <span class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            ${this.formatNumber(item.views)}
                        </span>
                    </div>
                </div>
            </div>
        `).join('');
    },

    updateLocations(locations) {
        const container = document.getElementById('topLocations');
        
        if (!locations || locations.length === 0) {
            container.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-4">No location data available</p>';
            return;
        }

        container.innerHTML = locations.map((location, index) => `
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                        ${index + 1}
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">${location.city}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${location.country}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900 dark:text-white">${this.formatNumber(location.posts)}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">posts</p>
                </div>
            </div>
        `).join('');
    },

    updateTags(tags) {
        const container = document.getElementById('popularTags');
        
        if (!tags || tags.length === 0) {
            container.innerHTML = '<p class="text-gray-500 dark:text-gray-400 text-center py-4">No tag data available</p>';
            return;
        }

        container.innerHTML = tags.map((tag, index) => `
            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-pink-500 to-rose-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                        ${index + 1}
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 dark:text-white">#${tag.name}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">${this.formatNumber(tag.usage)} uses</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="font-semibold text-gray-900 dark:text-white">${this.formatNumber(tag.posts)}</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400">posts</p>
                </div>
            </div>
        `).join('');
    },

    startRealTimeUpdates() {
        // Update real-time data every 30 seconds
        this.refreshInterval = setInterval(() => {
            this.updateRealTimeData();
        }, 30000);
    },

    async updateRealTimeData() {
        try {
            const response = await fetch(`/api/analytics/realtime`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const data = await response.json();
                this.updateRealtimeChart(data);
                this.updateActiveUsers(data.activeUsers);
            }
        } catch (error) {
            console.error('Error updating real-time data:', error);
        }
    },

    updateRealtimeChart(data) {
        if (this.charts.realtimeActivity) {
            // Only update if data has actually changed
            const currentData = this.charts.realtimeActivity.data.datasets[0].data;
            const newData = data.values;
            
            let hasChanged = false;
            if (currentData.length !== newData.length) {
                hasChanged = true;
            } else {
                for (let i = 0; i < currentData.length; i++) {
                    if (currentData[i] !== newData[i]) {
                        hasChanged = true;
                        break;
                    }
                }
            }
            
            if (hasChanged) {
                this.charts.realtimeActivity.data.labels = data.labels;
                this.charts.realtimeActivity.data.datasets[0].data = data.values;
                this.charts.realtimeActivity.update('none');
            }
        }
    },

    updateActiveUsers(count) {
        document.getElementById('activeUsers').textContent = this.formatNumber(count);
    },

    refreshData() {
        this.loadAnalytics();
    },

    showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    },

    hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    },

    showError(message) {
        // You can implement a toast notification system here
        console.error('Analytics Error:', message);
    },

    formatNumber(num) {
        try {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1) + 'M';
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1) + 'K';
            }
            return num.toString();
        } catch (error) {
            return num;
        }
    },

    destroy() {
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
            this.refreshInterval = null;
        }
        
        // Clear resize timeout
        if (this.resizeTimeout) {
            clearTimeout(this.resizeTimeout);
            this.resizeTimeout = null;
        }
        
        // Remove resize event listener
        window.removeEventListener('resize', this.handleResize.bind(this));
        
        // Destroy all charts properly
        Object.keys(this.charts).forEach(chartKey => {
            if (this.charts[chartKey]) {
                this.charts[chartKey].destroy();
                this.charts[chartKey] = null;
            }
        });
        
        // Clear the charts object
        this.charts = {};
    },

    handleResize() {
        // Debounce resize events to prevent excessive chart updates
        clearTimeout(this.resizeTimeout);
        this.resizeTimeout = setTimeout(() => {
            Object.values(this.charts).forEach(chart => {
                if (chart) {
                    chart.resize();
                }
            });
        }, 250);
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    AnalyticsManager.init();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    AnalyticsManager.destroy();
});
