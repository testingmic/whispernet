
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Content Moderation</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Manage and moderate community content</p>
                </div>
                <div class="flex space-x-4">
                    <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                        rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        New Rule
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Moderators</h2>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">12</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Reports</h2>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">24</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Resolved Today</h2>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">156</p>
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600 dark:text-gray-400">Banned Users</h2>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-white">8</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Tabs -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px">
                    <button class="px-6 py-4 text-sm font-medium text-blue-600 dark:text-blue-400 border-b-2 border-blue-500">
                        Pending Content
                    </button>
                    <button class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        Flagged Users
                    </button>
                    <button class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        Moderation Rules
                    </button>
                    <button class="px-6 py-4 text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300">
                        Activity Log
                    </button>
                </nav>
            </div>

            <!-- Content List -->
            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <!-- Sample Content Items -->
                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                                    JD
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">John Doe</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Posted 2 hours ago</p>
                                <div class="mt-2 text-gray-600 dark:text-gray-300">
                                    This is a sample post that needs moderation. It contains potentially inappropriate content that needs to be reviewed.
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                Pending Review
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                            dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            View Details
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 
                            rounded-lg transition-colors duration-200">
                            Remove Content
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                            rounded-lg transition-colors duration-200">
                            Approve
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-green-500 flex items-center justify-center text-white font-medium">
                                    JS
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Jane Smith</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Posted 5 hours ago</p>
                                <div class="mt-2 text-gray-600 dark:text-gray-300">
                                    Another post that requires moderation. This one has been flagged for potential spam content.
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                Under Review
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                            dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            View Details
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 
                            rounded-lg transition-colors duration-200">
                            Remove Content
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                            rounded-lg transition-colors duration-200">
                            Approve
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full bg-purple-500 flex items-center justify-center text-white font-medium">
                                    RJ
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Robert Johnson</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Posted 1 day ago</p>
                                <div class="mt-2 text-gray-600 dark:text-gray-300">
                                    A comment that has been reported for harassment. This needs immediate attention from moderators.
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                Urgent
                            </span>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-end space-x-3">
                        <button class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                            dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            View Details
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 
                            rounded-lg transition-colors duration-200">
                            Remove Content
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                            rounded-lg transition-colors duration-200">
                            Approve
                        </button>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <nav class="flex justify-between items-center">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Showing <span class="font-medium">1</span> to <span class="font-medium">3</span> of <span class="font-medium">24</span> results
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                            dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            Previous
                        </button>
                        <button class="px-3 py-1 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                            rounded-lg transition-colors duration-200">
                            1
                        </button>
                        <button class="px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                            dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            2
                        </button>
                        <button class="px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                            dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            3
                        </button>
                        <button class="px-3 py-1 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                            dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            Next
                        </button>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- Content Details Modal -->
<div id="contentDetailsModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-2xl w-full mx-4">
        <div class="flex justify-between items-start mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Content Details</h3>
            <button onclick="closeContentDetailsModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="space-y-6">
            <!-- Content Preview -->
            <div>
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content Preview</h4>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-gray-900 dark:text-white">This is the content that needs to be reviewed...</p>
                </div>
            </div>
            
            <!-- Report Details -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reported By</h4>
                    <p class="text-gray-900 dark:text-white">John Doe</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Report Type</h4>
                    <p class="text-gray-900 dark:text-white">Inappropriate Content</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reported At</h4>
                    <p class="text-gray-900 dark:text-white">2024-02-20 14:30</p>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</h4>
                    <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                        Pending
                    </span>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3">
                <button class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                    dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                    Dismiss
                </button>
                <button class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 
                    rounded-lg transition-colors duration-200">
                    Remove Content
                </button>
                <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                    rounded-lg transition-colors duration-200">
                    Approve
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle tab switching
    const tabs = document.querySelectorAll('nav button');
    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('text-blue-600', 'border-blue-500'));
            tabs.forEach(t => t.classList.add('text-gray-500'));
            this.classList.remove('text-gray-500');
            this.classList.add('text-blue-600', 'border-blue-500');
        });
    });
    
    // Handle content item clicks
    const contentItems = document.querySelectorAll('.p-6');
    contentItems.forEach(item => {
        item.addEventListener('click', function(e) {
            if (!e.target.closest('button')) {
                openContentDetailsModal();
            }
        });
    });
});

function openContentDetailsModal() {
    document.getElementById('contentDetailsModal').classList.remove('hidden');
}

function closeContentDetailsModal() {
    document.getElementById('contentDetailsModal').classList.add('hidden');
}
</script>