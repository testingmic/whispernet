// User Manager
const UserManager = {
    currentPage: 1,
    limit: 10,
    selectedUsers: new Set(),
    filters: {
        search: '',
        status: 'all',
        role: 'all'
    },

    init() {
        this.setupEventListeners();
        this.loadUsers();
        this.loadStats();
    },

    setupEventListeners() {
        // Search input
        document.getElementById('searchInput').addEventListener('input', (e) => {
            this.filters.search = e.target.value;
            this.debounce(() => this.loadUsers(), 500);
        });

        // Status filter
        document.getElementById('statusFilter').addEventListener('change', (e) => {
            this.filters.status = e.target.value;
            this.loadUsers();
        });

        // Role filter
        document.getElementById('roleFilter').addEventListener('change', (e) => {
            this.filters.role = e.target.value;
            this.loadUsers();
        });

        // Select all checkbox
        document.getElementById('selectAll').addEventListener('change', (e) => {
            this.toggleSelectAll(e.target.checked);
        });

        // User form submission
        document.getElementById('userForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.saveUser();
        });
    },

    async loadUsers() {
        try {
            this.showLoading();
            
            const params = new URLSearchParams({
                page: this.currentPage,
                limit: this.limit,
                search: this.filters.search,
                status: this.filters.status,
                role: this.filters.role
            });

            const response = await fetch(`/api/users?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load users');
            }

            const data = await response.json();
            this.updateUsersTable(data.users);
            this.updatePagination(data.pagination);
            
        } catch (error) {
            console.error('Error loading users:', error);
            this.showError('Failed to load users');
        } finally {
            this.hideLoading();
        }
    },

    async loadStats() {
        try {
            const response = await fetch('/api/users/stats', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (response.ok) {
                const stats = await response.json();
                this.updateStats(stats);
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    },

    updateUsersTable(users) {
        const tbody = document.getElementById('usersTableBody');
        
        if (!users || users.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        <p class="mt-2 text-sm">No users found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = users.map(user => `
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full" src="${user.profile_image || '/assets/images/default-avatar.png'}" alt="${user.full_name}">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">${user.full_name}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">${user.email}</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">@${user.username}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${this.getRoleBadgeClass(user.role)}">
                        ${this.capitalizeFirst(user.role)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${this.getStatusBadgeClass(user.status)}">
                        ${this.capitalizeFirst(user.status)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${this.formatDate(user.created_at)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    ${user.last_activity ? this.formatDate(user.last_activity) : 'Never'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex items-center space-x-2">
                        <label class="flex items-center">
                            <input type="checkbox" value="${user.user_id}" class="user-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                        </label>
                        <button onclick="UserManager.editUser(${user.user_id})" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                        </button>
                        <button onclick="UserManager.toggleUserStatus(${user.user_id}, '${user.status}')" class="${user.status === 'blocked' ? 'text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300' : 'text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300'}">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${user.status === 'blocked' ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728'}"></path>
                            </svg>
                        </button>
                        <button onclick="UserManager.showDeleteModal(${user.user_id})" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        // Add event listeners to checkboxes
        document.querySelectorAll('.user-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                this.toggleUserSelection(e.target.value, e.target.checked);
            });
        });
    },

    updateStats(stats) {
        document.getElementById('totalUsers').textContent = this.formatNumber(stats.totalUsers);
        document.getElementById('activeUsers').textContent = this.formatNumber(stats.activeUsers);
        document.getElementById('blockedUsers').textContent = this.formatNumber(stats.blockedUsers);
        document.getElementById('moderatorsCount').textContent = this.formatNumber(stats.moderatorsCount);
    },

    updatePagination(pagination) {
        document.getElementById('startRecord').textContent = ((pagination.page - 1) * pagination.limit) + 1;
        document.getElementById('endRecord').textContent = Math.min(pagination.page * pagination.limit, pagination.total);
        document.getElementById('totalRecords').textContent = pagination.total;

        const paginationContainer = document.getElementById('pagination');
        paginationContainer.innerHTML = this.generatePaginationHTML(pagination);
    },

    generatePaginationHTML(pagination) {
        const pages = [];
        const totalPages = pagination.pages;
        const currentPage = pagination.page;

        // Previous button
        pages.push(`
            <button onclick="UserManager.goToPage(${currentPage - 1})" 
                    ${currentPage === 1 ? 'disabled' : ''}
                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 ${currentPage === 1 ? 'cursor-not-allowed opacity-50' : ''}">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `);

        // Page numbers
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                pages.push(`
                    <button onclick="UserManager.goToPage(${i})" 
                            class="relative inline-flex items-center px-4 py-2 border text-sm font-medium ${i === currentPage ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white dark:bg-gray-700 border-gray-300 text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600'}">
                        ${i}
                    </button>
                `);
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                pages.push(`
                    <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300">
                        ...
                    </span>
                `);
            }
        }

        // Next button
        pages.push(`
            <button onclick="UserManager.goToPage(${currentPage + 1})" 
                    ${currentPage === totalPages ? 'disabled' : ''}
                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white dark:bg-gray-700 text-sm font-medium text-gray-500 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 ${currentPage === totalPages ? 'cursor-not-allowed opacity-50' : ''}">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `);

        return pages.join('');
    },

    goToPage(page) {
        if (page < 1) return;
        this.currentPage = page;
        this.loadUsers();
    },

    toggleSelectAll(checked) {
        const checkboxes = document.querySelectorAll('.user-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = checked;
            this.toggleUserSelection(checkbox.value, checked);
        });
    },

    toggleUserSelection(userId, selected) {
        if (selected) {
            this.selectedUsers.add(userId);
        } else {
            this.selectedUsers.delete(userId);
        }
        this.updateBulkActions();
    },

    updateBulkActions() {
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');
        const selectAll = document.getElementById('selectAll');

        if (this.selectedUsers.size > 0) {
            bulkActions.classList.remove('hidden');
            selectedCount.textContent = `${this.selectedUsers.size} selected`;
        } else {
            bulkActions.classList.add('hidden');
            selectedCount.textContent = '0 selected';
        }

        // Update select all checkbox
        const checkboxes = document.querySelectorAll('.user-checkbox');
        const allChecked = checkboxes.length > 0 && Array.from(checkboxes).every(cb => cb.checked);
        selectAll.checked = allChecked;
        selectAll.indeterminate = this.selectedUsers.size > 0 && !allChecked;
    },

    async bulkAction(action) {
        if (this.selectedUsers.size === 0) return;

        const confirmMessage = {
            block: 'Are you sure you want to block the selected users?',
            unblock: 'Are you sure you want to unblock the selected users?',
            delete: 'Are you sure you want to delete the selected users? This action cannot be undone.'
        };

        if (!confirm(confirmMessage[action])) return;

        try {
            this.showLoading();
            
            const response = await fetch('/api/users/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    action: action,
                    userIds: Array.from(this.selectedUsers)
                })
            });

            if (!response.ok) {
                throw new Error('Failed to perform bulk action');
            }

            this.selectedUsers.clear();
            this.updateBulkActions();
            this.loadUsers();
            this.loadStats();
            this.showSuccess(`Successfully ${action}ed ${this.selectedUsers.size} users`);
            
        } catch (error) {
            console.error('Error performing bulk action:', error);
            this.showError('Failed to perform bulk action');
        } finally {
            this.hideLoading();
        }
    },

    showAddUserModal() {
        document.getElementById('modalTitle').textContent = 'Add User';
        document.getElementById('userForm').reset();
        document.getElementById('userId').value = '';
        document.getElementById('password').required = true;
        document.getElementById('userModal').classList.remove('hidden');
    },

    async editUser(userId) {
        try {
            this.showLoading();
            
            const response = await fetch(`/api/users/${userId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to load user data');
            }

            const user = await response.json();
            
            document.getElementById('modalTitle').textContent = 'Edit User';
            document.getElementById('userId').value = user.user_id;
            document.getElementById('fullName').value = user.full_name;
            document.getElementById('username').value = user.username;
            document.getElementById('email').value = user.email;
            document.getElementById('role').value = user.role;
            document.getElementById('status').value = user.status;
            document.getElementById('password').required = false;
            
            document.getElementById('userModal').classList.remove('hidden');
            
        } catch (error) {
            console.error('Error loading user:', error);
            this.showError('Failed to load user data');
        } finally {
            this.hideLoading();
        }
    },

    closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
    },

    async saveUser() {
        try {
            this.showLoading();
            
            const formData = new FormData(document.getElementById('userForm'));
            const userData = Object.fromEntries(formData.entries());
            
            const url = userData.userId ? `/api/users/${userData.userId}` : '/api/users';
            const method = userData.userId ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(userData)
            });

            if (!response.ok) {
                throw new Error('Failed to save user');
            }

            this.closeUserModal();
            this.loadUsers();
            this.loadStats();
            this.showSuccess(userData.userId ? 'User updated successfully' : 'User created successfully');
            
        } catch (error) {
            console.error('Error saving user:', error);
            this.showError('Failed to save user');
        } finally {
            this.hideLoading();
        }
    },

    async toggleUserStatus(userId, currentStatus) {
        const newStatus = currentStatus === 'blocked' ? 'active' : 'blocked';
        const action = currentStatus === 'blocked' ? 'unblock' : 'block';
        
        if (!confirm(`Are you sure you want to ${action} this user?`)) return;

        try {
            this.showLoading();
            
            const response = await fetch(`/api/users/${userId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ status: newStatus })
            });

            if (!response.ok) {
                throw new Error('Failed to update user status');
            }

            this.loadUsers();
            this.loadStats();
            this.showSuccess(`User ${action}ed successfully`);
            
        } catch (error) {
            console.error('Error updating user status:', error);
            this.showError('Failed to update user status');
        } finally {
            this.hideLoading();
        }
    },

    showDeleteModal(userId) {
        this.userToDelete = userId;
        document.getElementById('deleteModal').classList.remove('hidden');
    },

    closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
        this.userToDelete = null;
    },

    async confirmDelete() {
        if (!this.userToDelete) return;

        try {
            this.showLoading();
            
            const response = await fetch(`/api/users/${this.userToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to delete user');
            }

            this.closeDeleteModal();
            this.loadUsers();
            this.loadStats();
            this.showSuccess('User deleted successfully');
            
        } catch (error) {
            console.error('Error deleting user:', error);
            this.showError('Failed to delete user');
        } finally {
            this.hideLoading();
        }
    },

    async exportUsers() {
        try {
            this.showLoading();
            
            const params = new URLSearchParams({
                search: this.filters.search,
                status: this.filters.status,
                role: this.filters.role,
                format: 'csv'
            });

            const response = await fetch(`/api/users/export?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            if (!response.ok) {
                throw new Error('Failed to export users');
            }

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `users-export-${new Date().toISOString().split('T')[0]}.csv`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
        } catch (error) {
            console.error('Error exporting users:', error);
            this.showError('Failed to export users');
        } finally {
            this.hideLoading();
        }
    },

    getRoleBadgeClass(role) {
        const classes = {
            admin: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            moderator: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
            user: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200'
        };
        return classes[role] || classes.user;
    },

    getStatusBadgeClass(status) {
        const classes = {
            active: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            blocked: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
        };
        return classes[status] || classes.pending;
    },

    formatDate(dateString) {
        if (!dateString) return 'Never';
        const date = new Date(dateString);
        return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
    },

    formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M';
        } else if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K';
        }
        return num.toString();
    },

    capitalizeFirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    },

    showLoading() {
        document.getElementById('loadingOverlay').classList.remove('hidden');
    },

    hideLoading() {
        document.getElementById('loadingOverlay').classList.add('hidden');
    },

    showSuccess(message) {
        // You can implement a toast notification system here
        alert(message);
    },

    showError(message) {
        // You can implement a toast notification system here
        alert('Error: ' + message);
    },

    debounce(func, wait) {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(func, wait);
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    UserManager.init();
}); 