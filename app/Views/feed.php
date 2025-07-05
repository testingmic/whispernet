<div class="min-h-[calc(100vh-100px)] bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">

    <!-- Location Header Card -->
    <div class="max-w-4xl mx-auto mt-0 sm:px-6 lg:px-8 mb-4">
        <div class="bg-white dark:bg-gray-800 shadow-xl border border-gray-200 dark:border-gray-700 p-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-6 h-6 bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white location-display">Loading location...</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Showing posts within your location.
                        </p>
                    </div>
                </div>
                <button id="changeLocationBtn" class="hidden inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Change Location
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Location/Radius Modal -->
    <div id="locationModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div class="inline-block w-full max-w-md p-6 sm:p-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Location Settings</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Customize your local feed preferences</p>
                    </div>
                    <button id="closeLocationModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="locationFormd" class="space-y-6">
                    <!-- Location Selection -->
                    <div>
                        <label for="locationSelecta" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Location</label>
                        <div class="relative">
                            <select id="locationSelecta" class="w-full px-4 py-3 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200 appearance-none">
                                <option value="current">📍 Current Location</option>
                                <option value="city_centre">🏙️ City Centre</option>
                                <option value="university">🎓 University Area</option>
                                <option value="mall">🛍️ Mall District</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Radius Selection -->
                    <div>
                        <label for="radiusInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Search Radius</label>
                        <div class="space-y-3">
                            <input type="range" id="radiusInput" min="1" max="50" value="10" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                <span>1km</span>
                                <span id="radiusValue" class="font-semibold text-blue-600 dark:text-blue-400">10km</span>
                                <span>50km</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <button type="button" id="cancelLocationBtn" class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Feed Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 pb-2">
        <!-- Unread Posts Notification -->
        <div id="unreadPostsCountContainer" class="hidden mb-6">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-2xl shadow-xl p-4 cursor-pointer transform transition-all duration-200 hover:scale-105" onclick="return PostManager.showUnreadPosts()">
                <div class="flex items-center justify-center space-x-3">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <span class="font-semibold">
                        <span id="unreadPostsCount" class="text-lg">0</span> new posts available
                    </span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </div>
        </div>
        <!-- Platform Updates Notification -->
        <!-- <div id="platformUpdates" class="update-card bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-6 border border-green-200 dark:border-green-700 hover:shadow-lg transition-all duration-300 mb-3">
            <div class="flex items-start justify-between">
                <div class="flex items-start space-x-3">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center space-x-2 mb-1">
                            <h3 class="font-semibold text-gray-900 dark:text-white">What's New in <?= $appName ?></h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200 animate-pulse">
                                New
                            </span>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-3">
                            We've added a new feature to share posts with your friends & create chat groups and have total control over your chats.
                        </p>
                        <div class="flex items-center space-x-3">
                            <a href="<?= $baseUrl ?>/updates" class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                                View Updates
                            </a>
                            <button onclick="return AppState.hidePlatformUpdates()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 text-sm transition-colors">
                                Dismiss
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Feed Container -->
        <div id="feedContainer" class="scroll-sentinel">
            <?= loadingSkeleton(1, false); ?>
        </div>

        <!-- Load More Posts -->
        <div id="oldPostsContainer" class="mt-8 mb-4">
            <button onclick="return PostManager.showOldPosts()" class="w-full bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-2xl py-4 px-6 hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105 shadow-xl">
                <div class="flex items-center justify-center space-x-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    <span>Load More Posts</span>
                </div>
            </button>
        </div>

        <!-- No Posts Message -->
        <div id="noPostsContainer" class="hidden mt-8">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Posts Found</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Try adjusting your location or radius to see more posts</p>
                <button onclick="return PostManager.openCreateModal()" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create A Post
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Floating Action Button -->
    <button onclick="return PostManager.openCreateModal()" class="fixed bottom-24 right-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full p-4 shadow-2xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all transform hover:scale-110 z-50">
        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
    </button>

    <!-- Report Post Modal -->
    <div id="reportPostModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 backdrop-blur-sm hidden" role="dialog" aria-modal="true">
        <div class="min-h-screen px-4 text-center flex items-center justify-center">
            <div class="inline-block w-full max-w-md p-6 sm:p-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-2xl rounded-2xl">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Report Post</h3>
                        <p class="text-gray-600 dark:text-gray-400 mt-1">Help us maintain a safe community</p>
                    </div>
                    <button onclick="PostManager.closeReportModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="reportPostForm" class="space-y-6">
                    <!-- Reason Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Reason for Report</label>
                        <div class="space-y-3" id="reportReasonsList"></div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-4 pt-4">
                        <button type="button" onclick="PostManager.closeReportModal()" class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" class="px-8 py-3 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-xl hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105">
                            Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="additionalHeight" class="h-20"></div>
