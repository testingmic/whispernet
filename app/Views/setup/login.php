<div class="flex items-center min-h-[calc(100vh-4rem)] justify-center bg-gray-50 dark:bg-gray-900 px-4 py-12 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-6">

        <!-- Prominent Signup Button -->
        <div class="bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 rounded-lg p-4 border border-green-200 dark:border-green-700/30 shadow-sm">
            <div class="text-center">
                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    New to <?= $appName ?>?
                </p>
                <a href="<?= $baseUrl; ?>/signup" 
                   class="w-full inline-flex justify-center items-center px-4 py-3 border border-transparent text-sm font-semibold rounded-lg text-white bg-gradient-to-r from-green-600 to-blue-600 hover:from-green-700 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transform transition-all duration-200 hover:scale-105 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    Create Your Account
                </a>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    It only takes a few seconds to get started
                </p>
            </div>
        </div>

        <!-- Divider -->
        <div class="relative">
            <div class="absolute inset-0 flex items-center">
                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
            </div>
            <div class="relative flex justify-center text-sm">
                <span class="px-4 bg-gray-50 dark:bg-gray-900 text-gray-1000 dark:text-gray-400 font-medium">or sign in</span>
            </div>
        </div>

        <!-- Login Form -->
        <form id="loginForm" class="space-y-6" action="<?= $baseUrl; ?>/login" method="POST">
            <div class="rounded-md shadow-sm -space-y-px">
                <!-- Email Field -->
                <div>
                    <label for="email" class="sr-only">Username or Email</label>
                    <input id="email" name="email" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-t-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm dark:bg-gray-800"
                        placeholder="Username or Email">
                </div>

                <!-- Password Field -->
                <div class="relative">
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="appearance-none rounded-none relative block w-full px-3 py-2 pr-10 border border-gray-300 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-b-md focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm dark:bg-gray-800"
                        placeholder="Password">
                    <button type="button" 
                        class="absolute inset-y-0 right-0 pr-3 flex items-center password-toggle-btn"
                        data-target="password">
                        <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 password-eye-closed" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <svg class="h-5 w-5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors duration-200 password-eye-open hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember_me" type="checkbox"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-800">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                        Remember me
                    </label>
                </div>

                <div class="text-sm">
                    <a href="<?= $baseUrl; ?>/forgot-password" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                        Forgot your password?
                    </a>
                </div>
            </div>

            <!-- Submit Button -->
            <div>
                <button id="loginButton" type="submit"
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-blue-500 group-hover:text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Sign in
                </button>
            </div>
        </form>

        <!-- Social Login -->
        <div>
            <div class="relative">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-gray-50 dark:bg-gray-900 text-gray-500 dark:text-gray-400"></span>
                </div>
            </div>

            <div class="mt-3 grid grid-cols-1">
                <a href="<?= $baseUrl; ?>/install" type="button"
                    class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-800 text-sm text-gray-900 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-gray-700"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                    </svg>
                    Click here to install
                </a>
            </div>
        </div>
    </div>
</div>