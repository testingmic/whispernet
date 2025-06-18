
<div class="container mx-auto px-4 py-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Platform Analytics</h1>
            <div class="flex gap-4">
                <select id="timeRange" class="form-input">
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month" selected>This Month</option>
                    <option value="year">This Year</option>
                </select>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900 mr-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                        <h3 class="text-2xl font-bold" id="totalUsers">0</h3>
                    </div>
                </div>
            </div>

            <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900 mr-4">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Posts</p>
                        <h3 class="text-2xl font-bold" id="totalPosts">0</h3>
                    </div>
                </div>
            </div>

            <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 dark:bg-purple-900 mr-4">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Comments</p>
                        <h3 class="text-2xl font-bold" id="totalComments">0</h3>
                    </div>
                </div>
            </div>

            <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 dark:bg-red-900 mr-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Active Users</p>
                        <h3 class="text-2xl font-bold" id="activeUsers">0</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- User Growth Chart -->
            <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow p-6">
                <h3 class="text-lg font-semibold mb-4">User Growth</h3>
                <canvas id="userGrowthChart"></canvas>
            </div>

            <!-- Content Distribution Chart -->
            <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Content Distribution</h3>
                <canvas id="contentDistributionChart"></canvas>
            </div>
        </div>

        <!-- Engagement Metrics -->
        <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow p-6 mb-8">
            <h3 class="text-lg font-semibold mb-4">Engagement Metrics</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <canvas id="engagementChart"></canvas>
                </div>
                <div>
                    <canvas id="retentionChart"></canvas>
                </div>
                <div>
                    <canvas id="activityChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Content -->
        <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold">Top Performing Content</h3>
            </div>
            <div class="p-4">
                <div id="topContent" class="space-y-4">
                    <!-- Top content will be loaded here -->
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- <script src="<?= $baseUrl ?>/assets/js/analytics.js"></script>  -->