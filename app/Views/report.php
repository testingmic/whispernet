<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Report Content</h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">Help us maintain a safe and respectful community</p>
        </div>

        <!-- Report Form -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6">
                <form id="reportForm" class="space-y-6">
                    <!-- Content Type Selection -->
                    <div class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            What are you reporting?
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="contentType" value="post" class="sr-only" required>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3">
                                        <div class="w-3 h-3 rounded-full bg-blue-500 hidden"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium text-gray-900 dark:text-white">Post</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">Report a post</span>
                                    </div>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="contentType" value="comment" class="sr-only" required>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3">
                                        <div class="w-3 h-3 rounded-full bg-blue-500 hidden"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium text-gray-900 dark:text-white">Comment</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">Report a comment</span>
                                    </div>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="contentType" value="user" class="sr-only" required>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3">
                                        <div class="w-3 h-3 rounded-full bg-blue-500 hidden"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium text-gray-900 dark:text-white">User</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">Report a user</span>
                                    </div>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="contentType" value="other" class="sr-only" required>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3">
                                        <div class="w-3 h-3 rounded-full bg-blue-500 hidden"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium text-gray-900 dark:text-white">Other</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">Report something else</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Content ID -->
                    <div>
                        <label for="contentId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Content ID
                        </label>
                        <div class="mt-1">
                            <input type="text" name="contentId" id="contentId" required
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 
                                dark:text-white transition-colors duration-200"
                                placeholder="Enter the ID of the content you're reporting">
                        </div>
                    </div>

                    <!-- Reason Selection -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Reason for Report
                        </label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="reason" value="spam" class="sr-only" required>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3">
                                        <div class="w-3 h-3 rounded-full bg-blue-500 hidden"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium text-gray-900 dark:text-white">Spam</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">Unwanted commercial content</span>
                                    </div>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="reason" value="harassment" class="sr-only" required>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3">
                                        <div class="w-3 h-3 rounded-full bg-blue-500 hidden"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium text-gray-900 dark:text-white">Harassment</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">Bullying or abusive behavior</span>
                                    </div>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="reason" value="inappropriate" class="sr-only" required>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3">
                                        <div class="w-3 h-3 rounded-full bg-blue-500 hidden"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium text-gray-900 dark:text-white">Inappropriate Content</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">NSFW or offensive material</span>
                                    </div>
                                </div>
                            </label>
                            <label class="relative flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                <input type="radio" name="reason" value="other" class="sr-only" required>
                                <div class="flex items-center">
                                    <div class="w-5 h-5 border-2 rounded-full flex items-center justify-center mr-3">
                                        <div class="w-3 h-3 rounded-full bg-blue-500 hidden"></div>
                                    </div>
                                    <div>
                                        <span class="block text-sm font-medium text-gray-900 dark:text-white">Other</span>
                                        <span class="block text-xs text-gray-500 dark:text-gray-400">Other violation</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Additional Details -->
                    <div>
                        <label for="details" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Additional Details
                        </label>
                        <div class="mt-1">
                            <textarea name="details" id="details" rows="4" required
                                class="block w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 
                                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 
                                dark:text-white transition-colors duration-200"
                                placeholder="Please provide any additional information that will help us understand the issue"></textarea>
                        </div>
                    </div>

                    <!-- Evidence Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Evidence (Optional)
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 
                            border-dashed rounded-lg hover:border-blue-500 dark:hover:border-blue-400 transition-colors duration-200">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" 
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 dark:text-gray-400">
                                    <label for="evidence" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-blue-600 dark:text-blue-400 hover:text-blue-500 focus-within:outline-none">
                                        <span>Upload files</span>
                                        <input id="evidence" name="evidence" type="file" class="sr-only" multiple>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    PNG, JPG, GIF up to 10MB
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg 
                            text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 
                            focus:ring-blue-500 transition-colors duration-200">
                            Submit Report
                            <svg class="ml-2 -mr-1 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden flex items-center justify-center">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-8 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 dark:bg-green-900">
                <svg class="h-6 w-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">Report Submitted</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Thank you for helping us maintain a safe community. We'll review your report shortly.
            </p>
            <div class="mt-6">
                <button type="button" onclick="closeSuccessModal()"
                    class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle radio button selection
    const radioGroups = document.querySelectorAll('input[type="radio"]');
    radioGroups.forEach(radio => {
        radio.addEventListener('change', function() {
            // Remove selected state from siblings
            const siblings = document.querySelectorAll(`input[name="${this.name}"]`);
            siblings.forEach(sib => {
                sib.parentElement.parentElement.querySelector('.w-3.h-3').classList.add('hidden');
            });
            // Add selected state to current
            this.parentElement.parentElement.querySelector('.w-3.h-3').classList.remove('hidden');
        });
    });

    // Handle file upload
    const dropZone = document.querySelector('.border-dashed');
    const fileInput = document.getElementById('evidence');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropZone.classList.add('border-blue-500');
    }

    function unhighlight(e) {
        dropZone.classList.remove('border-blue-500');
    }

    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        fileInput.files = files;
    }
});

function closeSuccessModal() {
    document.getElementById('successModal').classList.add('hidden');
}
</script>