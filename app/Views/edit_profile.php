<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-20 pt-2 p-6">
    <div class="max-w-3xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Profile</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update your profile information and preferences</p>
        </div>

        <!-- Profile Form -->
        <div class="mt-8 mb-4 bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-6">
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
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender</label>
                        <select name="gender" id="gender" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm dark:bg-gray-800">
                            <option value="">Select Gender</option>
                            <?php foreach(['Male', 'Female', 'Other', 'Prefer not to say'] as $gender) { ?>
                                <option <?= $user['gender'] == $gender ? 'selected' : '' ?> value="<?= $gender ?>"><?= $gender ?></option>
                            <?php } ?>
                        </select>
                        <span class="text-xs text-gray-500 dark:text-gray-400">(Not Visible to other users)</span>
                    </div>
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Location</label>
                        <input type="text" name="location" id="location" value="<?= $user['location'] ?? '' ?>" class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm dark:bg-gray-800">
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
                    <button id="saveProfile" type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
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

        <!-- Delete Account Section -->
        <div class="bg-white dark:bg-gray-800 mb-4 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-xl font-bold text-red-600 dark:text-red-400 flex items-center">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Danger Zone
                </h3>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between p-4 bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-800">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-lg font-semibold text-red-900 dark:text-red-100">Delete Account</h4>
                            <p class="text-red-700 dark:text-red-300">Once you delete your account, there is no going back.</p>
                        </div>
                    </div>
                    <button type="button" id="deleteAccountBtn" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                        Delete Account
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Account Confirmation Modal -->
        <div id="deleteAccountModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 hidden">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-4">Delete Account</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Are you sure you want to delete your account? This action cannot be undone and will permanently remove all your data, including:
                        </p>
                        <ul class="text-sm text-gray-500 dark:text-gray-400 mt-3 text-left list-disc list-inside space-y-1">
                            <li>Your profile and personal information</li>
                            <li>All your posts and comments</li>
                            <li>Your chat history and messages</li>
                            <li>Your uploaded media and files</li>
                        </ul>
                        <div class="mt-4">
                            <label for="confirmDelete" class="flex items-center">
                                <input type="checkbox" id="confirmDelete" class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">I understand that this action is irreversible</span>
                            </label>
                        </div>
                    </div>
                    <div class="flex justify-center space-x-3 px-4 py-3">
                        <button id="cancelDelete" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                            Cancel
                        </button>
                        <button id="confirmDeleteBtn" disabled class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?= custom_button('Go to Profile', $baseUrl.'/profile') ?>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteAccountBtn = document.getElementById('deleteAccountBtn');
    const deleteAccountModal = document.getElementById('deleteAccountModal');
    const cancelDelete = document.getElementById('cancelDelete');
    const confirmDelete = document.getElementById('confirmDelete');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

    // Show modal
    deleteAccountBtn.addEventListener('click', function() {
        deleteAccountModal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    });

    // Hide modal
    function hideModal() {
        deleteAccountModal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        confirmDelete.checked = false;
        confirmDeleteBtn.disabled = true;
    }

    cancelDelete.addEventListener('click', hideModal);

    // Close modal when clicking outside
    deleteAccountModal.addEventListener('click', function(e) {
        if (e.target === deleteAccountModal) {
            hideModal();
        }
    });

    // Enable/disable confirm button based on checkbox
    confirmDelete.addEventListener('change', function() {
        confirmDeleteBtn.disabled = !this.checked;
    });

    // Handle account deletion
    confirmDeleteBtn.addEventListener('click', function() {
        if (!confirmDelete.checked) return;

        // Show loading state
        confirmDeleteBtn.disabled = true;
        confirmDeleteBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Deleting...';

        // Make API call to delete account
        fetch('<?= $baseUrl ?>/api/users/goodbye', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                token: AppState.getToken()
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status == 'success') {
                // Redirect to logout or home page
                window.location.href = '<?= $baseUrl ?>/logout';
            } else {
                AppState.ShowNotification('Error deleting account: ' + (data.message || 'Unknown error'), 'error');
                hideModal();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            AppState.ShowNotification('Error deleting account. Please try again.', 'error');
            hideModal();
        });
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !deleteAccountModal.classList.contains('hidden')) {
            hideModal();
        }
    });
});
</script>