<script>
    // Enhanced Location Modal Management
    document.addEventListener('DOMContentLoaded', function() {
        const changeLocationBtn = document.getElementById('changeLocationBtn');
        const locationModal = document.getElementById('locationModal');
        const closeLocationModal = document.getElementById('closeLocationModal');
        const cancelLocationBtn = document.getElementById('cancelLocationBtn');
        const locationForm = document.getElementById('locationFormd');
        const radiusInput = document.getElementById('radiusInput');
        const radiusValue = document.getElementById('radiusValue');

        // Open modal
        if (changeLocationBtn) {
            changeLocationBtn.addEventListener('click', function() {
                locationModal.classList.remove('hidden');
                locationModal.querySelector('.inline-block').classList.add('animate-fadeIn');
            });
        }

        // Close modal handlers
        [closeLocationModal, cancelLocationBtn].forEach(btn => {
            if (btn) {
                btn.addEventListener('click', () => {
                    locationModal.classList.add('hidden');
                    locationModal.querySelector('.inline-block').classList.remove('animate-fadeIn');
                });
            }
        });

        // Click outside to close
        locationModal.addEventListener('click', (e) => {
            if (e.target === locationModal) {
                locationModal.classList.add('hidden');
                locationModal.querySelector('.inline-block').classList.remove('animate-fadeIn');
            }
        });

        // Radius input update
        if (radiusInput && radiusValue) {
            radiusInput.addEventListener('input', function() {
                radiusValue.textContent = this.value + 'km';
            });
        }

        // Form submission
        if (locationForm) {
            locationForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const submitBtn = locationForm.querySelector('button[type="submit"]');
                const originalText = submitBtn.textContent;

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Updating...
            `;

                // Simulate update
                setTimeout(() => {
                    submitBtn.innerHTML = `
                    <svg class="w-5 h-5 text-white mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                    </svg>
                    Updated!
                `;
                    submitBtn.classList.remove('from-blue-500', 'to-purple-600', 'hover:from-blue-600', 'hover:to-purple-700');
                    submitBtn.classList.add('from-green-500', 'to-green-600');

                    // Close modal after success
                    setTimeout(() => {
                        locationModal.classList.add('hidden');
                        locationForm.reset();
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                        submitBtn.classList.remove('from-green-500', 'to-green-600');
                        submitBtn.classList.add('from-blue-500', 'to-purple-600', 'hover:from-blue-600', 'hover:to-purple-700');
                    }, 1000);
                }, 1500);
            });
        }
    });

    // Add CSS animations
    const style = document.createElement('style');
    style.textContent = `
    @keyframes fadeIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
    
    /* Report Modal Animations */
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    #reportPostModal .inline-block {
        animation: slideIn 0.3s ease-out;
    }
    
    /* Radio button animations */
    .w-3.h-3 {
        transition: all 0.2s ease-in-out;
    }
    
    /* Form field focus animations */
    #reportPostForm input:focus + div .w-3.h-3,
    #reportPostForm label:hover .w-3.h-3 {
        transform: scale(1.1);
    }
    
    /* Selected radio button styling */
    #reportPostForm input:checked + div {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }
    
    #reportPostForm input:checked + div .w-3.h-3 {
        background-color: #3b82f6;
    }
    
    /* Dark mode support */
    .dark #reportPostForm input:checked + div {
        border-color: #60a5fa;
        background-color: #1e3a8a;
    }
    
    .dark #reportPostForm input:checked + div .w-3.h-3 {
        background-color: #60a5fa;
    }
`;
    document.head.appendChild(style);
</script>