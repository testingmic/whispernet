<div class="min-h-[90vh] bg-gray-50 dark:bg-gray-900 pt-2">
    <div class="max-w-7xl mx-auto px-4 py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Your Groups</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Connect and collaborate with your communities</p>
            </div>
            <button id="openCreateGroupModal" class="btn-primary flex items-center px-4 py-2 rounded-lg transition-all duration-200 hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Group
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="mb-6">
            <div class="relative">
                <input type="text" id="groupSearch" placeholder="Search groups..." 
                    class="w-full px-4 py-2 pl-10 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <div class="groupsSkeleton">
            <?= loadingSkeleton(1, false) ?>
        </div>

        <!-- Groups Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <?php if (empty($groups)): ?>
                <!-- <div class="col-span-full">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-8 text-center">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Groups Yet</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">You haven't joined any groups yet. Create a new group or join existing ones to get started.</p>
                        <button id="openCreateGroupModal" class="btn-primary inline-flex items-center px-4 py-2 rounded-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Your First Group
                        </button>
                    </div>
                </div> -->
            <?php else: ?>
                <?php foreach ($groups as $group): ?>
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden transition-all duration-200 hover:shadow-lg">
                        <!-- Group Header -->
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium text-lg">
                                        <?= substr($group['title'] ?? 'G', 0, 1) ?>
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($group['title'] ?? 'N/A') ?></h2>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Created <?= date('M j, Y', strtotime($group['created_at'] ?? 'now')) ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        <?= count($group['participants'] ?? []) ?> members
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Group Description -->
                            <p class="text-sm text-gray-600 dark:text-gray-300 mb-4 line-clamp-2">
                                <?= htmlspecialchars($group['description'] ?? 'No description available') ?>
                            </p>

                            <!-- Group Stats -->
                            <div class="grid grid-cols-3 gap-4 mb-4">
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white"><?= $group['posts_count'] ?? 0 ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Posts</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white"><?= $group['events_count'] ?? 0 ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Events</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-lg font-semibold text-gray-900 dark:text-white"><?= $group['files_count'] ?? 0 ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">Files</div>
                                </div>
                            </div>

                            <!-- Group Actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-2">
                                    <?php foreach (array_slice($group['participants'] ?? [], 0, 3) as $participant): ?>
                                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300">
                                            <?= substr($participant['name'] ?? 'U', 0, 1) ?>
                                        </div>
                                    <?php endforeach; ?>
                                    <?php if (count($group['participants'] ?? []) > 3): ?>
                                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center text-xs font-medium text-gray-500 dark:text-gray-400">
                                            +<?= count($group['participants'] ?? []) - 3 ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <a href="<?= configs('baseUrl') ?>/groups/<?= $group['id'] ?? '' ?>" 
                                   class="btn-primary px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md">
                                    View Group
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    

    <!-- Create Group Modal -->
    <div id="createGroupModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden" role="dialog" aria-modal="true">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-md w-full mx-4">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Create New Group</h3>
                <button type="button" id="closeCreateGroupModal" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form id="createGroupForm" class="space-y-6">
                <div>
                    <label for="groupTitle" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Group Title</label>
                    <input type="text" id="groupTitle" name="title" required 
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" 
                           placeholder="Enter group name">
                </div>
                <div>
                    <label for="groupDescription" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                    <textarea id="groupDescription" name="description" rows="3" 
                              class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white" 
                              placeholder="Describe your group's purpose"></textarea>
                </div>
                <div>
                    <label for="groupParticipants" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Add Participants</label>
                    <select id="groupParticipants" name="participants[]" multiple required 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                        <?php foreach ($allUsers ?? [] as $user): ?>
                            <option value="<?= $user['id'] ?? '' ?>">
                                <?= htmlspecialchars($user['name'] ?? '') ?> (<?= htmlspecialchars($user['email'] ?? '') ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <button type="button" id="cancelCreateGroup" 
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="btn-primary px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:shadow-md">
                        Create Group
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Modal functionality
    const openBtn = document.getElementById('openCreateGroupModal');
    const closeBtn = document.getElementById('closeCreateGroupModal');
    const cancelBtn = document.getElementById('cancelCreateGroup');
    const modal = document.getElementById('createGroupModal');

    if (openBtn && modal) openBtn.onclick = () => modal.classList.remove('hidden');
    if (closeBtn && modal) closeBtn.onclick = () => modal.classList.add('hidden');
    if (cancelBtn && modal) cancelBtn.onclick = () => modal.classList.add('hidden');

    // Search functionality
    const searchInput = document.getElementById('groupSearch');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            const groupCards = document.querySelectorAll('.group-card');
            
            groupCards.forEach(card => {
                const title = card.querySelector('h2').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    }
</script>