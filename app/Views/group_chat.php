<?php
$group = $group ?? [];
$messages = $messages ?? [];
$participants = $participants ?? [];
?>

<div class="h-screen flex flex-col bg-gray-50 dark:bg-gray-900 max-h-[calc(100vh-5rem)]">
    <!-- Group Header -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="px-4 py-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="<?= $baseUrl ?>/chat/groups" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium text-lg">
                        <?= substr($group['title'] ?? 'G', 0, 1) ?>
                    </div>
                    <div>
                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white"><?= htmlspecialchars($group['title'] ?? '') ?></h1>
                        <p class="text-sm text-gray-500 dark:text-gray-400"><?= count($participants) ?> members</p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <button id="showParticipants" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </button>
                    <button id="showGroupInfo" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="flex-1 flex overflow-hidden">
        <!-- Chat Messages -->
        <div class="flex-1 flex flex-col">
            <!-- Messages Container -->
            <div id="messagesContainer" class="flex-1 overflow-y-auto p-4 space-y-4 max-h-[calc(100vh-20rem)]">
                <?php if (empty($messages)): ?>
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No Messages Yet</h3>
                            <p class="text-gray-500 dark:text-gray-400">Start the conversation by sending a message!</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $message): ?>
                        <div class="flex <?= $message['user_id'] === session('user_id') ? 'justify-end' : 'justify-start' ?>">
                            <div class="flex items-end space-x-2 max-w-[70%]">
                                <?php if ($message['user_id'] !== session('user_id')): ?>
                                    <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-xs font-medium text-gray-600 dark:text-gray-300">
                                        <?= substr($message['username'] ?? 'U', 0, 1) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="flex flex-col <?= $message['user_id'] === session('user_id') ? 'items-end' : 'items-start' ?>">
                                    <?php if ($message['user_id'] !== session('user_id')): ?>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mb-1"><?= htmlspecialchars($message['username'] ?? '') ?></span>
                                    <?php endif; ?>
                                    <div class="rounded-2xl px-4 py-2 <?= $message['user_id'] === session('user_id') ? 'bg-blue-500 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' ?>">
                                        <?php if ($message['type'] === 'text'): ?>
                                            <p class="text-sm"><?= htmlspecialchars($message['content']) ?></p>
                                        <?php elseif ($message['type'] === 'image'): ?>
                                            <img src="<?= htmlspecialchars($message['content']) ?>" alt="Shared image" class="max-w-sm rounded-lg">
                                        <?php elseif ($message['type'] === 'audio'): ?>
                                            <audio controls class="w-full">
                                                <source src="<?= htmlspecialchars($message['content']) ?>" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio>
                                        <?php endif; ?>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        <?= date('g:i A', strtotime($message['created_at'])) ?>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Message Input -->
            <div class="border-t border-gray-200 fixed bottom-0 left-0 right-0 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                <form id="messageForm" class="flex items-center space-x-4">
                    <button type="button" id="attachButton" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13" />
                        </svg>
                    </button>
                    <div class="flex-1">
                        <input type="text" id="messageInput" placeholder="Type a message..." 
                               class="w-full px-4 py-2 rounded-full border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white">
                    </div>
                    <button type="button" id="recordButton" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z" />
                        </svg>
                    </button>
                    <button type="submit" class="btn-primary px-4 py-2 rounded-full">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Participants Sidebar -->
        <div id="participantsSidebar" class="w-80 border-l border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hidden">
            <div class="p-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Participants</h2>
                <div class="space-y-3">
                    <?php foreach ($participants as $participant): ?>
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-sm font-medium text-gray-600 dark:text-gray-300">
                                <?= substr($participant['name'] ?? 'U', 0, 1) ?>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white"><?= htmlspecialchars($participant['name'] ?? '') ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?= htmlspecialchars($participant['email'] ?? '') ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Group Info Modal -->
<div id="groupInfoModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 max-w-md w-full mx-4">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Group Information</h3>
            <button type="button" id="closeGroupInfo" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Group Name</label>
                <p class="text-gray-900 dark:text-white"><?= htmlspecialchars($group['title'] ?? '') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                <p class="text-gray-900 dark:text-white"><?= htmlspecialchars($group['description'] ?? 'No description available') ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Created</label>
                <p class="text-gray-900 dark:text-white"><?= date('F j, Y', strtotime($group['created_at'] ?? 'now')) ?></p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elements
    const messagesContainer = document.getElementById('messagesContainer');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const attachButton = document.getElementById('attachButton');
    const recordButton = document.getElementById('recordButton');
    const showParticipants = document.getElementById('showParticipants');
    const participantsSidebar = document.getElementById('participantsSidebar');
    const showGroupInfo = document.getElementById('showGroupInfo');
    const groupInfoModal = document.getElementById('groupInfoModal');
    const closeGroupInfo = document.getElementById('closeGroupInfo');

    // Scroll to bottom of messages
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Toggle participants sidebar
    showParticipants.addEventListener('click', () => {
        participantsSidebar.classList.toggle('hidden');
    });

    // Toggle group info modal
    showGroupInfo.addEventListener('click', () => {
        groupInfoModal.classList.remove('hidden');
    });

    closeGroupInfo.addEventListener('click', () => {
        groupInfoModal.classList.add('hidden');
    });

    // Handle message form submission
    messageForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = messageInput.value.trim();
        if (!message) return;

        try {
            const response = await fetch('<?= $baseUrl; ?>/api/chat/messages/<?= $group['id'] ?? '' ?>/send', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    content: message,
                    type: 'text'
                })
            });

            if (response.ok) {
                messageInput.value = '';
            }
        } catch (error) {
            console.error('Error sending message:', error);
        }
    });

    // Handle file attachment
    attachButton.addEventListener('click', () => {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.onchange = async (e) => {
            const file = e.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('type', 'image');

            try {
                const response = await fetch('<?= $baseUrl; ?>/api/chat/messages/<?= $group['id'] ?? '' ?>/image', {
                    method: 'POST',
                    body: formData
                });

                if (response.ok) {
                    location.reload();
                }
            } catch (error) {
                console.error('Error uploading file:', error);
            }
        };
        input.click();
    });

    // Handle audio recording
    let mediaRecorder;
    let audioChunks = [];

    recordButton.addEventListener('click', async () => {
        if (mediaRecorder && mediaRecorder.state === 'recording') {
            mediaRecorder.stop();
            recordButton.classList.remove('text-red-500');
            return;
        }

        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            mediaRecorder = new MediaRecorder(stream);
            audioChunks = [];

            mediaRecorder.ondataavailable = (e) => {
                audioChunks.push(e.data);
            };

            mediaRecorder.onstop = async () => {
                const audioBlob = new Blob(audioChunks, { type: 'audio/mpeg' });
                const formData = new FormData();
                formData.append('file', audioBlob, 'recording.mp3');
                formData.append('type', 'audio');

                try {
                    const response = await fetch('<?= $baseUrl; ?>/api/chat/messages/<?= $group['id'] ?? '' ?>/audio', {
                        method: 'POST',
                        body: formData
                    });

                    if (response.ok) {
                        location.reload();
                    }
                } catch (error) {
                    console.error('Error uploading audio:', error);
                }
            };

            mediaRecorder.start();
            recordButton.classList.add('text-red-500');
        } catch (error) {
            console.error('Error accessing microphone:', error);
        }
    });
});
</script> 