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
                            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center overflow-hidden">
                                <?php if (!empty($user['profile_image'])): ?>
                                    <img src="<?= $user['profile_image'] ?>" alt="Profile Picture" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-xl sm:text-2xl font-medium text-gray-600 dark:text-gray-300">
                                        <?= substr($user['name'] ?? 'User', 0, 1) ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            <button type="button" id="mediaUpload" class="absolute bottom-0 right-0 p-1 bg-blue-600 rounded-full text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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
                    <!-- Hidden file input for profile picture upload -->
                    <input type="file" id="profileImageInput" accept="image/*" class="hidden">
                </div>

                <!-- Basic Information -->
                <div class="grid grid-cols-1 gap-4 sm:gap-6 sm:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                        <input type="text" name="name" id="name" value="<?= $user['full_name'] ?? '' ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm dark:bg-gray-800">
                    </div>
                    <div>
                        <label for="username" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Username</label>
                        <input type="text" name="username" id="username" value="<?= $user['username'] ?? '' ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm dark:bg-gray-800">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                        <input type="email" disabled name="email" id="email" value="<?= $user['email'] ?? '' ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm dark:bg-gray-800">
                        <span class="text-xs text-gray-500 dark:text-gray-400">(Not Visible to other users)</span>
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

                <?php 
                // get the user settings
                $userSettings = listUserSettings($settings ?? []);

                // loop through the user settings
                foreach($userSettings as $ikey => $setting) {
                    if(!empty($setting['noDisplay'])) continue;
                ?>
                <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                    <div class="flex items-start">
                        <div class="<?= $setting['class'] ?>">
                            <svg class="<?= $setting['icon_class'] ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <?php if(isset($setting['top_icon'])) { ?>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $setting['top_icon']; ?>"></path>
                                <?php } ?>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $setting['icon'] ?>"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold text-gray-900 dark:text-white"><?= $setting['title'] ?></h4>
                            <p class="text-gray-600 dark:text-gray-400"><?= $setting['description'] ?></p>
                        </div>
                    </div>
                    <button type="button" data-setting="<?= $ikey ?>" data-value="<?= $setting['value'] ?>" class="relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 <?= ($setting['value'] ?? '0') == 1 ? 'bg-blue-600' : 'bg-gray-200 dark:bg-gray-700' ?>" role="switch" aria-checked="<?= ($setting['value'] ?? '0') == 1 ? 'true' : 'false' ?>">
                        <span class="<?= ($setting['value'] ?? '0') == 1 ? 'translate-x-5' : 'translate-x-0' ?> pointer-events-none relative inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200">
                            <span class="<?= ($setting['value'] ?? '0') == 1 ? 'opacity-0' : 'opacity-100' ?> ease-in duration-200 absolute inset-0 h-full w-full flex items-center justify-center transition-opacity" aria-hidden="true">
                                <svg class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 12 12">
                                    <path d="M4 8l2-2m0 0l2-2M6 6L4 4m2 2l2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </span>
                    </button>
                </div>
                <?php  } ?>
                
            </div>
        </div>

        <?= custom_button('Go to Profile', $baseUrl.'/profile') ?>

    </div>
</div>