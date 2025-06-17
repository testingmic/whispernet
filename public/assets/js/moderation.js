document.addEventListener('DOMContentLoaded', function() {
    // Initialize
    loadPendingContent();
    loadFlaggedUsers();
    
    // Event Listeners
    document.getElementById('refreshContent').addEventListener('click', function() {
        loadPendingContent();
        loadFlaggedUsers();
    });
    
    document.getElementById('banUser').addEventListener('click', function() {
        showModerationModal('ban');
    });
    
    document.getElementById('warnUser').addEventListener('click', function() {
        showModerationModal('warn');
    });
    
    document.getElementById('approveContent').addEventListener('click', function() {
        showModerationModal('approve');
    });
    
    // Load Pending Content
    async function loadPendingContent() {
        try {
            const response = await fetch('/api/moderation/pending-content');
            
            if (!response.ok) {
                throw new Error('Failed to load pending content');
            }
            
            const content = await response.json();
            renderPendingContent(content);
            
        } catch (error) {
            console.error('Error loading pending content:', error);
            showNotification('Failed to load pending content', 'error');
        }
    }
    
    // Load Flagged Users
    async function loadFlaggedUsers() {
        try {
            const response = await fetch('/api/moderation/flagged-users');
            
            if (!response.ok) {
                throw new Error('Failed to load flagged users');
            }
            
            const users = await response.json();
            renderFlaggedUsers(users);
            
        } catch (error) {
            console.error('Error loading flagged users:', error);
            showNotification('Failed to load flagged users', 'error');
        }
    }
    
    // Render Pending Content
    function renderPendingContent(content) {
        const container = document.getElementById('pendingContent');
        container.innerHTML = '';
        
        content.forEach(item => {
            const div = document.createElement('div');
            div.className = 'p-4 border-b border-gray-200 dark:border-gray-700 last:border-0';
            div.innerHTML = `
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold">${item.title || 'Untitled'}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Posted by ${item.author}</p>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn-success text-sm" onclick="moderateContent(${item.id}, 'approve')">
                            Approve
                        </button>
                        <button class="btn-danger text-sm" onclick="moderateContent(${item.id}, 'reject')">
                            Reject
                        </button>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-gray-600 dark:text-gray-300">${item.content}</p>
                </div>
            `;
            container.appendChild(div);
        });
    }
    
    // Render Flagged Users
    function renderFlaggedUsers(users) {
        const container = document.getElementById('flaggedUsers');
        container.innerHTML = '';
        
        users.forEach(user => {
            const div = document.createElement('div');
            div.className = 'p-4 border-b border-gray-200 dark:border-gray-700 last:border-0';
            div.innerHTML = `
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="font-semibold">${user.username}</h4>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Flagged ${user.flag_count} times
                        </p>
                    </div>
                    <div class="flex gap-2">
                        <button class="btn-warning text-sm" onclick="moderateUser(${user.id}, 'warn')">
                            Warn
                        </button>
                        <button class="btn-danger text-sm" onclick="moderateUser(${user.id}, 'ban')">
                            Ban
                        </button>
                    </div>
                </div>
                <div class="mt-2">
                    <p class="text-sm text-gray-600 dark:text-gray-300">
                        Last flagged: ${new Date(user.last_flagged).toLocaleDateString()}
                    </p>
                </div>
            `;
            container.appendChild(div);
        });
    }
    
    // Show Moderation Modal
    window.showModerationModal = function(action) {
        const modal = document.getElementById('moderationModal');
        const form = document.getElementById('moderationForm');
        
        let formContent = '';
        
        switch (action) {
            case 'ban':
                formContent = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">User ID</label>
                            <input type="text" id="userId" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Reason</label>
                            <textarea id="reason" class="form-input w-full" rows="3" required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Duration</label>
                            <select id="duration" class="form-input w-full">
                                <option value="1">1 day</option>
                                <option value="7">7 days</option>
                                <option value="30">30 days</option>
                                <option value="permanent">Permanent</option>
                            </select>
                        </div>
                    </div>
                `;
                break;
                
            case 'warn':
                formContent = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">User ID</label>
                            <input type="text" id="userId" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Warning Message</label>
                            <textarea id="warningMessage" class="form-input w-full" rows="3" required></textarea>
                        </div>
                    </div>
                `;
                break;
                
            case 'approve':
                formContent = `
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium mb-2">Content ID</label>
                            <input type="text" id="contentId" class="form-input w-full" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-2">Notes (Optional)</label>
                            <textarea id="notes" class="form-input w-full" rows="3"></textarea>
                        </div>
                    </div>
                `;
                break;
        }
        
        form.innerHTML = formContent;
        modal.classList.remove('hidden');
        
        // Handle form submission
        document.getElementById('confirmAction').onclick = async function() {
            try {
                let response;
                
                switch (action) {
                    case 'ban':
                        response = await fetch('/api/moderation/ban', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                user_id: document.getElementById('userId').value,
                                reason: document.getElementById('reason').value,
                                duration: document.getElementById('duration').value
                            })
                        });
                        break;
                        
                    case 'warn':
                        response = await fetch('/api/moderation/warn', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                user_id: document.getElementById('userId').value,
                                message: document.getElementById('warningMessage').value
                            })
                        });
                        break;
                        
                    case 'approve':
                        response = await fetch('/api/moderation/approve', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                content_id: document.getElementById('contentId').value,
                                notes: document.getElementById('notes').value
                            })
                        });
                        break;
                }
                
                if (!response.ok) {
                    throw new Error('Failed to perform moderation action');
                }
                
                showNotification('Action performed successfully', 'success');
                modal.classList.add('hidden');
                loadPendingContent();
                loadFlaggedUsers();
                
            } catch (error) {
                console.error('Error performing moderation action:', error);
                showNotification('Failed to perform action', 'error');
            }
        };
    };
    
    // Close Modal
    document.querySelectorAll('.modal-close').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('moderationModal').classList.add('hidden');
        });
    });
});

function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    } text-white`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
} 