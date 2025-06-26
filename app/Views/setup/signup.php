<div class="min-h-[calc(100vh-65px)] bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex items-center justify-center">
    
    <!-- Signup Section -->
    <div id="signup-section" class="w-full min-h-[80vh] flex items-center justify-center py-8">
        <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[70vh]">
                <!-- Left Side - Description -->
                <div class="relative overflow-hidden lg:text-left">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10"></div>
                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6">
                        <div class="text-center">
                            <!-- App Logo/Icon -->
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-green-500 to-blue-600 rounded-2xl shadow-lg mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            
                            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                                Join <span class="bg-gradient-to-r from-green-600 to-blue-600 bg-clip-text text-transparent"><?= $appName ?></span>
                            </h1>
                            
                            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed mb-8">
                                Start your journey with secure, private messaging. Connect with your community, share your thoughts, 
                                and build meaningful conversations in a safe environment.
                            </p>

                            <!-- Key Benefits -->
                            <div class="grid md:grid-cols-2 gap-6 max-w-4xl mx-auto mb-12">                                
                                <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-700/30 hover:scale-105 transition-all duration-300">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4 mx-auto">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tags </h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">Add tags to your posts to make them more discoverable</p>
                                </div>
                                
                                <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-700/30 hover:scale-105 transition-all duration-300">
                                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-4 mx-auto">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Instant Setup</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">Get started in under 30 seconds</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Signup Form -->
                <div class="max-w-md mx-auto lg:mx-0 w-full">
                    <div class="bg-white dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl p-8 shadow-xl border border-white/20 dark:border-gray-700/30">
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Create Account</h3>
                            <p class="text-gray-600 dark:text-gray-400">Join our community today</p>
                        </div>

                        <!-- Signup Form -->
                        <form id="signupForm" class="space-y-6" action="<?= $baseUrl; ?>/register" method="POST">
                            <!-- Full Name Field -->
                            <div class="relative">
                                <label for="full_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-green-600 dark:text-green-400">●</span> Full Name
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input id="full_name" name="full_name" type="text" required 
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200"
                                        placeholder="Enter your full name">
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="relative">
                                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-blue-600 dark:text-blue-400">●</span> Email Address
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input id="email" name="email" type="email" autocomplete="email" required 
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200"
                                        placeholder="Enter your email address">
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="relative">
                                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-purple-600 dark:text-purple-400">●</span> Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                    </div>
                                    <input id="password" name="password" type="password" autocomplete="new-password" required 
                                        class="w-full pl-10 pr-12 py-3 border-2 border-gray-200 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200"
                                        placeholder="Create a strong password">
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

                            <!-- Confirm Password Field -->
                            <div class="relative">
                                <label for="password_confirm" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-orange-600 dark:text-orange-400">●</span> Confirm Password
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                    <input id="password_confirm" name="password_confirm" type="password" autocomplete="new-password" required 
                                        class="w-full pl-10 pr-12 py-3 border-2 border-gray-200 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200"
                                        placeholder="Confirm your password">
                                    <button type="button" 
                                        class="absolute inset-y-0 right-0 pr-3 flex items-center password-toggle-btn"
                                        data-target="password_confirm">
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

                            <!-- Terms and Privacy -->
                            <div class="flex items-start space-x-3">
                                <input id="terms" name="terms" type="checkbox" required
                                    class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 mt-1">
                                <label for="terms" class="block text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                    I agree to the 
                                    <a href="<?= $baseUrl; ?>/terms" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">Terms of Service</a>
                                    and
                                    <a href="<?= $baseUrl; ?>/privacy" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 transition-colors duration-200">Privacy Policy</a>
                                </label>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" 
                                    class="w-full flex justify-center items-center py-4 px-6 border border-transparent text-base font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 via-blue-600 to-blue-700 hover:from-green-700 hover:via-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                    </svg>
                                    Create Your Account
                                </button>
                            </div>
                        </form>

                        <!-- Divider -->
                        <div class="relative my-8">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white/70 dark:bg-gray-800/70 text-gray-500 dark:text-gray-400 font-medium">Already have an account?</span>
                            </div>
                        </div>

                        <!-- Sign In Link -->
                        <a href="<?= $baseUrl; ?>/login" 
                           class="w-full inline-flex justify-center items-center px-4 py-3 border-2 border-gray-200 dark:border-gray-600 text-sm font-semibold rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                            </svg>
                            Sign In to Your Account
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>