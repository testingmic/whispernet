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
            AppState.showNotification('Report submitted successfully', 'success');
            
            // Reset form
            reportForm.reset();
            
        } catch (error) {
            console.error('Error submitting report:', error);
            AppState.showNotification('Failed to submit report. Please try again.', 'error');
        }
    });
    
    // File size validation
    const evidenceInput = reportForm.querySelector('input[name="evidence"]');
    evidenceInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const maxSize = 10 * 1024 * 1024; // 10MB
            if (file.size > maxSize) {
                AppState.showNotification('File size must be less than 10MB', 'error');
                e.target.value = '';
            }
        }
    });
});