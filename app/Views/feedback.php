<div class="min-h-[calc(100vh-100px)] bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-2">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Feedback & Suggestions</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Help us improve <?= $appName ?> with your valuable input</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Feedback Form -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Share Your Thoughts</h2>
                        <p class="text-green-100 text-sm">Your feedback helps us make <?= $appName ?> better for everyone</p>
                    </div>
                </div>
            </div>

            <!-- Form Content -->
            <div class="p-6">
                <form id="feedbackForm" class="space-y-6">
                    <!-- Feedback Type -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">What type of feedback do you have?</label>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                            <label class="relative">
                                <input type="radio" name="feedback_type" value="suggestion" class="sr-only" required>
                                <div class="feedback-option cursor-pointer p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-green-400 dark:hover:border-green-500 transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">Suggestion</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">New feature ideas</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="feedback_type" value="bug_report" class="sr-only">
                                <div class="feedback-option cursor-pointer p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-red-400 dark:hover:border-red-500 transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">Bug Report</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Something not working</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="feedback_type" value="improvement" class="sr-only">
                                <div class="feedback-option cursor-pointer p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-yellow-400 dark:hover:border-yellow-500 transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">Improvement</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Enhance existing features</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="feedback_type" value="general" class="sr-only">
                                <div class="feedback-option cursor-pointer p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-purple-400 dark:hover:border-purple-500 transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">General</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Other feedback</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="feedback_type" value="praise" class="sr-only">
                                <div class="feedback-option cursor-pointer p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-green-400 dark:hover:border-green-500 transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">Praise</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">What you love</p>
                                        </div>
                                    </div>
                                </div>
                            </label>

                            <label class="relative">
                                <input type="radio" name="feedback_type" value="complaint" class="sr-only">
                                <div class="feedback-option cursor-pointer p-4 border-2 border-gray-200 dark:border-gray-600 rounded-xl hover:border-orange-400 dark:hover:border-orange-500 transition-all duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900 dark:text-white">Complaint</p>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Issues to address</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Priority Level -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">How important is this to you?</label>
                        <div class="flex items-center space-x-4">
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="priority" value="low" class="text-green-600 focus:ring-green-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Low</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="priority" value="medium" class="text-yellow-600 focus:ring-yellow-500" checked>
                                <span class="text-sm text-gray-700 dark:text-gray-300">Medium</span>
                            </label>
                            <label class="flex items-center space-x-2">
                                <input type="radio" name="priority" value="high" class="text-red-600 focus:ring-red-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">High</span>
                            </label>
                        </div>
                    </div>

                    <!-- Subject -->
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Subject</label>
                        <input type="text" id="subject" name="subject" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white transition-all duration-200"
                            placeholder="Brief summary of your feedback">
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Detailed Description</label>
                        <textarea id="description" name="description" rows="6" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none transition-all duration-200"
                            placeholder="Please provide detailed information about your feedback, including steps to reproduce if it's a bug report, or specific suggestions for improvements..."></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Be as detailed as possible to help us understand your feedback</span>
                            <span id="charCount" class="text-xs text-gray-500 dark:text-gray-400">0/2000</span>
                        </div>
                    </div>

                    <!-- Contact Preference -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Would you like us to follow up with you?</label>
                        <div class="space-y-2">
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="contact_preference" value="yes" class="text-green-600 focus:ring-green-500">
                                <span class="text-sm text-gray-700 dark:text-gray-300">Yes, I'd like to be contacted about updates</span>
                            </label>
                            <label class="flex items-center space-x-3">
                                <input type="radio" name="contact_preference" value="no" class="text-green-600 focus:ring-green-500" checked>
                                <span class="text-sm text-gray-700 dark:text-gray-300">No, just submit the feedback</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" onclick="history.back()" 
                            class="px-6 py-3 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all duration-200">
                            Cancel
                        </button>
                        <button type="submit" id="submitBtn"
                            class="px-8 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transform transition-all duration-200 hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span>Submit Feedback</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- What happens next -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">What happens next?</h3>
                </div>
                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start space-x-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></span>
                        <span>We review all feedback within 24-48 hours</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></span>
                        <span>Bug reports are prioritized and addressed quickly</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></span>
                        <span>Feature suggestions are evaluated for future updates</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></span>
                        <span>We may contact you for additional details</span>
                    </li>
                </ul>
            </div>

            <!-- Tips for good feedback -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Tips for great feedback</h3>
                </div>
                <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                    <li class="flex items-start space-x-2">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 flex-shrink-0"></span>
                        <span>Be specific about what you want to see changed</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 flex-shrink-0"></span>
                        <span>Include steps to reproduce for bug reports</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 flex-shrink-0"></span>
                        <span>Explain why the change would be beneficial</span>
                    </li>
                    <li class="flex items-start space-x-2">
                        <span class="w-2 h-2 bg-yellow-500 rounded-full mt-2 flex-shrink-0"></span>
                        <span>Include your device and browser information</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div id="additionalHeight" class="h-20"></div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('feedbackForm');
    const description = document.getElementById('description');
    const charCount = document.getElementById('charCount');
    const submitBtn = document.getElementById('submitBtn');
    const successMessage = document.getElementById('successMessage');
    const errorMessage = document.getElementById('errorMessage');
    const errorText = document.getElementById('errorText');

    // Character count for description
    description.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = `${count}/2000`;
        
        if (count > 1800) {
            charCount.classList.add('text-red-500');
        } else {
            charCount.classList.remove('text-red-500');
        }
    });

    // Feedback option selection styling
    const feedbackOptions = document.querySelectorAll('.feedback-option');
    const radioInputs = document.querySelectorAll('input[name="feedback_type"]');

    radioInputs.forEach(input => {
        input.addEventListener('change', function() {
            feedbackOptions.forEach(option => {
                option.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
            });
            
            if (this.checked) {
                const option = this.closest('label').querySelector('.feedback-option');
                option.classList.add('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
            }
        });
    });

    // Form submission
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="flex items-center space-x-2">
                <svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                <span>Submitting...</span>
            </span>
        `;

        try {
            const formData = new FormData(form);
            const data = {
                feedback_type: formData.get('feedback_type'),
                priority: formData.get('priority'),
                subject: formData.get('subject'),
                description: formData.get('description'),
                contact_preference: formData.get('contact_preference'),
                user_id: loggedInUserId,
                token: localStorage.getItem('token')
            };

            const response = await fetch('/api/feedback/submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.status === 'success') {
                form.reset();
                feedbackOptions.forEach(option => {
                    option.classList.remove('border-green-500', 'bg-green-50', 'dark:bg-green-900/20');
                });
                AppState.showNotification("We've received your submission and will review it carefully.", 'success');
            } else {
                AppState.showNotification(result.data || 'An error occurred while submitting your feedback.', 'error');
            }
        } catch (error) {
            AppState.showNotification('Network error. Please check your connection and try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = `
                <span class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <span>Submit Feedback</span>
                </span>
            `;
        }
    });
});
</script> 