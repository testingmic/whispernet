<?php
// set the favicon color
$favicon_color = $favicon_color ?? 'dashboard';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'Dashboard' ?> - <?= $appName ?></title>
  <meta name="description" content="<?= $pgDesc ?>">
  <link rel="manifest" href="<?= $baseUrl ?>/manifest.json">
  <meta name="theme-color" content="#2196F3">
  <script src="https://cdn.tailwindcss.com"></script>
  <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
  <link rel="apple-touch-icon" href="<?= $baseUrl ?>/assets/icons/Icon.192.png">
  <link rel="icon" type="image/png" href="<?= $baseUrl ?>/assets/icons/Icon.32.png">
  <meta name="google-site-verification" content="LvqtogwJfOW-lu5ScIs_fEdHOZcPHi-yPheUZEQUfSA" />
  <meta name="google-adsense-account" content="ca-pub-1861204960763512">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black">
  <meta name="apple-mobile-web-app-title" content="<?= $appName ?>">

  <link rel="apple-touch-icon" href="<?= $baseUrl ?>/assets/icons/Icon.192.png">
  <link rel="apple-touch-icon" sizes="152x152" href="<?= $baseUrl ?>/assets/icons/Icon.152.png">
  <link rel="apple-touch-icon" sizes="180x180" href="<?= $baseUrl ?>/assets/icons/Icon.192.png">
  <link rel="apple-touch-icon" sizes="167x167" href="<?= $baseUrl ?>/assets/icons/Icon.167.png">

  <!-- <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/tailwind.min.css"> -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    localStorage.setItem('baseUrl', '<?= $baseUrl ?>');
    const baseUrl = '<?= $baseUrl ?>',
      defaultSearchQuery = '<?= $searchQuery ?? '' ?>',
      loggedInUserId = <?= $userId ?? 0 ?>,
      footerArray = <?= json_encode($footerArray ?? []) ?>,
      userLoggedIn = <?= !empty($userLoggedIn) ? 'true' : 'false' ?>,
      websocketUrl = '<?= $websocketUrl ?>',
      postRadius = <?= $postRadius ?>,
      appName = '<?= $appName ?>',
      appLogo = '<?= $baseUrl ?>/assets/icons/Icon.192.png',
      loadingSkeleton = `<?= function_exists('loadingSkeleton') ? loadingSkeleton() : '' ?>`;
    <?php if (!empty($userToken)) { ?>
      localStorage.setItem('token', '<?= $userToken ?>');
    <?php } ?>
    <?php if (isset($_GET['auth_token'])) { ?>
      localStorage.setItem('token', '<?= $_GET['auth_token'] ?>');
    <?php } ?>
  </script>
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/app.css">
  <style>
    /* Ensure loader doesn't affect page layout */
    #pageLoader {
      position: fixed !important;
      top: 0 !important;
      left: 0 !important;
      right: 0 !important;
      bottom: 0 !important;
      z-index: 9999 !important;
      pointer-events: none;
    }

    /* Ensure page content flows normally */
    body {
      margin: 0 !important;
      padding: 0 !important;
    }

    #app {
      position: relative;
      z-index: 1;
    }

    /* Menu positioning and animations */
    .menu-container {
      position: relative;
    }

    #menuButton {
      transition: all 0.2s ease-in-out;
    }

    #menuButton.menu-open {
      background-color: rgba(59, 130, 246, 0.1);
      color: rgb(59, 130, 246);
    }

    /* Ensure menu appears above other content */
    #menuHelper {
      z-index: 9999;
    }

    /* Password toggle button styles */
    .password-toggle-btn {
      cursor: pointer;
      transition: all 0.2s ease-in-out;
      z-index: 10;
      pointer-events: auto;
    }

    .password-toggle-btn:hover {
      transform: scale(1.05);
    }

    .password-toggle-btn:focus {
      outline: none;
      ring: 2px;
      ring-color: rgb(59 130 246);
      ring-offset: 2px;
    }

    /* Ensure password input has proper padding for the toggle button */
    input[type="password"],
    input[type="text"] {
      padding-right: 2.5rem !important;
    }

    /* Ensure toggle button is always visible and clickable */
    .password-toggle-btn {
      position: absolute !important;
      right: 0.75rem !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      background: transparent !important;
      border: none !important;
      padding: 0.25rem !important;
      margin: 0 !important;
      z-index: 20 !important;
    }

    /* Ensure the button stays above the input field */
    .relative {
      position: relative !important;
    }

    /* Make sure the button is always interactive */
    .password-toggle-btn * {
      pointer-events: none;
    }

    .password-toggle-btn {
      pointer-events: auto !important;
    }

    /* Additional rules to ensure button is always visible */
    .password-toggle-btn {
      display: flex !important;
      visibility: visible !important;
      opacity: 1 !important;
      position: absolute !important;
      right: 0.75rem !important;
      top: 50% !important;
      transform: translateY(-50%) !important;
      z-index: 30 !important;
      background: transparent !important;
      border: none !important;
      padding: 0.25rem !important;
      margin: 0 !important;
      min-width: 1.5rem !important;
      min-height: 1.5rem !important;
      align-items: center !important;
      justify-content: center !important;
    }

    /* Ensure button stays visible even when input is focused */
    input:focus+.password-toggle-btn,
    input:focus~.password-toggle-btn {
      visibility: visible !important;
      opacity: 1 !important;
      pointer-events: auto !important;
    }

    /* Override any potential hiding from other styles */
    .password-toggle-btn.hidden,
    .password-toggle-btn[style*="display: none"],
    .password-toggle-btn[style*="visibility: hidden"] {
      display: flex !important;
      visibility: visible !important;
      opacity: 1 !important;
    }
  </style>
