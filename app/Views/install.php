<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 p-4">
    <div class="max-w-4xl mx-auto">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-2">Install <?= $appName ?></h1>
            <p class="text-lg text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
                Transform your browser into a powerful app with just a few taps. Follow the steps below to add <?= $appName ?> to your home screen.
            </p>
        </div>

        <!-- Main Content Card -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            
            <!-- Installation Steps -->
            <div class="p-4">
                <div class="grid md:grid-cols-2 gap-8">
                    <?php 
                    $browsers = [
                        'Chrome' => [
                            'icon' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
                            'color' => 'bg-blue-500',
                            'step' => 'Top Right Corner'
                        ],
                        'Safari' => [
                            'icon' => 'M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z',
                            'color' => 'bg-green-500',
                            'step' => 'Bottom Center'
                        ]
                    ];
                    
                    foreach ($browsers as $browser => $config) { ?>
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-600">
                            <!-- Browser Header -->
                            <div class="flex items-center mb-6">
                                <div class="w-12 h-12 <?= $config['color']; ?> rounded-xl flex items-center justify-center mr-4">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="<?= $config['icon']; ?>"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900 dark:text-white"><?= $browser; ?></h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Browser Instructions</p>
                                </div>
                            </div>

                            <!-- Steps -->
                            <div class="space-y-4">
                                <?php 
                                $steps = [
                                    "Tap the Share Icon in the <strong>\"{$config['step']}\"</strong> of the browser",
                                    "Scroll down and select <strong>\"Add to Home Screen\"</strong>",
                                    "A pop-up will appear — edit the name if you want and tap <strong>\"Add\"</strong>",
                                    "Tap <strong>\"Add\"</strong> to complete installation"
                                ];
                                
                                foreach ($steps as $index => $step) { ?>
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0 w-8 h-8 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                            <span class="text-sm font-semibold text-blue-600 dark:text-blue-400"><?= $index + 1; ?></span>
                                        </div>
                                        <div class="flex-1 pt-1">
                                            <p class="text-gray-700 dark:text-gray-300 leading-relaxed"><?= $step; ?></p>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>

                            <!-- Success Indicator -->
                            <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-800">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-green-800 dark:text-green-200">
                                        App will appear on your home screen
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Visual Guide Section -->
            <div class="bg-gray-50 dark:bg-gray-900/50 px-5 py-6 border-t border-gray-200 dark:border-gray-700">
                <div class="text-center mb-6">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Visual Guide</h3>
                    <p class="text-gray-600 dark:text-gray-400">Tap on any image below to see it in full size</p>
                </div>

                <div id="postMediaPreview" class="media-display-container">
                    <div class="media-display-container">
                        <div class="media-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                            <?php 
                            $stepImages = [
                                'Step1-Chrome' => 'Chrome Step 1',
                                'Step1-Safari' => 'Safari Step 1', 
                                'Step2-Chrome' => 'Chrome Step 2',
                                'Step2-Safari' => 'Safari Step 2',
                                'Step3' => 'Final Step'
                            ];
                            
                            foreach($stepImages as $imageName => $label) { ?>
                                <div class="media-item image-item group" data-type="image" data-src="<?= $baseUrl; ?>/assets/install/<?= $imageName; ?>.jpeg" data-thumbnail="<?= $baseUrl; ?>/assets/install/<?= $imageName; ?>.jpeg">
                                    <div class="relative cursor-pointer overflow-hidden rounded-xl bg-gray-100 dark:bg-gray-700 aspect-square border-2 border-gray-200 dark:border-gray-600 hover:border-blue-300 dark:hover:border-blue-500 transition-all duration-300">
                                        <img src="<?= $baseUrl; ?>/assets/install/<?= $imageName; ?>.jpeg" 
                                             alt="<?= $label; ?>" 
                                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105">
                                        
                                        <!-- Overlay -->
                                        <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-300 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                            </svg>
                                        </div>
                                        
                                        <!-- Label -->
                                        <div class="absolute bottom-2 left-2 right-2 bg-black bg-opacity-75 text-white text-xs px-2 py-1 rounded-lg text-center font-medium">
                                            <?= $label; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Benefits Section -->
            <div class="p-8 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20">
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-4 text-center">Why Install as App?</h3>
                <div class="grid md:grid-cols-3 gap-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Faster Access</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">One tap to open</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Offline Ready</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Works without internet</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900 dark:text-white">Native Feel</h4>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Like a real app</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8">
            <p class="text-gray-500 dark:text-gray-400 text-sm">
                Need help? Contact our support team for assistance.
            </p>
        </div>
    </div>
</div>

<!-- Enhanced Full View Modal -->
<?= full_view_modal() ?>