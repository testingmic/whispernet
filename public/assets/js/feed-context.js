document.addEventListener('DOMContentLoaded', function() {
    // Create context menu element
    const contextMenu = document.createElement('div');
    contextMenu.className = 'fixed hidden bg-white dark:bg-gray-800 shadow-lg rounded-lg py-2 z-50';
    document.body.appendChild(contextMenu);
    
    // Track active post
    let activePostId = null;
    
    // Add context menu to all feed items
    document.querySelectorAll('.feed-item').forEach(item => {
        // Handle right-click
        item.addEventListener('contextmenu', function(e) {
            e.preventDefault();
            showContextMenu(e, this);
        });

        // Handle three dots menu click
        const menuButton = item.querySelector('button[aria-label="Post options"]');
        if (menuButton) {
            menuButton.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                showContextMenu(e, item);
            });
        }
    });
    
    // Function to show context menu
    function showContextMenu(e, item) {
        const postId = item.dataset.postId;
        activePostId = postId;
        
        // Position context menu
        if (e.target.closest('button[aria-label="Post options"]')) {
            // Position relative to the three dots button
            const buttonRect = e.target.getBoundingClientRect();
            contextMenu.style.left = `${buttonRect.left}px`;
            contextMenu.style.top = `${buttonRect.bottom + 5}px`;
        } else {
            // Position relative to right-click
            contextMenu.style.left = `${e.pageX}px`;
            contextMenu.style.top = `${e.pageY}px`;
        }
        
        // Update menu items based on user permissions
        updateContextMenu(postId);
        
        // Show menu
        contextMenu.classList.remove('hidden');
    }
    
    // Hide context menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!contextMenu.contains(e.target) && !e.target.closest('button[aria-label="Post options"]')) {
            contextMenu.classList.add('hidden');
        }
    });
    
    // Update Context Menu
    async function updateContextMenu(postId) {
        try {
            const response = await fetch(`/api/posts/${postId}/permissions`);
            
            if (!response.ok) {
                throw new Error('Failed to load post permissions');
            }
            
            const permissions = await response.json();
            
            // Build menu items
            let menuItems = [];
            
            // Basic actions
            menuItems.push({
                label: 'Share',
                icon: 'share',
                action: () => sharePost(postId)
            });
            
            menuItems.push({
                label: 'Report',
                icon: 'flag',
                action: () => reportPost(postId)
            });
            
            // Owner/Moderator actions
            if (permissions.can_edit) {
                menuItems.push({
                    label: 'Edit',
                    icon: 'pencil',
                    action: () => editPost(postId)
                });
            }
            
            if (permissions.can_delete) {
                menuItems.push({
                    label: 'Delete',
                    icon: 'trash',
                    action: () => deletePost(postId)
                });
            }
            
            if (permissions.can_pin) {
                menuItems.push({
                    label: 'Pin',
                    icon: 'pin',
                    action: () => pinPost(postId)
                });
            }
            
            // Render menu items
            contextMenu.innerHTML = menuItems.map(item => `
                <button class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center"
                        onclick="event.stopPropagation(); ${item.action.name}(${postId})">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${getIconPath(item.icon)}
                    </svg>
                    ${item.label}
                </button>
            `).join('');
            
        } catch (error) {
            console.error('Error updating context menu:', error);
            showNotification('Failed to load post options', 'error');
        }
    }
    
    // Post Actions
    window.sharePost = async function(postId) {
        try {
            const response = await fetch(`/api/posts/${postId}/share`, {
                method: 'POST'
            });
            
            if (!response.ok) {
                throw new Error('Failed to share post');
            }
            
            const data = await response.json();
            
            // Copy share link to clipboard
            await navigator.clipboard.writeText(data.share_url);
            showNotification('Share link copied to clipboard', 'success');
            
        } catch (error) {
            console.error('Error sharing post:', error);
            showNotification('Failed to share post', 'error');
        }
    };
    
    window.reportPost = function(postId) {
        window.location.href = `/report?content_id=${postId}`;
    };
    
    window.editPost = function(postId) {
        window.location.href = `/posts/${postId}/edit`;
    };
    
    window.deletePost = async function(postId) {
        if (!confirm('Are you sure you want to delete this post?')) {
            return;
        }
        
        try {
            const response = await fetch(`/api/posts/${postId}`, {
                method: 'DELETE'
            });
            
            if (!response.ok) {
                throw new Error('Failed to delete post');
            }
            
            showNotification('Post deleted successfully', 'success');
            
            // Remove post from feed
            const post = document.querySelector(`[data-posts-id="${postId}"]`);
            if (post) {
                post.remove();
            }
            
        } catch (error) {
            console.error('Error deleting post:', error);
            showNotification('Failed to delete post', 'error');
        }
    };
    
    window.pinPost = async function(postId) {
        try {
            const response = await fetch(`/api/posts/${postId}/pin`, {
                method: 'POST'
            });
            
            if (!response.ok) {
                throw new Error('Failed to pin post');
            }
            
            showNotification('Post pinned successfully', 'success');
            
        } catch (error) {
            console.error('Error pinning post:', error);
            showNotification('Failed to pin post', 'error');
        }
    };
    
    // Helper Functions
    function getIconPath(icon) {
        const paths = {
            share: 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z',
            flag: 'M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9',
            pencil: 'M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z',
            trash: 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
            pin: 'M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z'
        };
        
        return `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${paths[icon]}"/>`;
    }
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