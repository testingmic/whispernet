<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Terms of Service
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                Please read these terms carefully before using our service. By using <?= $appName ?>, you agree to be bound by these terms.
            </p>
            <div class="mt-6 flex items-center justify-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                <span>Last updated: <?= $privacyUpdatedDate ?></span>
                <span>•</span>
                <span>Version 1.0</span>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-8 space-y-8">
                
                <!-- Acceptance of Terms -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        1. Acceptance of Terms
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            By accessing and using <?= $appName ?>, you accept and agree to be bound by the terms and provision of this agreement. 
                            If you do not agree to abide by the above, please do not use this service.
                        </p>
                    </div>
                </section>

                <!-- Description of Service -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        2. Description of Service
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            <?= $appName ?> is a local community platform that allows users to:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li>Share posts and content with users in their local area</li>
                            <li>Connect with community members within a <?= $postRadius ?>km radius</li>
                            <li>Engage in discussions and interactions</li>
                            <li>Access community information and updates</li>
                        </ul>
                    </div>
                </section>

                <!-- User Accounts -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        3. User Accounts
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            When you create an account with us, you must provide accurate and complete information. You are responsible for:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li>Maintaining the security of your account and password</li>
                            <li>All activities that occur under your account</li>
                            <li>Notifying us immediately of any unauthorized use</li>
                            <li>Ensuring your account information remains accurate and up-to-date</li>
                        </ul>
                    </div>
                </section>

                <!-- Acceptable Use -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        4. Acceptable Use Policy
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            You agree not to use the service to:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li>Post content that is illegal, harmful, threatening, abusive, or defamatory</li>
                            <li>Post nudity, violence, or other content that is not appropriate for a family-friendly platform</li>
                            <li>Harass, bully, or intimidate other users</li>
                            <li>Share personal information of others without consent</li>
                            <li>Post spam, advertisements, or commercial content without permission</li>
                            <li>Impersonate another person or entity</li>
                            <li>Violate any applicable laws or regulations</li>
                        </ul>
                    </div>
                </section>

                <!-- Content Ownership -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        5. Content Ownership
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            You retain ownership of content you post, but grant us a license to use, display, and distribute your content on our platform. 
                            You represent that you have the right to grant this license.
                        </p>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            We reserve the right to remove content that violates our terms or policies.
                        </p>
                    </div>
                </section>

                <!-- Privacy -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        6. Privacy
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            Your privacy is important to us. Please review our 
                            <a href="<?= $baseUrl ?>/privacy" class="text-blue-600 dark:text-blue-400 hover:underline">Privacy Policy</a>, 
                            which also governs your use of the service.
                        </p>
                    </div>
                </section>

                <!-- Termination -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </div>
                        7. Termination
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            We may terminate or suspend your account immediately, without prior notice, for conduct that we believe violates these Terms of Service or is harmful to other users, us, or third parties.
                        </p>
                    </div>
                </section>

                <!-- Contact Information -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        8. Contact Information
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            If you have any questions about these Terms of Service, please contact us through the app or at our support email.
                        </p>
                    </div>
                </section>

            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                By using <?= $appName ?>, you acknowledge that you have read, understood, and agree to be bound by these Terms of Service.
            </p>
        </div>
    </div>
</div> 