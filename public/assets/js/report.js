document.addEventListener('DOMContentLoaded', function() {
    const reportForm = document.getElementById('reportForm');
    
    reportForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(reportForm);
        
        try {
            const response = await fetch('/api/reports', {
                method: 'POST',
                body: formData
            });
            
            if (!response.ok) {
                throw new Error('Failed to submit report');
            }
            
            const result = await response.json();
            
            // Show success message
            showNotification('Report submitted successfully', 'success');
            
            // Reset form
            reportForm.reset();
            
        } catch (error) {
            console.error('Error submitting report:', error);
            showNotification('Failed to submit report. Please try again.', 'error');
        }
    });
    
    // File size validation
    const evidenceInput = reportForm.querySelector('input[name="evidence"]');
    evidenceInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                showNotification('File size must be less than 10MB', 'error');
                e.target.value = '';
            }
        }
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