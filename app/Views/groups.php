<div class="min-h-[90vh] bg-gray-50 dark:bg-gray-900 pt-2">
    <div class="max-w-3xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Your Groups</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Groups you have joined</p>
            </div>
            <button id="openCreateGroupModal" class="btn-primary flex items-center px-4 py-2">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Group
            </button>
        </div>

        <!-- Groups List -->
        <div class="space-y-4">
            <?php if (empty($groups)): ?>
                <div class="p-6 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-lg shadow">
                    You haven't joined any groups yet.
                </div>
            <?php else: ?>
                <?php foreach ($groups as $group): ?>
                    <div class="card p-4 flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-blue-700 dark:text-blue-300"><?= htmlspecialchars($group['title']) ?></h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Participants: <?= count($group['participants']) ?></p>
                        </div>
                        <a href="/groups/<?= $group['id'] ?>" class="btn-primary px-4 py-2">Open</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal Trigger Button (for testing) -->
    <button id="testOpenModal" class="fixed bottom-4 right-4 z-50 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg">Test Modal</button>

    <!-- Minimal Working Modal Example (fixed for stacking/centering) -->
    <div id="testModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-md w-full">
            <h2 class="text-lg font-bold mb-4">Modal Content</h2>
            <p>This is a test modal. If you see this, your modal works!</p>
            <button id="testCloseModal" class="mt-4 px-4 py-2 bg-gray-200 rounded">Close</button>
        </div>
    </div>

    <!-- Main Group Modal (fixed for stacking/centering) -->
    <div id="createGroupModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden" role="dialog" aria-modal="true">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-md w-full">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Create New Group</h3>
                <button type="button" id="closeCreateGroupModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="createGroupForm">
                <div class="mb-4">
                    <label for="groupTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Group Title</label>
                    <input type="text" id="groupTitle" name="title" required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Enter group name">
                </div>
                <div class="mb-4">
                    <label for="groupParticipants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Add Participants</label>
                    <select id="groupParticipants" name="participants[]" multiple required class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <?php foreach ($allUsers ?? [] as $user): ?>
                            <option value="<?= $user['id'] ?? '' ?>"><?= htmlspecialchars($user['name'] ?? '') ?> (<?= htmlspecialchars($user['email'] ?? '') ?>)</option>
                        <?php endforeach; ?>
                    </select>


                    <!-- Modal Trigger Button (for testing) -->
                    <button id="testOpenModal" class="fixed bottom-4 right-4 z-50 bg-blue-600 text-white px-4 py-2 rounded-full shadow-lg">Test Modal</button>

                    <!-- Minimal Working Modal Example -->
                    <div id="testModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
                        <div class="min-h-screen px-4 text-center">
                            <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
                            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle bg-white rounded-2xl">
                                <h2 class="text-lg font-bold mb-4">Modal Content</h2>
                                <p>This is a test modal. If you see this, your modal works!</p>
                                <button id="testCloseModal" class="mt-4 px-4 py-2 bg-gray-200 rounded">Close</button>
                            </div>
                        </div>
                    </div>

                    <!-- New Modal Overlay -->
                    <div id="modalOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-md w-full">
                            <h2 class="text-lg font-bold mb-4">Modal Content</h2>
                            <button id="closeModal" class="mt-4 px-4 py-2 bg-gray-200 rounded">Close</button>
                        </div>
                    </div>
                </div>

                <script>
                    // Patch for main group modal
                    const openBtn = document.getElementById('openCreateGroupModal');
                    const closeBtn = document.getElementById('closeCreateGroupModal');
                    const cancelBtn = document.getElementById('cancelCreateGroup');
                    const modal = document.getElementById('createGroupModal');
                    if (openBtn && modal) openBtn.onclick = () => modal.classList.remove('hidden');
                    if (closeBtn && modal) closeBtn.onclick = () => modal.classList.add('hidden');
                    if (cancelBtn && modal) cancelBtn.onclick = () => modal.classList.add('hidden');

                    // Minimal working modal test
                    const testOpen = document.getElementById('testOpenModal');
                    const testModal = document.getElementById('testModal');
                    const testClose = document.getElementById('testCloseModal');
                    if (testOpen && testModal) testOpen.onclick = () => testModal.classList.remove('hidden');
                    if (testClose && testModal) testClose.onclick = () => testModal.classList.add('hidden');

                    document.getElementById('openModal').onclick = () => document.getElementById('modalOverlay').classList.remove('hidden');
                    document.getElementById('closeModal').onclick = () => document.getElementById('modalOverlay').classList.add('hidden');
                </script>

                <style>
                    #modalOverlay {
                        z-index: 9999;
                    }
                </style>
            </form>
        </div>
    </div>
</div>