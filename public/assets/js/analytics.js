document.addEventListener('DOMContentLoaded', function() {
    let charts = {};
    let dataUpdateTimeout;
    
    // Initialize charts with empty data
    initializeCharts();
    
    // Load initial data
    loadAnalyticsData();
    
    // Event Listeners with debounce
    document.getElementById('timeRange').addEventListener('change', function(e) {
        clearTimeout(dataUpdateTimeout);
        dataUpdateTimeout = setTimeout(() => {
            loadAnalyticsData(e.target.value);
        }, 300);
    });
    
    // Load Analytics Data
    async function loadAnalyticsData(timeRange = 'month') {
        try {
            const response = await fetch(`${baseUrl}/api/analytics?timeRange=${timeRange}`);
            
            if (!response.ok) {
                throw new Error('Failed to load analytics data');
            }
            
            const data = await response.json();
            
            // Update dashboard stats first
            updateDashboard(data);
            
            // Update charts in batches to prevent UI blocking
            requestAnimationFrame(() => {
                updateCharts(data);
            });
            
        } catch (error) {
            console.error('Error loading analytics data:', error);
            showNotification('Failed to load analytics data', 'error');
        }
    }
    
    // Update Dashboard Stats
    function updateDashboard(data) {
        const stats = {
            totalUsers: document.getElementById('totalUsers'),
            totalPosts: document.getElementById('totalPosts'),
            totalComments: document.getElementById('totalComments'),
            activeUsers: document.getElementById('activeUsers')
        };
        
        // Animate numbers
        Object.entries(stats).forEach(([key, element]) => {
            const targetValue = data.stats[key];
            animateNumber(element, targetValue);
        });
    }
    
    // Initialize Charts
    function initializeCharts() {
        const chartConfigs = {
            userGrowth: {
                type: 'line',
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 750,
                        easing: 'easeInOutQuart'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            },
            contentDistribution: {
                type: 'doughnut',
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 750,
                        easing: 'easeInOutQuart'
                    }
                }
            },
            engagement: {
                type: 'bar',
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 750,
                        easing: 'easeInOutQuart'
                    }
                }
            },
            retention: {
                type: 'line',
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 750,
                        easing: 'easeInOutQuart'
                    }
                }
            },
            activity: {
                type: 'bar',
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: {
                        duration: 750,
                        easing: 'easeInOutQuart'
                    }
                }
            }
        };
        
        Object.entries(chartConfigs).forEach(([key, config]) => {
            const ctx = document.getElementById(`${key}Chart`).getContext('2d');
            charts[key] = new Chart(ctx, {
                type: config.type,
                data: getInitialData(key),
                options: config.options
            });
        });
    }
    
    // Get Initial Chart Data
    function getInitialData(chartType) {
        const baseData = {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: getChartColors(chartType),
                borderColor: getChartColors(chartType),
                tension: 0.1
            }]
        };
        
        switch (chartType) {
            case 'contentDistribution':
                return {
                    labels: ['Posts', 'Comments', 'Media'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: [
                            'rgb(34, 197, 94)',
                            'rgb(59, 130, 246)',
                            'rgb(168, 85, 247)'
                        ]
                    }]
                };
            default:
                return baseData;
        }
    }
    
    // Get Chart Colors
    function getChartColors(chartType) {
        const colors = {
            userGrowth: 'rgb(59, 130, 246)',
            engagement: 'rgb(59, 130, 246)',
            retention: 'rgb(34, 197, 94)',
            activity: 'rgb(168, 85, 247)'
        };
        return colors[chartType] || 'rgb(59, 130, 246)';
    }
    
    // Update Charts with Data
    function updateCharts(data) {
        // Update each chart in sequence to prevent UI blocking
        const updates = [
            () => updateUserGrowthChart(data.user_growth),
            () => updateContentDistributionChart(data.content_distribution),
            () => updateEngagementChart(data.engagement),
            () => updateRetentionChart(data.retention),
            () => updateActivityChart(data.activity),
            () => updateTopContent(data.top_content)
        ];
        
        updates.reduce((promise, update) => {
            return promise.then(() => {
                return new Promise(resolve => {
                    requestAnimationFrame(() => {
                        update();
                        resolve();
                    });
                });
            });
        }, Promise.resolve());
    }
    
    // Individual Chart Updates
    function updateUserGrowthChart(data) {
        charts.userGrowth.data.labels = data.labels;
        charts.userGrowth.data.datasets[0].data = data.data;
        charts.userGrowth.update('none');
    }
    
    function updateContentDistributionChart(data) {
        charts.contentDistribution.data.datasets[0].data = [
            data.posts,
            data.comments,
            data.media
        ];
        charts.contentDistribution.update('none');
    }
    
    function updateEngagementChart(data) {
        charts.engagement.data.labels = data.labels;
        charts.engagement.data.datasets[0].data = data.data;
        charts.engagement.update('none');
    }
    
    function updateRetentionChart(data) {
        charts.retention.data.labels = data.labels;
        charts.retention.data.datasets[0].data = data.data;
        charts.retention.update('none');
    }
    
    function updateActivityChart(data) {
        charts.activity.data.labels = data.labels;
        charts.activity.data.datasets[0].data = data.data;
        charts.activity.update('none');
    }
    
    // Update Top Content
    function updateTopContent(content) {
        const container = document.getElementById('topContent');
        container.innerHTML = '';
        
        content.forEach(item => {
            const div = document.createElement('div');
            div.className = 'p-4 border-b border-gray-200 dark:border-gray-700 last:border-0';
            div.innerHTML = `
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold">${item.title}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            By ${item.author} • ${new Date(item.created_at).toLocaleDateString()}
                        </p>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        ${item.engagement_count} engagements
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-gray-600 dark:text-gray-300">${item.content}</p>
                </div>
            `;
            container.appendChild(div);
        });
    }
    
    // Animate Number
    function animateNumber(element, target) {
        const start = parseInt(element.textContent.replace(/,/g, '')) || 0;
        const duration = 1000;
        const startTime = performance.now();
        
        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const current = Math.floor(start + (target - start) * progress);
            element.textContent = current.toLocaleString();
            
            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        
        requestAnimationFrame(update);
    }
});

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    } text-white`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
} 