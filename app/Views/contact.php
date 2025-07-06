<div class="min-h-[calc(100vh-65px)] bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 flex items-center justify-center">
    
    <!-- Contact Section -->
    <div id="contact-section" class="w-full min-h-[80vh] flex items-center justify-center py-4">
        <div class="w-full max-w-6xl mx-auto px-4 sm:px-6">
            <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[60vh]">
                <!-- Left Side - Information -->
                <div class="relative overflow-hidden lg:text-left">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-600/10 to-purple-600/10"></div>
                    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-8">
                        <div class="text-center">
                            <!-- App Logo/Icon -->
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl shadow-lg mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            
                            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                                Get in Touch
                            </h1>
                            
                            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto leading-relaxed mb-8">
                                We're here to help! Whether you have a question, need support, or want to share feedback, 
                                our team is ready to assist you with anything related to <?= $appName ?>.
                            </p>

                            <!-- Contact Methods -->
                            <div class="grid md:grid-cols-3 gap-4 max-w-4xl mx-auto mb-8">
                                <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-4 border border-white/20 dark:border-gray-700/30 hover:scale-105 transition-all duration-300">
                                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mb-4 mx-auto">
                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Email Support</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">support@<?= strtolower(str_replace(' ', '', $appName)) ?>.com</p>
                                </div>
                                
                                <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-4 border border-white/20 dark:border-gray-700/30 hover:scale-105 transition-all duration-300">
                                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mb-4 mx-auto">
                                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Quick Response</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">We reply within 24 hours</p>
                                </div>
                                
                                <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-4 border border-white/20 dark:border-gray-700/30 hover:scale-105 transition-all duration-300">
                                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mb-4 mx-auto">
                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Expert Help</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">Professional support team</p>
                                </div>
                            </div>

                            <!-- FAQ Section -->
                            <div class="bg-white dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-white/20 dark:border-gray-700/30 max-w-2xl mx-auto hover:scale-105 transition-all duration-300">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Frequently Asked Questions</h3>
                                <div class="space-y-3 text-left">
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">How do I reset my password?</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Use the "Forgot Password" link on the login page.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Is my data secure?</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Yes, we use industry-standard encryption and security measures.</p>
                                        </div>
                                    </div>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-2 h-2 bg-purple-500 rounded-full mt-2"></div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-white">Can I delete my account?</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">Yes, you can delete your account from your profile settings.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Contact Form -->
                <div class="max-w-md mx-auto lg:mx-0 w-full">
                    <div class="bg-white dark:bg-gray-800/70 backdrop-blur-sm rounded-2xl p-8 shadow-xl border border-white/20 dark:border-gray-700/30">
                        <div class="text-center mb-8">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Contact Us</h3>
                            <p class="text-gray-600 dark:text-gray-400">Send us a message and we'll get back to you</p>
                        </div>

                        <!-- Contact Form -->
                        <form id="contactForm" class="space-y-6" action="<?= $baseUrl; ?>/contact" method="POST">
                            <!-- Name Field -->
                            <div class="relative">
                                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-blue-600 dark:text-blue-400">●</span> Name
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <input id="name" name="name" type="text" required 
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200"
                                        placeholder="Enter name">
                                </div>
                            </div>

                            <!-- Email Field -->
                            <div class="relative">
                                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-green-600 dark:text-green-400">●</span> Email Address
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <input id="email" name="email" type="email" autocomplete="email" required 
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200"
                                        placeholder="Enter your email address">
                                </div>
                            </div>

                            <!-- Subject Field -->
                            <div class="relative">
                                <label for="subject" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-purple-600 dark:text-purple-400">●</span> Subject
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                        </svg>
                                    </div>
                                    <select id="subject" name="subject" required 
                                        class="w-full pl-10 pr-4 py-3 border-2 border-gray-200 dark:border-gray-600 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200">
                                        <option value="">Select a subject</option>
                                        <?php foreach(contact_form_selection('contact') as $key => $value) { ?>
                                            <option value="<?= $key; ?>"><?= $value; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>

                            <!-- Message Field -->
                            <div class="relative">
                                <label for="message" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <span class="text-orange-600 dark:text-orange-400">●</span> Message
                                </label>
                                <div class="relative">
                                    <textarea id="message" name="message" rows="4" required 
                                        class="w-full px-4 py-3 border-2 border-gray-200 dark:border-gray-600 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-orange-500 sm:text-sm dark:bg-gray-700/50 transition-all duration-200 resize-none"
                                        placeholder="Tell us how we can help you..."></textarea>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div>
                                <button type="submit" 
                                    class="w-full flex justify-center items-center py-4 px-6 border border-transparent text-base font-semibold rounded-xl text-white bg-gradient-to-r from-blue-600 via-purple-600 to-purple-700 hover:from-blue-700 hover:via-purple-700 hover:to-purple-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    Send Message
                                </button>
                            </div>
                        </form>

                        <!-- Divider -->
                        <div class="relative my-8">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white/70 dark:bg-gray-800/70 text-gray-500 dark:text-gray-400 font-medium">Need immediate help?</span>
                            </div>
                        </div>

                        <!-- Quick Actions -->
                        <div class="space-y-3">
                            <a href="<?= $baseUrl; ?>/login" 
                               class="w-full inline-flex justify-center items-center px-4 py-3 border-2 border-gray-200 dark:border-gray-600 text-sm font-semibold rounded-xl text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 hover:border-blue-300 dark:hover:border-blue-600">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                </svg>
                                Back to Sign In
                            </a>
                            
                            <a href="<?= $baseUrl; ?>/signup" 
                               class="w-full inline-flex justify-center items-center px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl shadow-sm bg-gradient-to-r from-green-50 to-blue-50 dark:from-green-900/20 dark:to-blue-900/20 text-sm text-gray-700 dark:text-gray-300 hover:from-green-100 hover:to-blue-100 dark:hover:from-green-800/30 dark:hover:to-blue-800/30 transition-all duration-200">
                                <svg class="w-5 h-5 mr-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                Create Account
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 