async function handleGroupFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.textContent = 'Creating...';

    try {
        const response = await fetch('/groups/create', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();
        if (data.success) {
            alert('Group created successfully!');
            document.getElementById('createGroupModal').classList.add('hidden');
            form.reset();
            window.location.reload();
        } else {
            alert(data.message || 'Failed to create group.');
        }
    } catch (err) {
        alert('An error occurred. Please try again.');
    } finally {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Create Group';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    // Modal open/close logic
    const openBtn = document.getElementById('openCreateGroupModal');
    const closeBtn = document.getElementById('closeCreateGroupModal');
    const cancelBtn = document.getElementById('cancelCreateGroup');
    const modal = document.getElementById('createGroupModal');
    const form = document.getElementById('createGroupForm');

    if (openBtn && modal) openBtn.onclick = () => modal.classList.remove('hidden');
    if (closeBtn && modal) closeBtn.onclick = () => modal.classList.add('hidden');
    if (cancelBtn && modal) cancelBtn.onclick = () => modal.classList.add('hidden');

    // Attach the dedicated handler
    if (form) {
        form.addEventListener('submit', handleGroupFormSubmit);
    }
}); 