document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    const itemsPerPage = 10;
    let currentFilters = {
        status: 'all',
        type: 'all'
    };
    
    // Initialize
    loadReports();
    
    // Event Listeners
    document.getElementById('statusFilter').addEventListener('change', function(e) {
        currentFilters.status = e.target.value;
        currentPage = 1;
        loadReports();
    });
    
    document.getElementById('typeFilter').addEventListener('change', function(e) {
        currentFilters.type = e.target.value;
        currentPage = 1;
        loadReports();
    });
    
    document.getElementById('prevPage').addEventListener('click', function() {
        if (currentPage > 1) {
            currentPage--;
            loadReports();
        }
    });
    
    document.getElementById('nextPage').addEventListener('click', function() {
        currentPage++;
        loadReports();
    });
    
    // Load Reports
    async function loadReports() {
        try {
            const queryParams = new URLSearchParams({
                page: currentPage,
                limit: itemsPerPage,
                status: currentFilters.status,
                type: currentFilters.type
            });
            
            const response = await fetch(`/api/reports?${queryParams}`);
            
            if (!response.ok) {
                throw new Error('Failed to load reports');
            }
            
            const data = await response.json();
            renderReports(data.reports);
            updatePagination(data.total);
            
        } catch (error) {
            console.error('Error loading reports:', error);
            AppState.showNotification('Failed to load reports', 'error');
        }
    }
    
    // Render Reports
    function renderReports(reports) {
        const tbody = document.getElementById('reportsList');
        tbody.innerHTML = '';
        
        reports.forEach(report => {
            const tr = document.createElement('tr');
            tr.className = 'hover:bg-gray-50 dark:hover:bg-gray-700';
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">${report.id}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full ${
                        report.type === 'spam' ? 'bg-yellow-100 text-yellow-800' :
                        report.type === 'harassment' ? 'bg-red-100 text-red-800' :
                        report.type === 'inappropriate' ? 'bg-orange-100 text-orange-800' :
                        'bg-gray-100 text-gray-800'
                    }">${report.type}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${report.content_id}</td>
                <td class="px-6 py-4 whitespace-nowrap">${report.reporter}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 py-1 text-xs rounded-full ${
                        report.status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                        report.status === 'reviewing' ? 'bg-blue-100 text-blue-800' :
                        report.status === 'resolved' ? 'bg-green-100 text-green-800' :
                        'bg-gray-100 text-gray-800'
                    }">${report.status}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${new Date(report.created_at).toLocaleDateString()}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <button class="text-blue-600 hover:text-blue-900" onclick="viewReport(${report.id})">
                        View
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }
    
    // Update Pagination
    function updatePagination(total) {
        const showingCount = document.getElementById('showingCount');
        const totalCount = document.getElementById('totalCount');
        const prevButton = document.getElementById('prevPage');
        const nextButton = document.getElementById('nextPage');
        
        const start = (currentPage - 1) * itemsPerPage + 1;
        const end = Math.min(start + itemsPerPage - 1, total);
        
        showingCount.textContent = `${start}-${end}`;
        totalCount.textContent = total;
        
        prevButton.disabled = currentPage === 1;
        nextButton.disabled = end >= total;
    }
    
    // View Report Details
    window.viewReport = async function(reportId) {
        try {
            const response = await fetch(`/api/reports/${reportId}`);
            
            if (!response.ok) {
                throw new Error('Failed to load report details');
            }
            
            const report = await response.json();
            
            const modal = document.getElementById('reportDetailsModal');
            const details = document.getElementById('reportDetails');
            
            details.innerHTML = `
                <div class="space-y-4">
                    <div>
                        <h4 class="font-semibold">Report Type</h4>
                        <p>${report.type}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold">Description</h4>
                        <p>${report.description}</p>
                    </div>
                    <div>
                        <h4 class="font-semibold">Status</h4>
                        <select id="reportStatus" class="form-input">
                            <option value="pending" ${report.status === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="reviewing" ${report.status === 'reviewing' ? 'selected' : ''}>Reviewing</option>
                            <option value="resolved" ${report.status === 'resolved' ? 'selected' : ''}>Resolved</option>
                            <option value="dismissed" ${report.status === 'dismissed' ? 'selected' : ''}>Dismissed</option>
                        </select>
                    </div>
                    ${report.evidence ? `
                        <div>
                            <h4 class="font-semibold">Evidence</h4>
                            <img src="${report.evidence}" alt="Evidence" class="max-w-full h-auto">
                        </div>
                    ` : ''}
                </div>
            `;
            
            modal.classList.remove('hidden');
            
            // Update Status
            document.getElementById('updateStatus').addEventListener('click', async function() {
                const newStatus = document.getElementById('reportStatus').value;
                
                try {
                    const response = await fetch(`/api/reports/${reportId}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ status: newStatus })
                    });
                    
                    if (!response.ok) {
                        throw new Error('Failed to update status');
                    }
                    
                    AppState.showNotification('Status updated successfully', 'success');
                    loadReports();
                    modal.classList.add('hidden');
                    
                } catch (error) {
                    console.error('Error updating status:', error);
                    AppState.showNotification('Failed to update status', 'error');
                }
            });
            
        } catch (error) {
            console.error('Error loading report details:', error);
            AppState.showNotification('Failed to load report details', 'error');
        }
    };
    
    // Close Modal
    document.querySelectorAll('.modal-close').forEach(button => {
        button.addEventListener('click', function() {
            document.getElementById('reportDetailsModal').classList.add('hidden');
        });
    });
});