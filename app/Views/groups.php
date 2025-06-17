<div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-20 pt-2">
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

    <!-- Create Group Modal -->
    <div id="createGroupModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden" role="dialog" aria-modal="true">
        <div class="min-h-screen px-4 text-center">
            <span class="inline-block h-screen align-middle" aria-hidden="true">&#8203;</span>
            <div class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-2xl">
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
                            <?php foreach ($allUsers as $user): ?>
                                <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                        <p class="text-xs text-gray-400 mt-1">Hold Ctrl (Windows) or Command (Mac) to select multiple users.</p>
                    </div>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" id="cancelCreateGroup" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none">Cancel</button>
                        <button type="submit" class="btn-primary px-4 py-2">Create Group</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Modal open/close logic
const openBtn = document.getElementById('openCreateGroupModal');
const closeBtn = document.getElementById('closeCreateGroupModal');
const cancelBtn = document.getElementById('cancelCreateGroup');
const modal = document.getElementById('createGroupModal');
if (openBtn && modal) {
    openBtn.onclick = () => modal.classList.remove('hidden');
}
if (closeBtn && modal) {
    closeBtn.onclick = () => modal.classList.add('hidden');
}
if (cancelBtn && modal) {
    cancelBtn.onclick = () => modal.classList.add('hidden');
}
// Optional: handle form submission via AJAX here
</script> 