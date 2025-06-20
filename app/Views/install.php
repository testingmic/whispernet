<div class="bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 p-4">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden mt-4 mb-4">
        <div class="container mx-auto px-4 py-8">
            <h1 class="text-2xl font-bold">How to Install</h1>
            <p class="text-gray-600">
                Follow these steps to install the app on your Android device:
            </p>
        </div>

        <div class="container mx-auto px-4">
            <?php foreach (['Chrome' => 'Top Right Corner', 'Safari' => 'Bottom Center'] as $browser => $step) { ?>
                <div class="mb-6">
                    <h2 class="text-xl font-bold">🔹 <?= $browser; ?> Steps:</h2>
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            1. Tap the Share Icon in the <strong>"<?= $step; ?>"</strong>.
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            2. Scroll down and select <strong>"Add to Home Screen"</strong>.
                        </div>
                        <div class="flex items-center gap-2 mb-2">
                            3. A pop-up will appear — edit the name if you want and tap <strong>"Add"</strong>.
                        </div>
                        <div class="flex items-center gap-2">
                            4. Tap <strong>"Add"</strong>.
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div id="postMediaPreview" class="media-display-container mb-3">
                <div class="media-display-container space-y-4 mt-3">
                    <div class="media-grid grid grid-cols-3 gap-2">

                        <?php foreach(['Step1-Chrome','Step1-Safari','Step2-Chrome','Step2-Safari','Step3'] as $imageName) { ?>
                            <div class="media-item image-item" data-type="image" data-src="<?= $baseUrl; ?>/assets/install/<?= $imageName; ?>.jpeg" data-thumbnail="<?= $baseUrl; ?>/assets/install/<?= $imageName; ?>.jpeg">
                                <div class="relative group cursor-pointer overflow-hidden rounded-lg bg-gray-100 aspect-square">
                                    <img src="<?= $baseUrl; ?>/assets/install/<?= $imageName; ?>.jpeg" alt="Sample Image" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-20 transition-all duration-300 flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                        </svg>
                                    </div>
                                    <div class="absolute top-2 right-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                        <?= $imageName; ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- Enhanced Full View Modal -->
<div id="fullViewModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="absolute inset-0 bg-black bg-opacity-90 backdrop-blur-sm"></div>
    
    <div class="relative h-full flex items-center justify-center p-4">
        <!-- Close Button -->
        <button id="closeModal" class="absolute top-6 right-6 z-10 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-all duration-200 backdrop-blur-sm border border-white/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>

        <!-- Navigation Buttons -->
        <button id="prevBtn" class="absolute left-6 top-1/2 transform -translate-y-1/2 z-10 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-all duration-200 backdrop-blur-sm border border-white/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button id="nextBtn" class="absolute right-6 top-1/2 transform -translate-y-1/2 z-10 bg-white/20 hover:bg-white/30 text-white rounded-full p-3 transition-all duration-200 backdrop-blur-sm border border-white/20">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </button>

        <!-- Content Container -->
        <div id="modalContent" class="max-w-full max-h-full flex items-center justify-center">
            <!-- Content will be dynamically inserted here -->
        </div>

        <!-- Enhanced Loading Spinner -->
        <div id="loadingSpinner" class="absolute inset-0 flex items-center justify-center">
            <div class="relative">
                <div class="w-16 h-16 border-4 border-white/20 border-t-white rounded-full animate-spin"></div>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-8 h-8 bg-white/10 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Media Counter -->
        <div id="mediaCounter" class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-10 bg-black/50 text-white px-4 py-2 rounded-full backdrop-blur-sm text-sm font-medium">
            <span id="currentIndex">1</span> of <span id="totalCount">1</span>
        </div>
    </div>
</div>