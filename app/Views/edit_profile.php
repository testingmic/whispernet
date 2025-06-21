<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-20 pt-2">
    <div class="max-w-3xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Profile</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update your profile information and preferences</p>
        </div>

        <!-- Profile Form -->
        <div class="mt-8 mb-6 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
            <form id="editProfileForm" class="space-y-6 p-4 sm:p-6">
                <!-- Profile Picture -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Profile Picture</label>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                <span class="text-xl sm:text-2xl font-medium text-gray-600 dark:text-gray-300">
                                    <?= substr($user['name'] ?? 'User', 0, 1) ?>
                                </span>
                            </div>
                            <button type="button" class="absolute bottom-0 right-0 p-1 bg-blue-600 rounded-full text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </button>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Upload a new profile picture</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">JPG, PNG or GIF (max. 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Basic Information -->
                <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                        <input type="text" name="name" id="name" value="<?= $user['name'] ?? '' ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                        <input type="email" name="email" id="email" value="<?= $user['email'] ?? '' ?>" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-3 border-gray-200 dark:border-gray-700">
                    <a href="<?= $baseUrl ?>/profile" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Go Back
                    </a>
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
        
        
        <!-- Settings Section -->
        <div class="bg-white dark:bg-gray-800 mb-4 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
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

        <?= custom_button('Go to Profile', $baseUrl.'/profile') ?>

    </div>
</div>