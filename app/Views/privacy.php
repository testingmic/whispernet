<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                Privacy Policy
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                We respect your privacy and are committed to protecting your personal data. This policy explains how we collect, use, and safeguard your information.
            </p>
            <div class="mt-6 flex items-center justify-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                <span>Last updated: <?= $privacyUpdatedDate ?></span>
                <span>•</span>
                <span>Version <?= $privacyVersion ?></span>
            </div>
        </div>

        <!-- Content Sections -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-8 space-y-8">
                
                <!-- Information We Collect -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        1. Information We Collect
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            We collect information you provide directly to us, including:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li><strong>Account Information:</strong> Name, email address, and profile information</li>
                            <li><strong>Location Data:</strong> Your approximate location to connect you with local community members</li>
                            <li><strong>Content:</strong> Posts, comments, and media you share on the platform</li>
                            <li><strong>Usage Data:</strong> How you interact with our service and features</li>
                            <li><strong>Device Information:</strong> Device type, operating system, and browser information</li>
                        </ul>
                    </div>
                </section>

                <!-- How We Use Your Information -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        2. How We Use Your Information
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            We use the information we collect to:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li>Provide, maintain, and improve our services</li>
                            <li>Connect you with other users in your local area</li>
                            <li>Personalize your experience and content</li>
                            <li>Send you important updates and notifications</li>
                            <li>Comply with legal obligations</li>
                        </ul>
                    </div>
                </section>

                <!-- Location Services -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        3. Location Services
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            Our service uses location data to:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li>Show you content from users within a <?= $postRadius ?>km radius</li>
                            <li>Connect you with your local community</li>
                            <li>Provide location-based features and services</li>
                        </ul>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mt-4">
                            You can control location permissions through your device settings. We only access your location when you grant permission.
                        </p>
                    </div>
                </section>

                <!-- Information Sharing -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
                            </svg>
                        </div>
                        4. Information Sharing
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            We do not sell, trade, or rent your personal information. We may share your information in the following circumstances:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li><strong>With Your Consent:</strong> When you explicitly agree to share information</li>
                            <li><strong>Service Providers:</strong> With trusted third-party services that help us operate our platform</li>
                            <li><strong>Legal Requirements:</strong> When required by law or to protect rights and safety</li>
                            <li><strong>Community Content:</strong> Your posts and public content are visible to other users in your area</li>
                        </ul>
                    </div>
                </section>

                <!-- Data Security -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        5. Data Security
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            We implement appropriate security measures to protect your personal information:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li>Encryption of data in transit and at rest</li>
                            <li>Regular security assessments and updates</li>
                            <li>Access controls and authentication measures</li>
                            <li>Secure data storage and backup procedures</li>
                        </ul>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mt-4">
                            However, no method of transmission over the internet is 100% secure, and we cannot guarantee absolute security.
                        </p>
                    </div>
                </section>

                <!-- Your Rights -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-pink-100 dark:bg-pink-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        6. Your Rights
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            You have the right to:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li><strong>Correction:</strong> Update or correct your information</li>
                            <li><strong>Deletion:</strong> Request deletion of your account and data</li>
                            <li><strong>Withdrawal:</strong> Withdraw consent at any time</li>
                        </ul>
                    </div>
                </section>

                <!-- Data Retention -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        7. Data Retention
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            We retain your personal information for as long as your account is active or as needed to provide services. 
                            When you delete your account, we will delete your personal data, except where we need to retain it for legal, security, or business purposes.
                        </p>
                    </div>
                </section>

                <!-- Cookies and Tracking -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                            </svg>
                        </div>
                        8. Cookies and Tracking
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mb-4">
                            We use cookies and similar technologies to:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 dark:text-gray-300 space-y-2 ml-4">
                            <li>Remember your preferences and settings</li>
                            <li>Analyze how you use our service</li>
                            <li>Improve our platform and user experience</li>
                            <li>Provide personalized content and features</li>
                        </ul>
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed mt-4">
                            You can control cookie settings through your browser preferences.
                        </p>
                    </div>
                </section>

                <!-- Children's Privacy -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-teal-100 dark:bg-teal-900/30 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        9. Children's Privacy
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            Our service is not intended for children under 18 years of age. We do not knowingly collect personal information 
                            from children under 18. If you are a parent or guardian and believe your child has provided us with personal information, 
                            please contact us immediately.
                        </p>
                    </div>
                </section>

                <!-- Changes to This Policy -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        10. Changes to This Policy
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy 
                            on this page and updating the "Last updated" date. We encourage you to review this policy periodically.
                        </p>
                    </div>
                </section>

                <!-- Contact Us -->
                <section>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <div class="w-8 h-8 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        11. Contact Us
                    </h2>
                    <div class="prose prose-gray dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                            If you have any questions about this Privacy Policy or our data practices, please contact us through the app or at our support email.
                        </p>
                    </div>
                </section>

            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                By using <?= $appName ?>, you acknowledge that you have read and understood this Privacy Policy.
            </p>
        </div>
    </div>
</div> 