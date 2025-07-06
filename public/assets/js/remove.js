const deleteAccountForm = document.getElementById('deleteAccountForm');
const confirmDeletion = document.getElementById('confirmDeletion');
const deleteAccountBtn = document.getElementById('deleteAccountBtn');
const finalConfirmationModal = document.getElementById('finalConfirmationModal');
const cancelFinalDelete = document.getElementById('cancelFinalDelete');
const confirmFinalDelete = document.getElementById('confirmFinalDelete');

// Enable/disable submit button based on checkbox
confirmDeletion.addEventListener('change', function() {
    deleteAccountBtn.disabled = !this.checked;
});

// Handle form submission
deleteAccountForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Show final confirmation modal
    finalConfirmationModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
});

// Hide modal
function hideModal() {
    finalConfirmationModal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    confirmFinalDelete.disabled = false;
    confirmFinalDelete.innerHTML = 'Yes, Delete My Account';
}

cancelFinalDelete.addEventListener('click', hideModal);

// Close modal when clicking outside
finalConfirmationModal.addEventListener('click', function(e) {
    if (e.target === finalConfirmationModal) {
        hideModal();
    }
});

// Handle final confirmation
confirmFinalDelete.addEventListener('click', function() {
    // Show loading state
    confirmFinalDelete.disabled = true;
    confirmFinalDelete.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Deleting...';

    // Get form data
    const formData = new FormData(deleteAccountForm);
    const data = {
        email: formData.get('email'),
        password: formData.get('password'),
        reason: formData.get('reason'),
        comments: formData.get('comments')
    };

    // Make API call to delete account
    fetch(`${baseUrl}/api/users/removeme`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin',
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status == 'success') {
            // Show success message
            AppState.showNotification('Account deleted successfully. You will be redirected shortly.', 'success');
            // Redirect to logout after a short delay
            AppState.logout();
        } else {
            AppState.showNotification('Error deleting account: ' + (data.message || 'Unknown error'), 'error');
            hideModal();
        }
    })
    .catch(error => {
        AppState.showNotification('Error deleting account. Please try again.', 'error');
        hideModal();
    });
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && !finalConfirmationModal.classList.contains('hidden')) {
        hideModal();
    }
});