<div class="min-h-[calc(100vh-4rem)] flex items-center justify-center bg-gray-50 dark:bg-gray-900 px-4 py-12 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="flex items-center justify-center">
            <img src="<?= $baseUrl; ?>/assets/images/logo.png" alt="<?= $appName ?>" class="h-16 w-auto">
        </div>
        <!-- Header -->
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-gray-900 dark:text-white">
                Reset your password
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Enter your email address and we'll send you a link to reset your password.
            </p>
        </div>

        <!-- Reset Form -->
        <form id="forgotPasswordForm" class="mt-8 space-y-6" action="<?= $baseUrl; ?>/forgot-password" method="POST">
            <div>
                <label for="email" class="sr-only">Email address</label>
                <input id="email" name="email" type="email" autocomplete="email" required 
                    class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm dark:bg-gray-800"
                    placeholder="Enter your email address">
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit" 
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Send reset link
                </button>
            </div>
        </form>

        <!-- Back to Login -->
        <div class="text-center">
            <a href="<?= $baseUrl; ?>/login" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to login
                </span>
            </a>
        </div>

        <!-- Additional Help -->
        <div class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
            <p>Didn't receive the email?</p>
            <p class="mt-2">
                Check your spam folder or 
                <button type="button" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                    try another email address
                </button>
            </p>
        </div>
    </div>
</div> 