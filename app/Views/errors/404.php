<div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4">
    <div class="max-w-md w-full text-center">
        <!-- 404 Illustration -->
        <div class="mb-8">
            <svg class="mx-auto h-48 w-48 text-blue-500 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>

        <!-- Error Message -->
        <h1 class="text-6xl font-bold text-gray-900 dark:text-white mb-4">404</h1>
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-300 mb-4">Oops! Page Not Found</h2>
        <p class="text-gray-600 dark:text-gray-400 mb-8">
            The page you're looking for seems to have vanished into thin air. 
            Maybe it's exploring the local area?
        </p>

        <!-- Action Buttons -->
        <div class="space-y-4">
            <a href="<?= $baseUrl ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Return Home
            </a>
            
            <button onclick="window.history.back()" class="inline-flex items-center px-6 py-3 border border-gray-300 dark:border-gray-600 text-base font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Go Back
            </button>
        </div>

        <!-- Additional Help -->
        <div class="mt-12 text-sm text-gray-500 dark:text-gray-400">
            <p>Need help? Try these options:</p>
            <div class="mt-4 space-x-4">
                <a href="<?= $baseUrl ?>/dashboard" class="hover:text-blue-500 dark:hover:text-blue-400">Browse Feed</a>
                <a href="<?= $baseUrl ?>/posts/create" class="hover:text-blue-500 dark:hover:text-blue-400">Create Post</a>
                <a href="<?= $baseUrl ?>/chat" class="hover:text-blue-500 dark:hover:text-blue-400">Open Chat</a>
            </div>
        </div>
    </div>
</div>