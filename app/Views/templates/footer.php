<?php
// set the favicon color
$favicon_color = $favicon_color ?? 'dashboard';
?>
</main>
<?php if (empty($footerHidden)) { ?>
  <!-- Modern Professional Footer -->
  <nav id="footerBanner" data-footer-hidden="<?= !empty($footerHidden) ?>" <?= !empty($footerHidden) ? 'style="display: none;"' : '' ?> class="bg-white dark:bg-gray-800 shadow-2xl border-t border-gray-200 dark:border-gray-700 fixed bottom-0 left-0 right-0 z-50 backdrop-blur-lg bg-white/95 dark:bg-gray-800/95">
    <div class="max-w-7xl mx-auto px-4">
      <div class="flex justify-around">
        <?php if (empty($footerHidden) && $userLoggedIn) { ?>
          <!-- Home Navigation -->
          <a href="<?= $baseUrl ?>" class="flex flex-col items-center justify-center px-4 py-3 rounded-xl transition-all duration-300 group <?= $favicon_color == 'dashboard' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">
            <div class="relative">
              <!-- <svg class="h-6 w-6 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg> -->
              <svg class="h-6 w-6 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
              </svg>
              <?php if ($favicon_color == 'dashboard'): ?>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
              <?php endif; ?>
            </div>
            <span class="text-xs mt-1 font-medium">Feed</span>
          </a>

          <!-- Chat Navigation -->
          <a href="<?= $baseUrl ?>/chat" class="flex flex-col items-center justify-center px-4 py-3 rounded-xl transition-all duration-300 group <?= $favicon_color == 'chat' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">
            <div class="relative">
              <svg class="h-6 w-6 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
              <?php if ($favicon_color == 'chat'): ?>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
              <?php endif; ?>
            </div>
            <span class="text-xs mt-1 font-medium">Chat</span>
          </a>

          <!-- Notifications Navigation -->
          <a href="<?= $baseUrl ?>/notifications" class="flex flex-col items-center justify-center px-4 py-3 rounded-xl transition-all duration-300 group <?= $favicon_color == 'notifications' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">
            <div class="relative">
              <svg class="h-6 w-6 transition-transform duration-300 group-hover:scale-110" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
              <?php if ($favicon_color == 'notifications'): ?>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
              <?php endif; ?>
            </div>
            <span class="text-xs mt-1 font-medium">Alerts</span>
          </a>

          <!-- Profile Navigation -->
          <a href="<?= $baseUrl ?>/profile" class="flex flex-col items-center justify-center px-4 py-3 rounded-xl transition-all duration-300 group <?= $favicon_color == 'profile' ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20' : 'text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20' ?>">
            <div class="relative">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <?php if ($favicon_color == 'profile'): ?>
                <div class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full animate-pulse"></div>
              <?php endif; ?>
            </div>
            <span class="text-xs mt-1 font-medium">Profile</span>
          </a>
        <?php } ?>

        <?php if (empty($noInstallation)) { ?>
          <!-- PWA Install Button -->
          <button id="installButton" class="flex flex-col items-center justify-center px-4 py-3 rounded-xl transition-all duration-300 group text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20">
            <div class="relative">
              <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center transition-transform duration-300 group-hover:scale-110">
                <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
              </div>
            </div>
            <span class="text-xs mt-1 font-medium">Install</span>
          </button>
        <?php } ?>
      </div>
    </div>

    <!-- Active Tab Indicator -->
    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 via-purple-500 to-green-500"></div>
  </nav>

  <!-- Floating Back to Top Button -->
  <button id="backToTopBtn" class="fixed bottom-40 right-8 z-50 bg-gradient-to-r from-green-200 to-green-600 text-white rounded-full p-3 shadow-lg hover:shadow-xl transition-all duration-300 transform scale-0 opacity-0 hover:scale-110 group">
    <svg class="w-6 h-6 transition-transform duration-300 group-hover:-translate-y-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
    </svg>
    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">↑</span>
  </button>

  <!-- Bottom Spacer for Fixed Footer -->
<?php } ?>

