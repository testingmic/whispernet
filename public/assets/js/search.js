async function handleSearchFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const results = document.getElementById('searchResults');
    const formData = new FormData(form);
    results.innerHTML = '<div class="p-6 text-center text-gray-500 dark:text-gray-400">Searching...</div>';

    try {
        const response = await fetch('/search', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await response.json();

        if (data.results && data.results.length > 0) {
            results.innerHTML = data.results.map(post => `
                <div class="card p-4">
                    <div class="flex items-center mb-2">
                        <div class="avatar-gradient-blue flex items-center justify-center rounded-full w-10 h-10 font-bold text-base mr-3">
                            ${(post.username || 'AN').substring(0,2).toUpperCase()}
                        </div>
                        <div>
                            <span class="font-semibold text-blue">${post.username || 'Anonymous User'}</span>
                            <span class="block text-xs text-gray-400">${post.created_at}</span>
                        </div>
                    </div>
                    <div class="text-gray-800 dark:text-gray-100 mb-2">${post.content}</div>
                    <div class="flex flex-wrap gap-2 mb-2">
                        ${(post.tags || []).map(tag => `<span class="px-2 py-1 rounded bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-medium">#${tag}</span>`).join('')}
                    </div>
                </div>
            `).join('');
        } else {
            results.innerHTML = '<div class="p-6 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-lg shadow">No results found for your search.</div>';
        }
    } catch (err) {
        results.innerHTML = '<div class="p-6 text-center text-red-500 bg-white dark:bg-gray-800 rounded-lg shadow">An error occurred. Please try again.</div>';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('searchForm');
    if (form) {
        form.addEventListener('submit', handleSearchFormSubmit);
    }
}); 