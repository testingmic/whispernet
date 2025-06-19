<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Header Section -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
            <div class="text-center">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-200 text-sm font-medium mb-6">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Profile Dashboard
                </div>
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                    Welcome Back, <?= $user['full_name'] ?? 'User' ?>
                </h1>
                <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed">
                    Manage your profile, track your activity, and customize your experience
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
        <!-- Profile Header Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 mb-8">
            <div class="flex flex-col md:flex-row items-center md:items-start space-y-6 md:space-y-0 md:space-x-8">
                <!-- Profile Avatar -->
                <div class="relative">
                    <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                        <span class="text-4xl font-bold text-white">
                            <?= strtoupper(substr($user['full_name'] ?? 'User', 0, 1)) ?>
                        </span>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-green-500 rounded-full border-4 border-white dark:border-gray-800 flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <!-- Profile Info -->
                <div class="flex-1 text-center md:text-left">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                        <?= $user['full_name'] ?? 'User' ?>
                    </h2>
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-4">
                        <?= $user['email'] ?? 'user@example.com' ?>
                    </p>
                    <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Active Member
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-200">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Verified
                        </span>
                    </div>
                </div>

                <!-- Edit Button -->
                <div class="flex-shrink-0">
                    <a href="<?= $baseUrl ?>/profile/edit" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-purple-600 text-white font-semibold rounded-xl hover:from-blue-600 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Posts Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <a href="<?= $baseUrl ?>/profile/posts" class="block">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['posts'] ?? 0 ?></span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">My Posts</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">View and manage your published content</p>
                </a>
            </div>

            <!-- Replies Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <a href="<?= $baseUrl ?>/profile/replies" class="block">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['comments'] ?? 0 ?></span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">My Replies</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Track your comments and interactions</p>
                </a>
            </div>

            <!-- Votes Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <a href="<?= $baseUrl ?>/profile/votes" class="block">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                            </svg>
                        </div>
                        <span class="text-2xl font-bold text-gray-900 dark:text-white"><?= $stats['votes'] ?? 0 ?></span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">My Votes</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">See your voting history and preferences</p>
                </a>
            </div>
        </div>

        <!-- Settings Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Account Settings
                </h3>
            </div>
            
            <div class="p-6 space-y-6">
                <!-- Notification Settings -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.19 4.19A2 2 0 004 6v10a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Email Notifications</h4>
                            <p class="text-gray-600 dark:text-gray-400">Receive email notifications for new messages and mentions</p>
                        </div>
                    </div>
                    <button type="button" data-setting="email_notifications" data-value="<?= $user['email_notifications'] ?? '0' ?>" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 <?= ($user['email_notifications'] ?? '0') == '1' ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' ?>" role="switch" aria-checked="<?= ($user['email_notifications'] ?? '0') == '1' ? 'true' : 'false' ?>">
                        <span class="<?= ($user['email_notifications'] ?? '0') == '1' ? 'translate-x-5' : 'translate-x-0' ?> pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                            <span class="<?= ($user['email_notifications'] ?? '0') == '1' ? 'opacity-0' : 'opacity-100' ?> ease-in duration-200 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </span>
                    </button>
                </div>

                <!-- Privacy Settings -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Visibility</h4>
                            <p class="text-gray-600 dark:text-gray-400">Make your profile visible to other users</p>
                        </div>
                    </div>
                    <button type="button" data-setting="profile_visibility" data-value="<?= $user['profile_visibility'] ?? '1' ?>" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 <?= ($user['profile_visibility'] ?? '1') == '1' ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' ?>" role="switch" aria-checked="<?= ($user['profile_visibility'] ?? '1') == '1' ? 'true' : 'false' ?>">
                        <span class="<?= ($user['profile_visibility'] ?? '1') == '1' ? 'translate-x-5' : 'translate-x-0' ?> pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                            <span class="<?= ($user['profile_visibility'] ?? '1') == '1' ? 'opacity-0' : 'opacity-100' ?> ease-in duration-200 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </span>
                    </button>
                </div>

                <!-- Search Visibility -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Appear in Search</h4>
                            <p class="text-gray-600 dark:text-gray-400">Make your profile appear in search results</p>
                        </div>
                    </div>
                    <button type="button" data-setting="search_visibility" data-value="<?= $user['search_visibility'] ?? '0' ?>" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 <?= ($user['search_visibility'] ?? '0') == '1' ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' ?>" role="switch" aria-checked="<?= ($user['search_visibility'] ?? '0') == '1' ? 'true' : 'false' ?>">
                        <span class="<?= ($user['search_visibility'] ?? '0') == '1' ? 'translate-x-5' : 'translate-x-0' ?> pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                            <span class="<?= ($user['search_visibility'] ?? '0') == '1' ? 'opacity-0' : 'opacity-100' ?> ease-in duration-200 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </span>
                    </button>
                </div>

                <!-- Theme Settings -->
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div class="flex items-start">
                        <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mr-4">
                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white">Dark Mode</h4>
                            <p class="text-gray-600 dark:text-gray-400">Switch between light and dark theme</p>
                        </div>
                    </div>
                    <button type="button" data-setting="dark_mode" data-value="<?= $user['dark_mode'] ?? '0' ?>" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 <?= ($user['dark_mode'] ?? '0') == '1' ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' ?>" role="switch" aria-checked="<?= ($user['dark_mode'] ?? '0') == '1' ? 'true' : 'false' ?>">
                        <span class="<?= ($user['dark_mode'] ?? '0') == '1' ? 'translate-x-5' : 'translate-x-0' ?> pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                            <span class="<?= ($user['dark_mode'] ?? '0') == '1' ? 'opacity-0' : 'opacity-100' ?> ease-in duration-200 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center">
                <svg class="w-6 h-6 mr-3 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Quick Actions
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="<?= $baseUrl ?>/create" class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl hover:from-blue-100 hover:to-blue-200 dark:hover:from-blue-800/30 dark:hover:to-blue-700/30 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Create Post</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Share something new</p>
                    </div>
                </a>

                <a href="<?= $baseUrl ?>/upgrades" class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl hover:from-purple-100 hover:to-purple-200 dark:hover:from-purple-800/30 dark:hover:to-purple-700/30 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Upgrade</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Unlock premium features</p>
                    </div>
                </a>

                <a href="<?= $baseUrl ?>/settings" class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl hover:from-green-100 hover:to-green-200 dark:hover:from-green-800/30 dark:hover:to-green-700/30 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Settings</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Manage preferences</p>
                    </div>
                </a>

                <a href="<?= $baseUrl ?>/help" class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl hover:from-yellow-100 hover:to-yellow-200 dark:hover:from-yellow-800/30 dark:hover:to-yellow-700/30 transition-all duration-200 group">
                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900 dark:text-white">Help</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Get support</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced Settings Toggle Functionality
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('[data-setting]');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function() {
            const setting = this.getAttribute('data-setting');
            const currentValue = this.getAttribute('data-value');
            const newValue = currentValue === '1' ? '0' : '1';
            
            // Update button state
            this.setAttribute('data-value', newValue);
            this.setAttribute('aria-checked', newValue === '1' ? 'true' : 'false');
            
            // Update visual state
            const toggle = this.querySelector('span');
            const icon = this.querySelector('svg');
            
            if (newValue === '1') {
                this.classList.remove('bg-gray-200', 'dark:bg-gray-700');
                this.classList.add('bg-blue-600');
                toggle.classList.remove('translate-x-0');
                toggle.classList.add('translate-x-5');
                if (icon) icon.classList.add('opacity-0');
            } else {
                this.classList.remove('bg-blue-600');
                this.classList.add('bg-gray-200', 'dark:bg-gray-700');
                toggle.classList.remove('translate-x-5');
                toggle.classList.add('translate-x-0');
                if (icon) icon.classList.remove('opacity-0');
            }
            
            // Send update to server
            updateSetting(setting, newValue);
        });
    });
});

async function updateSetting(setting, value) {
    try {
        const response = await fetch('/api/profile/settings', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                setting: setting,
                value: value,
                token: localStorage.getItem('token')
            })
        });
        
        if (!response.ok) {
            throw new Error('Failed to update setting');
        }
        
        // Show success notification
        showNotification('Setting updated successfully!', 'success');
    } catch (error) {
        console.error('Error updating setting:', error);
        showNotification('Failed to update setting. Please try again.', 'error');
    }
}

// Notification function
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-xl shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
    
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    notification.classList.add(bgColor, 'text-white');
    
    notification.innerHTML = `
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 5 seconds
    setTimeout(() => {
        notification.classList.add('translate-x-full');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 5000);
}
</script>