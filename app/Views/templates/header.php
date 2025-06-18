<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $pageTitle ?? 'Dashboard' ?> - <?= $appName ?></title>
  <meta name="description" content="Connect with your local community anonymously">
  <link rel="manifest" href="<?= $baseUrl ?>/assets/manifest.json">
  <meta name="theme-color" content="#2196F3">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="apple-touch-icon" href="<?= $baseUrl ?>/assets/icons/Icon.192.png">

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
      userLoggedin = <?= !empty($userLoggedin) ? 'true' : 'false' ?>,
      websocketUrl = '<?= $websocketUrl ?>',
      loadingSkeleton = `<?= function_exists('loadingSkeleton') ? loadingSkeleton() : '' ?>`;
    <?php if (!empty($logoutUser)) { ?>
      localStorage.removeItem('user');
      localStorage.removeItem('token');
    <?php } ?>
  </script>
  <link rel="stylesheet" href="<?= $baseUrl ?>/assets/css/app.css">
</head>

<body class="bg-gray-100 min-h-screen">

  <!-- Post Creation Form -->
  <div id="postCreationForm" class="fixed inset-0 py-8 top-8 z-50 hidden">
    <!-- Overlay -->
    <div class="absolute inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

    <!-- Form Container -->
    <div class="relative bg-white dark:bg-gray-800 shadow-lg rounded-b-2xl">
      <div class="px-4 py-4">
        <div class="text-xl font-medium text-gray-900 dark:text-white mb-2">
          <button type="button" onclick="return PostManager.closeCreateModal()" ; id="backButton" class="text-gray-500 text-base hover:text-gray-700 dark:hover:text-gray-300 flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back
          </button>
        </div>
        <form id="createPostForm" method="post" enctype="multipart/form-data" class="space-y-4">
          <!-- Text Input -->
          <div class="relative">
            <textarea id="postContent" rows="5"
              class="w-full px-4 py-3 font-medium text-xl border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white resize-none text-base"
              placeholder="Share your thoughts and experiences with users in a 10km radius..." name="content"></textarea>
          </div>

          <!-- Media Preview -->
          <div id="mediaPreview" class="hidden">
            <div class="relative rounded-xl overflow-hidden">
              <img id="previewImage" class="max-h-96 w-full object-cover" src="" alt="Preview">
              <button type="button" id="removeMedia"
                class="absolute top-3 right-3 bg-gray-900 bg-opacity-50 rounded-full p-2 text-white hover:bg-opacity-75 transition-opacity">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Tags Input -->
          <div class="flex hidden flex-wrap gap-2 p-2 bg-gray-50 dark:bg-gray-700/50 rounded-xl" id="tagsContainer">
            <input type="text" id="tagInput"
              class="flex-1 min-w-[120px] px-3 py-1.5 border border-gray-300 dark:border-gray-600 rounded-full text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-700 dark:text-white"
              placeholder="Add tags...">
          </div>

          <!-- Action Buttons -->
          <div class="flex items-center justify-between pt-2 border-t border-gray-200 dark:border-gray-700">
            <div class="flex flex-wrap gap-2">
              <!-- Photo/Video Upload -->
              <label class="inline-flex items-center space-x-2 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                <input type="file" id="mediaUpload" class="hidden" accept="image/*,video/*">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Photo/Video</span>
              </label>
            </div>

            <!-- Post Button -->
            <button type="submit" id="postButton"
              class="ml-4 px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors font-medium flex items-center">
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
              </svg>
              Post
            </button>
          </div>
        </form>
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
          <?php if (!empty($userLoggedin)) { ?>
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
      <?php if (!empty($userLoggedin)) { ?>
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
              <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                <p class="text-sm font-medium text-gray-900 dark:text-white">Signed in as</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 truncate"><?= session()->get('email') ?></p>
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

              <a href="<?= $baseUrl ?>/help" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="mr-3 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Help Center
              </a>

              <!-- Divider -->
              <div class="border-t border-gray-200 dark:border-gray-700 my-1"></div>

              <!-- Logout -->
              <a href="/logout" class="flex items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                <svg class="mr-3 h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Sign out
              </a>
            </div>
          </div>
        </div>
      <?php } ?>