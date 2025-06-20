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
  <meta name="description" content="Connect with your local community anonymously">
  <link rel="manifest" href="<?= $baseUrl ?>/manifest.json">
  <meta name="theme-color" content="#2196F3">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="apple-touch-icon" href="<?= $baseUrl ?>/assets/icons/Icon.192.png">
  <link rel="icon" type="image/png" href="<?= $baseUrl ?>/assets/icons/Icon.32.png">

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
  <script src="<?= $baseUrl ?>/assets/js/groups.js?v=<?= $version ?>"></script>
  <script src="<?= $baseUrl ?>/assets/js/search.js?v=<?= $version ?>"></script>
  <script>
    localStorage.setItem('baseUrl', '<?= $baseUrl ?>');
    const baseUrl = '<?= $baseUrl ?>',
      loggedInUserId = <?= $userId ?? 0 ?>,
      userLoggedIn = <?= !empty($userLoggedIn) ? 'true' : 'false' ?>,
      websocketUrl = '<?= $websocketUrl ?>',
      loadingSkeleton = `<?= function_exists('loadingSkeleton') ? loadingSkeleton() : '' ?>`;
    <?php if (!empty($userToken)) { ?>
      localStorage.setItem('token', '<?= $userToken ?>');
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
  <div id="postCreationForm" class="fixed inset-0 py-8 top-8 z-50 hidden">
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
          <div class="flex items-center justify-between pt-4 border-t border-gray-200">
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
  </div>

  <div id="app" class="flex flex-col min-h-screen">
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
            <div class="flex items-center">
              <button id="menuButton" class="p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>
          <?php } ?>
        </div>
      </div>
    </nav>
    <main class="flex-grow pt-<?= $topMargin ?? 16 ?>">
      <?php if (!empty($userLoggedIn)) { ?>
        <div class="relative" x-data="{ open: false }">
          <div x-show="open"
            x-cloak
            @click.away="open = false"
            x-transition:enter="transition ease-out duration-100"
            x-transition:enter-start="transform opacity-0 scale-95"
            x-transition:enter-end="transform opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="transform opacity-100 scale-100"
            x-transition:leave-end="transform opacity-0 scale-95"
            id="menuHelper"
            class="mr-2 mt-1 hidden fixed right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none z-50">
            <div class="py-1">
              <!-- Profile Section -->
              <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700 flex items-center justify-start space-x-2">
                <p class="text-sm font-medium text-gray-900 dark:text-white">Hello</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate"><?= session()->get('userData')['full_name'] ?? '' ?></p>
              </div>

              <!-- Menu Items -->
              <a href="<?= $baseUrl ?>/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Your Profile
              </a>

              <a href="<?= $baseUrl ?>/profile/edit" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Settings
              </a>

              <a href="<?= $baseUrl ?>/profile/saved" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                </svg>
                Saved Items
              </a>

              <a href="<?= $baseUrl ?>/dashboard/install" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4" />
                </svg>
                How to Install
              </a>

              <a href="<?= $baseUrl ?>/help" class="flex hidden items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Help Center
              </a>

              <!-- Divider -->
              <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

              <!-- Logout -->
              <a onclick="return AppState.logout()" class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="mr-3 h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Sign out
              </a>
            </div>
          </div>
        </div>
      <?php } ?>