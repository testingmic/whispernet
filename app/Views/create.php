<div class="max-w-2xl mx-auto px-4 py-4">
    <div class="bg-white rounded-lg shadow-sm p-4">
        <h1 class="text-xl font-semibold text-gray-900 mb-6">Create New Post</h1>
        
        <form id="createPostFormUnique" class="space-y-6" onsubmit="return false;">
            <!-- Textarea Section -->
            <div class="space-y-2">
                <label for="content" class="block text-sm font-medium text-gray-700">What's on your mind?</label>
                <div class="relative">
                    <textarea 
                        id="content" 
                        name="content" 
                        rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-all duration-200"
                        placeholder="Share your thoughts, experiences, or ask a question..."
                    ></textarea>
                    <div class="absolute bottom-2 right-2 flex items-center space-x-2">
                        <!-- Character Counter -->
                        <span id="charCount" class="text-xs text-gray-400">0/1000</span>
                    </div>
                </div>
            </div>

            <!-- Media Upload Section -->
            <div class="space-y-4">
                <label class="block text-sm font-medium text-gray-700">Add Media</label>
                
                <!-- File Upload -->
                <div class="flex items-center space-x-4">
                    <label for="fileUpload" class="flex items-center justify-center w-12 h-12 bg-gray-100 hover:bg-gray-200 rounded-lg cursor-pointer transition-colors duration-200">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                        </svg>
                        <input type="file" id="fileUpload" name="media[]" accept="image/*,video/*" multiple class="hidden" />
                    </label>
                    <div class="flex-1">
                        <span class="text-sm text-gray-600">Upload images or video (max 4 files)</span>
                        <div class="text-xs text-gray-400 mt-1">Images: JPG, PNG, GIF (max 2MB) • Video: MP4, MOV (max 10MB)</div>
                    </div>
                </div>

                <!-- Image Preview Grid -->
                <div id="imagePreviewGrid" class="hidden grid grid-cols-4 gap-2 mt-4">
                    <!-- Preview slots will be dynamically added here -->
                </div>

                <!-- Audio Recording -->
                <div class="flex items-center space-x-4">
                    <button type="button" id="audioRecordBtn" class="flex items-center justify-center w-12 h-12 bg-red-100 hover:bg-red-200 rounded-lg transition-colors duration-200">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                        </svg>
                    </button>
                    <span id="audioStatus" class="text-sm text-gray-600">Record audio message</span>
                    <span id="audioTimer" class="text-sm text-red-600 hidden">00:00</span>
                </div>

                <!-- Emoji Selector -->
                <div class="flex items-center space-x-4">
                    <button type="button" id="emojiBtn" class="flex items-center justify-center w-12 h-12 bg-yellow-100 hover:bg-yellow-200 rounded-lg transition-colors duration-200">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                    <span class="text-sm text-gray-600">Add emoji</span>
                </div>

                <!-- Emoji Picker (Hidden by default) -->
                <div id="emojiPicker" class="hidden bg-gray-50 rounded-lg p-4 border">
                    <div class="grid grid-cols-8 gap-2">
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">😊</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">😂</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">❤️</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">👍</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">🎉</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">🔥</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">💯</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">✨</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">😍</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">🤔</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">😮</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">😢</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">😡</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">🤯</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">🥳</button>
                        <button type="button" class="emoji-btn text-2xl hover:bg-gray-200 rounded p-1 transition-colors">😴</button>
                    </div>
                </div>
            </div>

            <!-- Preview Section -->
            <div id="mediaPreview" class="hidden space-y-2">
                <label class="block text-sm font-medium text-gray-700">Preview</label>
                <div id="previewContainer" class="bg-gray-50 rounded-lg p-4 min-h-[100px] flex items-center justify-center">
                    <span class="text-gray-400">Media preview will appear here</span>
                </div>
            </div>

            <!-- Audio Preview -->
            <div id="audioPreview" class="hidden space-y-2">
                <label class="block text-sm font-medium text-gray-700">Audio Preview</label>
                <div class="bg-gray-50 rounded-lg p-4">
                    <audio id="audioPlayer" controls class="w-full"></audio>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="flex items-center space-x-2 text-sm text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span>Location will be added automatically</span>
                </div>
                <button 
                    type="submit" 
                    id="submitBtn"
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span class="flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        <span>Post</span>
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
const ImprovedPostCreationForm = {
    mediaRecorder: null,
    audioChunks: [],
    recordingTimer: null,
    recordingStartTime: null,
    uploadedFiles: [],

    init() {

        console.log('PostCreationForm initialized');
        this.form = document.getElementById('createPostFormUnique');
        this.textarea = document.getElementById('content');
        this.charCount = document.getElementById('charCount');
        this.fileUpload = document.getElementById('fileUpload');
        this.audioRecordBtn = document.getElementById('audioRecordBtn');
        this.audioStatus = document.getElementById('audioStatus');
        this.audioTimer = document.getElementById('audioTimer');
        this.emojiBtn = document.getElementById('emojiBtn');
        this.emojiPicker = document.getElementById('emojiPicker');
        this.mediaPreview = document.getElementById('mediaPreview');
        this.previewContainer = document.getElementById('previewContainer');
        this.audioPreview = document.getElementById('audioPreview');
        this.audioPlayer = document.getElementById('audioPlayer');
        this.submitBtn = document.getElementById('submitBtn');
        this.MAX_RECORDING_TIME = 30; // 30 seconds
        this.MAX_IMAGE_SIZE = 2 * 1024 * 1024; // 2MB in bytes

        this.formSetup();
    },
    formSetup() {
        // Character counter
        this.textarea.addEventListener('input', function() {
            const length = this.value.length;
            charCount.textContent = `${length}/1000`;
            
            if (length > 1000) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });

        // File upload preview
        this.fileUpload.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            // Check if adding these files would exceed the limit
            if (ImprovedPostCreationForm.uploadedFiles?.length + files?.length > 4) {
                ImprovedPostCreationForm.showNotification('You can only upload up to 4 files. Please remove some files first.', 'error');
                return;
            }

            files.forEach((file, index) => {
                // Check file type
                if (!file.type.startsWith('image/') && !file.type.startsWith('video/')) {
                    ImprovedPostCreationForm.showNotification(`File "${file.name}" is not a valid image or video file.`, 'error');
                    return;
                }

                // Check file size based on type
                if (file.type.startsWith('image/') && file.size > ImprovedPostCreationForm.MAX_IMAGE_SIZE) {
                    ImprovedPostCreationForm.showNotification(`Image "${file.name}" is too large. Maximum size is 2MB.`, 'error');
                    return;
                }

                // Check file size for videos (10MB limit)
                if (file.type.startsWith('video/') && file.size > 10 * 1024 * 1024) {
                    ImprovedPostCreationForm.showNotification(`Video "${file.name}" is too large. Maximum size is 10MB.`, 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    const fileId = Date.now() + index;
                    ImprovedPostCreationForm.uploadedFiles.push({
                        id: fileId,
                        file: file,
                        preview: e.target.result
                    });
                    
                    ImprovedPostCreationForm.createFilePreview(fileId, file, e.target.result);
                    ImprovedPostCreationForm.updateUploadButton();
                };
                reader.readAsDataURL(file);
            });
        });

        // Emoji picker
        this.emojiBtn.addEventListener('click', function() {
            ImprovedPostCreationForm.emojiPicker.classList.toggle('hidden');
        });

        // Add emoji to textarea
        document.querySelectorAll('.emoji-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const emoji = this.textContent;
                const cursorPos = ImprovedPostCreationForm.textarea.selectionStart;
                const textBefore = ImprovedPostCreationForm.textarea.value.substring(0, cursorPos);
                const textAfter = ImprovedPostCreationForm.textarea.value.substring(cursorPos);
                ImprovedPostCreationForm.textarea.value = textBefore + emoji + textAfter;
                ImprovedPostCreationForm.textarea.focus();
                ImprovedPostCreationForm.textarea.setSelectionRange(cursorPos + emoji.length, cursorPos + emoji.length);
                
                // Trigger input event for character counter
                ImprovedPostCreationForm.textarea.dispatchEvent(new Event('input'));
            });
        });

        // Close emoji picker when clicking outside
        document.addEventListener('click', function(e) {
            if (!ImprovedPostCreationForm.emojiBtn.contains(e.target) && !ImprovedPostCreationForm.emojiPicker.contains(e.target)) {
                ImprovedPostCreationForm.emojiPicker.classList.add('hidden');
            }
        });

        // Add click event listener to submit button as additional safeguard
        submitBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Trigger form submission manually
            ImprovedPostCreationForm.form.dispatchEvent(new Event('submit', { bubbles: true, cancelable: true }));
            
            return false;
        });

        // Form submission
        this.form.addEventListener('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();
            
            // Validate content
            if (!ImprovedPostCreationForm.textarea.value.trim() && ImprovedPostCreationForm.uploadedFiles.length === 0) {
                ImprovedPostCreationForm.showNotification('Please add some content or upload media before posting.', 'error');
                return false;
            }
            
            // Stop recording if it's currently active
            if (ImprovedPostCreationForm.mediaRecorder && ImprovedPostCreationForm.mediaRecorder.state === 'recording') {
                ImprovedPostCreationForm.stopRecording();
                // Wait a moment for the recording to finish processing
                setTimeout(() => {
                    ImprovedPostCreationForm.processFormSubmission();
                }, 500);
            } else {
                ImprovedPostCreationForm.processFormSubmission();
            }
            
            return false;
        });

        // Audio recording
        this.audioRecordBtn.addEventListener('click', function() {
            if (!ImprovedPostCreationForm.mediaRecorder || ImprovedPostCreationForm.mediaRecorder.state === 'inactive') {
                ImprovedPostCreationForm.startRecording();
            } else {
                ImprovedPostCreationForm.stopRecording();
            }
        });
    },

    createFilePreview(fileId, file, previewUrl) {
        const previewGrid = document.getElementById('imagePreviewGrid');
        previewGrid.classList.remove('hidden');

        const previewContainer = document.createElement('div');
        previewContainer.className = 'relative group';
        previewContainer.id = `preview-${fileId}`;

        const isVideo = file.type.startsWith('video/');
        
        previewContainer.innerHTML = `
            <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden w-20 h-20">
                ${isVideo ? 
                    `<video src="${previewUrl}" class="w-full h-full object-cover" controls></video>` :
                    `<img src="${previewUrl}" class="w-full h-full object-cover" alt="Preview">`
                }
            </div>
            <button type="button" class="remove-file absolute -top-1 -right-1 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs hover:bg-red-600 transition-colors opacity-0 group-hover:opacity-100" data-file-id="${fileId}">
                ×
            </button>
            <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 truncate">
                ${file.name}
            </div>
        `;

        previewGrid.appendChild(previewContainer);

        // Add remove functionality
        const removeBtn = previewContainer.querySelector('.remove-file');
        removeBtn.addEventListener('click', function() {
            removeFile(fileId);
        });
    },

    removeFile(fileId) {
        // Remove from uploadedFiles array
        this.uploadedFiles = this.uploadedFiles.filter(file => file.id !== fileId);
        
        // Remove from DOM
        const previewElement = document.getElementById(`preview-${fileId}`);
        if (previewElement) {
            previewElement.remove();
        }

        // Hide grid if no files
        if (this.uploadedFiles.length === 0) {
            document.getElementById('imagePreviewGrid').classList.add('hidden');
        }

        this.updateUploadButton();
    },

    updateUploadButton() {
        const uploadLabel = fileUpload.parentElement;
        const remainingSlots = 4 - this.uploadedFiles.length;
        
        if (remainingSlots === 0) {
            uploadLabel.classList.add('opacity-50', 'cursor-not-allowed');
            uploadLabel.classList.remove('hover:bg-gray-200');
        } else {
            uploadLabel.classList.remove('opacity-50', 'cursor-not-allowed');
            uploadLabel.classList.add('hover:bg-gray-200');
        }
    },

    async startRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];

            mediaRecorder.ondataavailable = function(event) {
                audioChunks.push(event.data);
            };

            mediaRecorder.onstop = function() {
                const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                const audioUrl = URL.createObjectURL(audioBlob);
                audioPlayer.src = audioUrl;
                audioPreview.classList.remove('hidden');
                
                // Stop all tracks
                stream.getTracks().forEach(track => track.stop());
            };

            mediaRecorder.start();
            audioRecordBtn.classList.add('bg-red-500', 'text-white');
            audioRecordBtn.classList.remove('bg-red-100', 'text-red-600');
            audioStatus.textContent = 'Recording...';
            audioTimer.classList.remove('hidden');
            
            recordingStartTime = Date.now();
            recordingTimer = setInterval(this.updateTimer, 1000);
        } catch (error) {
            console.error('Error accessing microphone:', error);
            this.showNotification('Unable to access microphone. Please check permissions.', 'error');
        }
    },

    stopRecording() {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
            audioRecordBtn.classList.remove('bg-red-500', 'text-white');
            audioRecordBtn.classList.add('bg-red-100', 'text-red-600');
            audioStatus.textContent = 'Record audio message';
            audioTimer.classList.add('hidden');
            clearInterval(recordingTimer);
        }
    },

    updateTimer() {
        const elapsed = Math.floor((Date.now() - recordingStartTime) / 1000);
        const minutes = Math.floor(elapsed / 60);
        const seconds = elapsed % 60;
        audioTimer.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
        
        // Check if recording time limit reached
        if (elapsed >= this.MAX_RECORDING_TIME) {
            this.showNotification('Recording stopped automatically (30 second limit reached).', 'info');
            this.stopRecording();
        }
    },
    
    processFormSubmission() {
        // Disable submit button
        ImprovedPostCreationForm.submitBtn.disabled = true;
        ImprovedPostCreationForm.submitBtn.innerHTML = `
            <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Posting...</span>
        `;

        // Prepare form data
        const formData = new FormData();
        formData.append('content', ImprovedPostCreationForm.textarea.value.trim());
        
        // Add uploaded files
        ImprovedPostCreationForm.uploadedFiles.forEach((fileData, index) => {
            formData.append(`media[${index}]`, fileData.file);
        });

        // Add audio if recorded
        if (ImprovedPostCreationForm.audioPlayer.src && ImprovedPostCreationForm.audioPlayer.src !== '') {
            // Convert audio blob to file
            fetch(ImprovedPostCreationForm.audioPlayer.src)
                .then(res => res.blob())
                .then(blob => {
                    const audioFile = new File([blob], 'audio-message.wav', { type: 'audio/wav' });
                    formData.append('audio', audioFile);
                    ImprovedPostCreationForm.submitFormData(formData);
                });
        } else {
            ImprovedPostCreationForm.submitFormData(formData);
        }
    },

    submitFormData(formData) {
        // Send AJAX request to API endpoint
        fetch('/api/posts/create', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Show success message
                showNotification('Post created successfully!', 'success');
                resetForm();
                
                // Optionally redirect to the new post or refresh the feed
                if (data.data && data.data.post_id) {
                    // Redirect to the new post
                    window.location.href = `/posts/view/${data.data.post_id}`;
                } else {
                    // Redirect to home page
                    window.location.href = '/';
                }
            } else {
                // Show error message
                showNotification(data.message || 'Failed to create post. Please try again.', 'error');
                ImprovedPostCreationForm.submitBtn.disabled = false;
                ImprovedPostCreationForm.submitBtn.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    <span>Post</span>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while creating the post. Please try again.', 'error');
            ImprovedPostCreationForm.submitBtn.disabled = false;
            ImprovedPostCreationForm.submitBtn.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                <span>Post</span>
            `;
        });
    },

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm transform transition-all duration-300 translate-x-full`;
        
        // Set background color based on type
        if (type === 'success') {
            notification.classList.add('bg-green-500', 'text-white');
        } else if (type === 'error') {
            notification.classList.add('bg-red-500', 'text-white');
        } else {
            notification.classList.add('bg-blue-500', 'text-white');
        }
        
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button type="button" class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    },

    resetForm() {
        // Reset form fields
        form.reset();
        textarea.value = '';
        charCount.textContent = '0/1000';
        
        // Clear uploaded files
        this.uploadedFiles = [];
        document.getElementById('imagePreviewGrid').innerHTML = '';
        document.getElementById('imagePreviewGrid').classList.add('hidden');
        
        // Clear audio
        audioPlayer.src = '';
        audioPreview.classList.add('hidden');
        
        // Reset upload button
        this.updateUploadButton();
        
        // Re-enable submit button
        submitBtn.disabled = false;
        submitBtn.innerHTML = `
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
            </svg>
            <span>Post</span>
        `;
    }

};
document.addEventListener('DOMContentLoaded', function() {
    ImprovedPostCreationForm.init();
});
</script>