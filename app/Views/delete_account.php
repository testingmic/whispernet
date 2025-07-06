<div class="min-h-[calc(100vh-65px)] bg-gradient-to-br from-slate-50 via-red-50 to-pink-100 dark:from-gray-900 dark:via-red-900/20 dark:to-pink-900/20 flex items-center justify-center">
    
    <!-- Delete Account Section -->
    <div id="delete-account-section" class="w-full min-h-[80vh] flex items-center justify-center py-4">
        <div class="w-full max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[60vh]">
                <!-- Left Side - Information -->
                <div class="relative overflow-hidden lg:text-left">
                    <div class="absolute inset-0 bg-gradient-to-r from-red-600/10 to-pink-600/10"></div>
                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-8">
                        <div class="text-center">
                            <!-- Warning Icon -->
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl shadow-lg mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                            </div>
                            
                            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                                Delete Your Account
                            </h1>
                            
                            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed mb-8">
                                We're sorry to see you go. Before you proceed, please understand that this action is <strong>permanent and irreversible</strong>. 
                                All your data will be permanently deleted from our systems.
                            </p>

                            <!-- What Will Be Deleted -->
                            <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-700/30 max-w-2xl mx-auto mb-8">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">What Will Be Deleted</h3>
                                <div class="space-y-3 text-left">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Your Profile & Personal Information</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Name, email, profile picture, and account settings</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">All Your Posts & Comments</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Everything you've shared on the platform</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Chat History & Messages</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">All conversations and private messages</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Uploaded Media & Files</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Photos, videos, and other uploaded content</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-2 h-2 bg-red-500 rounded-full mt-2"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Account Activity & Preferences</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Settings, notifications, and usage data</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Important Notice -->
                            <div class="bg-red-50 dark:bg-red-900/20 backdrop-blur-sm rounded-xl p-6 border border-red-200 dark:border-red-800 max-w-2xl mx-auto">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-lg font-semibold text-red-900 dark:text-red-100">Important Notice</h4>
                                        <p class="text-red-700 dark:text-red-300 text-sm">
                                            This action cannot be undone. Once your account is deleted, all data will be permanently removed and cannot be recovered.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Delete Account Form -->
                <div class="max-w-md mx-auto lg:mx-0 w-full">
                    <div class="bg-white dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl p-8 shadow-xl border border-white/20 dark:border-gray-700/30">
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Delete Account</h3>
                            <p class="text-gray-600 dark:text-gray-400">Please confirm your decision to delete your account</p>
                        </div>

                        <!-- Delete Account Form -->
                        <form id="deleteAccountForm" class="space-y-6" action="<?= $baseUrl; ?>/api/users/goodbye" method="POST">
                            <!-- Email Confirmation -->
                            <div class="relative">
                                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-red-600 dark:text-red-400">●</span> Confirm Your Email
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input id="email" name="email" type="email" autocomplete="email" required 
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200"
                                        placeholder="Enter your email address">
                                </div>
                            </div>

                            <!-- Password Confirmation -->
                            <div class="relative">
                                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-red-600 dark:text-red-400">●</span> Confirm Your Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password" name="password" type="password" autocomplete="current-password" required 
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200"
                                        placeholder="Enter your password">
                                </div>
                            </div>

                            <!-- Reason for Deletion -->
                            <div class="relative">
                                <label for="reason" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-red-600 dark:text-red-400">●</span> Reason for Deletion (Optional)
                                </label>
                                <div class="relative">
                                    <select id="reason" name="reason" 
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200">
                                        <option value="">Select a reason (optional)</option>
                                        <option value="privacy_concerns">Privacy concerns</option>
                                        <option value="no_longer_needed">No longer needed</option>
                                        <option value="found_alternative">Found alternative service</option>
                                        <option value="too_many_notifications">Too many notifications</option>
                                        <option value="technical_issues">Technical issues</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Confirmation Checkbox -->
                            <div class="relative">
                                <label for="confirmDeletion" class="flex items-start">
                                    <input type="checkbox" id="confirmDeletion" name="confirmDeletion" required 
                                        class="mt-1 h-4 w-4 text-red-600 border-gray-300 rounded focus:ring-red-500 focus:ring-2">
                                    <span class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                        I understand that this action is <strong>permanent and irreversible</strong>. 
                                        All my data will be permanently deleted and cannot be recovered.
                                    </span>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" id="deleteAccountBtn" disabled
                                    class="w-full flex justify-center items-center py-4 px-6 border border-transparent text-base font-semibold rounded-xl text-white bg-gradient-to-r from-red-600 via-pink-600 to-pink-700 hover:from-red-700 hover:via-pink-700 hover:to-pink-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete My Account
                                </button>
                            </div>
                        </form>

                        <!-- Divider -->
                        <div class="relative my-8">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white/70 dark:bg-gray-800/70 text-gray-500 dark:text-gray-400 font-medium">Changed your mind?</span>
                            </div>
                        </div>

                        <!-- Alternative Actions -->
                        <div class="space-y-3">
                            <a href="<?= $baseUrl; ?>/profile" 
                               class="w-full inline-flex justify-center items-center px-4 py-3 border-2 border-gray-200 dark:border-gray-600 text-sm font-semibold rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Back to Profile
                            </a>
                            
                            <a href="<?= $baseUrl; ?>/support" 
                               class="w-full inline-flex justify-center items-center px-4 py-3 border-2 border-gray-200 dark:border-gray-600 text-sm font-semibold rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Contact Support
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Final Confirmation Modal -->
<div id="finalConfirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-4">Final Confirmation</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Are you absolutely sure you want to delete your account? This action cannot be undone and will permanently remove all your data.
                </p>
            </div>
            <div class="flex justify-center space-x-3 px-4 py-3">
                <button id="cancelFinalDelete" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button id="confirmFinalDelete" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    Yes, Delete My Account
                </button>
            </div>
        </div>
    </div>
</div>
<script src="<?= $baseUrl ?>/assets/js/remove.js?v=<?= $version ?>" defer></script>