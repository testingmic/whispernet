<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Reports</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Manage and review reported content</p>
                </div>
                <div class="flex space-x-4">
                    <div class="relative">
                        <select id="statusFilter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 
                            focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 
                            dark:text-white">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="reviewing">Under Review</option>
                            <option value="resolved">Resolved</option>
                            <option value="dismissed">Dismissed</option>
                        </select>
                    </div>
                    <div class="relative">
                        <select id="typeFilter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 
                            focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 
                            dark:text-white">
                            <option value="all">All Types</option>
                            <option value="post">Posts</option>
                            <option value="comment">Comments</option>
                            <option value="user">Users</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="reportsContainer">
            <!-- Sample Report Cards -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                            Pending
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">2 hours ago</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Reported Post</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Inappropriate content in post #1234</p>
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-medium">
                                JD
                            </div>
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">John Doe</span>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Post</span>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                            dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            View Details
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                            rounded-lg transition-colors duration-200">
                            Take Action
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                            Under Review
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">5 hours ago</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Reported User</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Harassment by user @problematic_user</p>
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-green-500 flex items-center justify-center text-white font-medium">
                                JS
                            </div>
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Jane Smith</span>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">User</span>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                            dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            View Details
                        </button>
                        <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                            rounded-lg transition-colors duration-200">
                            Take Action
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Resolved
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">1 day ago</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Reported Comment</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-4">Spam in comment #5678</p>
                    <div class="flex items-center space-x-4 mb-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-purple-500 flex items-center justify-center text-white font-medium">
                                RJ
                            </div>
                            <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Robert Johnson</span>
                        </div>
                        <span class="text-sm text-gray-500 dark:text-gray-400">•</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">Comment</span>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button class="px-4 py-1 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-100 
                            dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                            View Details
                        </button>
                        <button class="px-4 py-1 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                            rounded-lg transition-colors duration-200">
                            Take Action
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 dark:border-gray-600 
                    bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <span class="sr-only">Previous</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 
                    bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    1
                </a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 
                    bg-blue-50 dark:bg-blue-900 text-sm font-medium text-blue-600 dark:text-blue-200 hover:bg-blue-100 dark:hover:bg-blue-800">
                    2
                </a>
                <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 
                    bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                    3
                </a>
                <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 dark:border-gray-600 
                    bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <span class="sr-only">Next</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            </nav>
        </div>
    </div>
</div>

<!-- Report Details Modal -->
<div id="reportDetailsModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-2xl w-full mx-4">
        <div class="flex justify-between items-start mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Report Details</h3>
            <button onclick="closeReportDetailsModal()" class="text-gray-400 hover:text-gray-500">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="space-y-6">
            <!-- Report Content -->
            <div>
                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reported Content</h4>
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                    <p class="text-gray-900 dark:text-white">This is the reported content that needs to be reviewed...</p>
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
                    Dismiss Report
                </button>
                <button class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 
                    rounded-lg transition-colors duration-200">
                    Remove Content
                </button>
                <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 
                    rounded-lg transition-colors duration-200">
                    Take Action
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle filters
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    
    [statusFilter, typeFilter].forEach(filter => {
        filter.addEventListener('change', function() {
            // Implement filter logic here
            console.log('Filter changed:', this.value);
        });
    });
    
    // Handle report card clicks
    const reportCards = document.querySelectorAll('.bg-white');
    reportCards.forEach(card => {
        card.addEventListener('click', function(e) {
            if (!e.target.closest('button')) {
                openReportDetailsModal();
            }
        });
    });
});

function openReportDetailsModal() {
    document.getElementById('reportDetailsModal').classList.remove('hidden');
}

function closeReportDetailsModal() {
    document.getElementById('reportDetailsModal').classList.add('hidden');
}
</script>