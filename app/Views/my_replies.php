<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-4 pb-0">
    <div class="text-xl font-medium text-gray-900 dark:text-white mb-3">
        <a class="flex items-center space-x-2 text-base" href="<?= $baseUrl ?>/profile">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            <span class="bg-gradient-to-r from-red-500 via-green-500 to-blue-500 bg-clip-text text-transparent">Back</span>
        </a>
    </div>
</div>
<div class="bg-gray-50 dark:bg-gray-900 pt-2 px-4">
    <div class="max-w-3xl mx-auto px-4 py-6 sm:px-6 lg:px-8 bg-white dark:bg-gray-800 shadow rounded-lg">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Replies</h1>
    </div>
</div>
<script>
    var requestData = 'my_replies',
        requestLimit = 100;
</script>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-2 mb-2">
    <div id="feedContainer" class="scroll-sentinel">
        <?= loadingSkeleton(1, false); ?>
    </div>
</div>
<div class="px-4 py-8 sm:p-6">&nbsp;</div>