</div>
<!-- Enhanced Scripts -->
<script src="<?= $baseUrl ?>/assets/js/app.js?v=<?= $version ?>" defer></script>
<?php if (!empty($userLoggedIn)) { ?>
  <script src="<?= $baseUrl ?>/assets/js/websocket.js?v=<?= $version ?>" defer></script>
  <script src="<?= $baseUrl ?>/assets/js/feed-context.js?v=<?= $version ?>" defer></script>
  <script src="<?= $baseUrl ?>/assets/js/groups.js?v=<?= $version ?>"></script>
  <script src="<?= $baseUrl ?>/assets/js/search.js?v=<?= $version ?>"></script>
<?php } ?>
<?php if (!empty($chatSection) && !empty($userLoggedIn)) { ?>
  <script src="<?= $baseUrl ?>/assets/js/chat.js?v=<?= $version ?>" defer></script>
<?php } ?>
<script type="text/javascript">

  // Initialize when DOM is loaded
  document.addEventListener('DOMContentLoaded', function() {
    // Add smooth scrolling to footer navigation
    const footerLinks = document.querySelectorAll('#footerBanner a');
    footerLinks.forEach(link => {
      link.addEventListener('click', function(e) {
        // Add click animation
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
          this.style.transform = '';
        }, 150);
      });
    });

    // Back to Top Button Functionality
    const backToTopBtn = document.getElementById('backToTopBtn');
    if (backToTopBtn) {
      // Show/hide button based on scroll position
      function toggleBackToTopButton() {
        if (window.pageYOffset > 300) {
          backToTopBtn.classList.remove('scale-0', 'opacity-0');
          backToTopBtn.classList.add('scale-100', 'opacity-100');
        } else {
          backToTopBtn.classList.add('scale-0', 'opacity-0');
          backToTopBtn.classList.remove('scale-100', 'opacity-100');
        }
      }

      // Smooth scroll to top function
      function scrollToTop() {
        const startPosition = window.pageYOffset;
        const targetPosition = 0;
        const distance = targetPosition - startPosition;
        const duration = 800;
        const startTime = performance.now();

        function easeOutCubic(t) {
          return 1 - Math.pow(1 - t, 3);
        }

        function animateScroll(currentTime) {
          const elapsed = currentTime - startTime;
          const progress = Math.min(elapsed / duration, 1);
          const easedProgress = easeOutCubic(progress);

          window.scrollTo(0, startPosition + (distance * easedProgress));

          if (progress < 1) {
            requestAnimationFrame(animateScroll);
          }
        }

        requestAnimationFrame(animateScroll);
      }

      // Add event listeners
      window.addEventListener('scroll', toggleBackToTopButton);
      backToTopBtn.addEventListener('click', scrollToTop);

      // Add click animation to back to top button
      backToTopBtn.addEventListener('click', function() {
        // Add click animation
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
          this.style.transform = '';
        }, 150);
      });
    }
  });

  const vapidKey = "<?= $firebaseConfig['vapidKey'] ?? '' ?>";
  const toUint8 = key => {
    const pad = '='.repeat((4 - key.length % 4) % 4);
    const base64 = (key + pad).replace(/-/g, '+').replace(/_/g, '/');
    return Uint8Array.from(atob(base64), c => c.charCodeAt(0));
  };

  let deferredPrompt;

  if ('serviceWorker' in navigator && 'PushManager' in window) {
    window.addEventListener('beforeinstallprompt', e => {
      e.preventDefault();
      deferredPrompt = e;
      $(`button[id="installButton"]`).removeClass('hidden');
    });

    // Check if already installed
    if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
      $(`button[id="installButton"]`).addClass('hidden');
    }

    document.getElementById('installButton')?.addEventListener('click', () => {
      alert('button clicked');
      alert(deferredPrompt);
      deferredPrompt?.prompt();
      alert('user choice');
      alert(deferredPrompt?.userChoice);
      deferredPrompt?.userChoice.then(() => deferredPrompt = null);
      alert('user choice done');
    });

    navigator.serviceWorker.register('<?= $baseUrl ?>/assets/js/sw.js')
      .then(reg => {
        if(localStorage.getItem('substate') == 1) return;
        Notification.requestPermission().then(p => {
          if (p === 'granted') {
            reg.pushManager.subscribe({
              userVisibleOnly: true,
              applicationServerKey: toUint8(vapidKey)
            }).then(sub => {
              navigator.sendBeacon('/api/users/update', JSON.stringify({
                token: localStorage.getItem('token'),
                setting: 'sub_notification',
                value: sub
              }));
              localStorage.setItem('substate', 1);
            });
          }
        });
      })
      .catch(err => console.warn('SW/Push failed:', err));
  }
</script>
</body>

</html>