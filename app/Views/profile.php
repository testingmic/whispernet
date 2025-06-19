<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 px-3">
        <!-- Profile Header -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <div class="w-24 h-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-2xl font-medium text-gray-600 dark:text-gray-300">
                                <?= substr($user['full_name'] ?? 'User', 0, 1) ?>
                            </span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <?= $user['full_name'] ?? 'User' ?>
                        </h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <?= $user['email'] ?? 'user@example.com' ?>
                        </p>
                    </div>
                    <div>
                        <a type="button" href="<?= $baseUrl ?>/profile/edit" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Content -->
        <div class="grid grid-cols-1 lg:grid-cols-3">
            <!-- Left Column - Stats -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg mb-4">
                <div class="px-4 py-3 sm:p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Activity Stats</h3>
                </div>
            </div>

            <!-- Middle Column - Recent Activity -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg lg:col-span-2 mb-3 bg-gradient-to-r border-t border-blue-500 hover:border-blue-400 hover:shadow-md transition-all duration-300">
                <a href="<?= $baseUrl ?>/profile/posts">
                    <div class="px-4 py-4 sm:p-6  ">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">My Posts</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?= $stats['posts'] ?? 0 ?></span>
                        </div>
                    </div>
                </a>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg lg:col-span-2 mb-3 bg-gradient-to-r border-t border-blue-500 hover:border-blue-400 hover:shadow-md transition-all duration-300">
                <a href="<?= $baseUrl ?>/profile/replies">
                    <div class="px-4 py-4 sm:p-6 ">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">My Replies</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?= $stats['comments'] ?? 0 ?></span>
                        </div>
                    </div>
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 shadow rounded-lg bg-gradient-to-r border-t border-blue-500 lg:col-span-2 mb-3  hover:border-blue-400 hover:shadow-md transition-all duration-300">
                <a href="<?= $baseUrl ?>/profile/votes">
                    <div class="px-4 py-4 sm:p-6 ">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500 dark:text-gray-400">My Votes</span>
                            <span class="text-sm font-medium text-gray-900 dark:text-white"><?= $stats['votes'] ?? 0 ?></span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Settings Section -->
        <div class="mt-4 bg-white dark:bg-gray-800 shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Settings</h3>
                <div class="space-y-4">
                    <!-- Notification Settings -->
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Email Notifications</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Receive email notifications for new messages and mentions</p>
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
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Profile Visibility</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Make your profile visible to other users</p>
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

                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Appear in Search</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Make your profile appear in search results</p>
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
                    <div class="flex items-center justify-between">
                        <div>
                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Dark Mode</h4>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Switch between light and dark theme</p>
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
        </div>
        <div class="px-4 py-6 sm:p-6">&nbsp;</div>
    </div>
</div>