</head>

<body class="bg-gray-100 min-h-screen">

  <!-- Loading Spinner -->
  <div id="pageLoader" class="fixed inset-0 z-[9999] bg-white dark:bg-gray-900 flex items-center justify-center" style="position: fixed !important; top: 0 !important; left: 0 !important; right: 0 !important; bottom: 0 !important; z-index: 9999 !important;">
    <div class="text-center">
      <!-- Logo and App Name -->
      <div class="flex items-center justify-center mb-6">
        <img class="h-12 w-auto mr-3" src="<?= $baseUrl ?>/assets/images/logo.png" alt="<?= $appName ?>">
        <span class="text-2xl font-bold text-gray-900 dark:text-white"><?= $appName ?></span>
      </div>

      <!-- Single Spinner -->
      <div class="w-12 h-12 border-4 border-blue-200 dark:border-blue-800 rounded-full animate-spin border-t-blue-600 dark:border-t-blue-400 mx-auto"></div>

      <!-- Loading Text -->
      <p class="text-gray-600 dark:text-gray-400 mt-4 text-sm font-medium">Loading your experience...</p>

      <!-- Progress Bar -->
      <div class="w-48 h-1 bg-gray-200 dark:bg-gray-700 rounded-full mt-4 mx-auto overflow-hidden">
        <div id="progressBar" class="h-full bg-blue-600 dark:bg-blue-400 rounded-full transition-all duration-300" style="width: 0%"></div>
      </div>
    </div>
  </div>

  <!-- Post Creation Form -->
  <div id="postCreationForm" class="fixed inset-0 top-8 z-50 hidden">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm mt-8"></div>

    <!-- Form Container -->
    <div class="relative bg-white dark:bg-gray-800 shadow-2xl max-w-2xl mx-auto mt-8 overflow-hidden">
      <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-4 sm:px-6 py-3 sm:py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-2 sm:space-x-3">
            <div class="w-8 h-8 sm:w-10 sm:h-10 bg-white/20 rounded-full flex items-center justify-center">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
              </svg>
            </div>
            <div>
              <h2 class="text-lg sm:text-xl font-bold text-white">Create Post</h2>
              <p class="text-blue-100 text-xs sm:text-sm">Share with your community</p>
            </div>
          </div>
          <button type="button" onclick="return PostManager.closeCreateModal()" class="text-white hover:text-blue-100 transition-colors">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
      </div>
      <div class="p-2">


        <div class="max-w-2xl mx-auto">
          <div class="bg-white rounded-lg p-2">

            <form id="createPostFormUnique" class="space-y-2" onsubmit="return false;">
              <!-- Textarea Section -->
              <div class="space-y-2">
                <div class="relative">
                  <textarea
                    id="content"
                    name="content"
                    rows="4"
                    maxlength="300"
                    class="w-full outline-none font-medium text-xl px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none transition-all duration-200"
                    placeholder="Share your thoughts and experiences with users in a 30km radius..."></textarea>
                  <div class="absolute bottom-3 sm:bottom-4 right-3 sm:right-4 flex items-center space-x-1 sm:space-x-2">
                    <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span id="charCount" class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">0/300</span>
                  </div>
                </div>
              </div>

              <!-- Media Upload Section -->
              <div class="flex gap-2">

                <div class="flex items-center hidden">
                  <!-- Emoji Selector -->
                  <div class="flex items-center space-x-4">
                    <button type="button" id="emojiBtn" class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-lg sm:rounded-xl transition-all duration-200 hover:from-yellow-100 hover:to-orange-100 dark:hover:from-yellow-900/30 dark:hover:to-orange-900/30 border-2 border-dashed border-yellow-300 dark:border-yellow-700 hover:border-yellow-400 dark:hover:border-yellow-600">
                      <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm sm:text-base text-gray-700 dark:text-gray-300">Add Emoji</p>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Express yourself</p>
                      </div>
                    </button>
                  </div>
                </div>

                <!-- File Upload -->
                <div class="flex items-center space-x-4">
                  <label for="fileUpload" class="flex items-center space-x-2 sm:space-x-3 px-3 sm:px-4 py-2.5 sm:py-3 bg-gradient-to-r from-gray-50 to-blue-50 dark:from-gray-700 dark:to-gray-600 rounded-lg sm:rounded-xl cursor-pointer transition-all duration-200 hover:from-blue-50 hover:to-purple-50 dark:hover:from-gray-600 dark:hover:to-gray-500 border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-400 dark:hover:border-blue-500">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                      <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                      </svg>
                    </div>
                    <div>
                      <p class="font-medium text-sm sm:text-base text-gray-700 dark:text-gray-300">Add Media</p>
                      <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">Images &amp; Videos</p>
                    </div>
                    <input type="file" id="fileUpload" name="media[]" accept="image/*,video/*" multiple="" class="hidden">
                  </label>
                </div>

                <!-- Audio Recording -->
                <div class="flex items-center space-x-4 hidden">
                  <button type="button" id="audioRecordBtn" class="flex items-center justify-center w-12 h-12 bg-red-100 hover:bg-red-200 rounded-lg transition-colors duration-200">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                    </svg>
                  </button>
                  <span id="audioStatus" class="text-sm text-gray-600"></span>
                  <span id="audioTimer" class="text-sm text-red-600 hidden">00:00</span>
                  <button type="button" id="audioPauseBtn" class="hidden flex items-center justify-center w-8 h-8 bg-yellow-100 hover:bg-yellow-200 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                  </button>
                </div>
              </div>

              <div id="imagePreviewGrid" class="hidden grid grid-cols-4 gap-2 mt-4"></div>

              <!-- Emoji Picker (Hidden by default) -->
              <div id="emojiPicker" class="hidden bg-gray-50 rounded-lg p-4 mt-2 border">
                <div class="grid grid-cols-8 gap-2">
                  <?= imoji_list(); ?>
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
          <div class="flex items-center justify-between pt-2 border-t border-gray-200">
            <div class="flex items-center space-x-2 text-sm text-gray-500">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
              </svg>
              <span>Your current location.</span>
            </div>
            <button
              type="submit"
              id="submitBtn"
              class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
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

    </div>
  </div>

  <div id="app" class="flex flex-col">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-md fixed top-0 left-0 right-0 z-50">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <a href="<?= $baseUrl ?>" class="flex items-center">
                <img class="h-8 w-auto" src="<?= $baseUrl ?>/assets/images/logo.png" alt="<?= $appName ?>">
                <span class="text-xl font-bold ml-2"><?= $appName ?></span>
              </a>
            </div>
          </div>
          <?php if (!empty($userLoggedIn)) { ?>
            <div class="flex items-center relative" x-data="{ 
              open: false,
              init() {
                // Ensure menu starts closed
                this.open = false;
                
                // Close menu when clicking outside
                document.addEventListener('click', (e) => {
                  if (!this.$el.contains(e.target)) {
                    this.open = false;
                  }
                });
                
                // Close menu on escape key
                document.addEventListener('keydown', (e) => {
                  if (e.key === 'Escape' && this.open) {
                    this.open = false;
                  }
                });
              }
            }">
              <button id="menuButton"
                @click="open = !open"
                :class="{ 'menu-open': open }"
                class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>

              <!-- Menu Dropdown -->
              <div x-show="open"
                x-cloak
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95 translate-y-2"
                x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="transform opacity-0 scale-95 translate-y-2"
                id="menuHelper"
                class="absolute right-0 top-12 w-64 rounded-xl shadow-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 backdrop-blur-sm bg-opacity-95 dark:bg-opacity-95 z-50 overflow-hidden max-h-[80vh] overflow-y-auto">

                <!-- Header Section -->
                <div class="bg-gradient-to-r from-blue-500 to-purple-600 px-3 py-3">
                  <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center">
                      <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                      <p class="text-xs font-medium text-white">Welcome back</p>
                      <p class="text-xs text-blue-100 truncate"><?= session()->get('userData')['full_name'] ?? 'User' ?></p>
                    </div>
                  </div>
                </div>

                <!-- Menu Items -->
                <div class="py-1">
                  <!-- Profile Section -->
                  <div class="px-2">
                    <a href="<?= $baseUrl ?>/profile"
                      class="group flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:translate-x-1">
                      <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-2 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors duration-200">
                        <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm">Your Profile</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">View and edit your profile</p>
                      </div>
                    </a>
                  </div>

                  <!-- Settings -->
                  <div class="px-2">
                    <a href="<?= $baseUrl ?>/profile/edit"
                      class="group flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:translate-x-1">
                      <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-2 group-hover:bg-green-200 dark:group-hover:bg-green-800/50 transition-colors duration-200">
                        <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm">Settings</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Customize your experience</p>
                      </div>
                    </a>
                  </div>

                  <!-- Saved Items -->
                  <div class="px-2">
                    <a href="<?= $baseUrl ?>/profile/saved"
                      class="group flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-yellow-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:translate-x-1">
                      <div class="w-6 h-6 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mr-2 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800/50 transition-colors duration-200">
                        <svg class="w-3 h-3 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm">Saved Items</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Your bookmarked content</p>
                      </div>
                    </a>
                  </div>

                  <!-- Install Guide -->
                  <div class="px-2">
                    <a href="<?= $baseUrl ?>/install"
                      class="group flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-purple-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:translate-x-1">
                      <div class="w-6 h-6 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center mr-2 group-hover:bg-purple-200 dark:group-hover:bg-purple-800/50 transition-colors duration-200">
                        <svg class="w-3 h-3 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm">How to Install</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Add to home screen</p>
                      </div>
                    </a>
                  </div>

                  <!-- What's New -->
                  <div class="px-2">
                    <a href="<?= $baseUrl ?>/updates"
                      class="group flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:translate-x-1">
                      <div class="w-6 h-6 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center mr-2 group-hover:bg-orange-200 dark:group-hover:bg-orange-800/50 transition-colors duration-200">
                        <svg class="w-3 h-3 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm">What's New</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Latest updates</p>
                      </div>
                    </a>
                  </div>

                  <?php if (!empty($isAdminOrModerator)) { ?>
                  <!-- Admin Dashboard Section -->
                  <div class="border-t border-gray-200 dark:border-gray-700 mx-2 my-1"></div>
                  
                  <!-- Admin Dashboard Header -->
                  <div class="px-2 py-1">
                    <div class="flex items-center px-2 py-1">
                      <div class="w-6 h-6 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-2">
                        <svg class="w-3 h-3 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                      </div>
                      <div>
                        <p class="text-xs font-semibold text-red-600 dark:text-red-400 uppercase tracking-wide">Admin Panel</p>
                      </div>
                    </div>
                  </div>

                  <!-- Analytics -->
                  <div class="px-2">
                    <a href="<?= $baseUrl ?>/admin/analytics"
                      class="group flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:translate-x-1">
                      <div class="w-6 h-6 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center mr-2 group-hover:bg-blue-200 dark:group-hover:bg-blue-800/50 transition-colors duration-200">
                        <svg class="w-3 h-3 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm">Analytics</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Platform insights</p>
                      </div>
                    </a>
                  </div>

                  <!-- Reports -->
                  <div class="px-2">
                    <a href="<?= $baseUrl ?>/admin/reports"
                      class="group flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-yellow-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:translate-x-1">
                      <div class="w-6 h-6 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center mr-2 group-hover:bg-yellow-200 dark:group-hover:bg-yellow-800/50 transition-colors duration-200">
                        <svg class="w-3 h-3 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm">Reports</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">User reports</p>
                      </div>
                    </a>
                  </div>

                  <!-- Users Management -->
                  <div class="px-2">
                    <a href="<?= $baseUrl ?>/admin/users"
                      class="group flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:translate-x-1">
                      <div class="w-6 h-6 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center mr-2 group-hover:bg-green-200 dark:group-hover:bg-green-800/50 transition-colors duration-200">
                        <svg class="w-3 h-3 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm">Users</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Manage users</p>
                      </div>
                    </a>
                  </div>

                  <div class="border-t border-gray-200 dark:border-gray-700 mx-2 my-1"></div>
                  <?php } ?>

                  <!-- Help Center (Hidden) -->
                  <div class="px-2 py-1">
                    <a href="<?= $baseUrl ?>/support"
                      class="group flex items-center px-2 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700 rounded-lg transition-all duration-200 hover:translate-x-1">
                      <div class="w-6 h-6 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center mr-2 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-800/50 transition-colors duration-200">
                        <svg class="w-3 h-3 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm">Help Center</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Get support</p>
                      </div>
                    </a>
                  </div>

                  <!-- Divider -->
                  <div class="border-t border-gray-200 dark:border-gray-700 mx-2 my-1"></div>

                  <!-- Logout -->
                  <div class="px-2">
                    <button onclick="return AppState.logout()"
                      class="group w-full flex items-center px-2 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all duration-200 hover:translate-x-1">
                      <div class="w-6 h-6 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center mr-2 group-hover:bg-red-200 dark:group-hover:bg-red-800/50 transition-colors duration-200">
                        <svg class="w-3 h-3 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                      </div>
                      <div>
                        <p class="font-medium text-sm">Sign out</p>
                      </div>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </div>
  </div>
  </div>

  <main class="flex-grow pt-<?= $topMargin ?? 16 ?>">
    <?php if (!empty($userLoggedIn)) { ?>
      <!-- This section is now handled in the navigation above -->
    <?php } ?>