<?php
// set the favicon color
$favicon_color = $favicon_color ?? 'dashboard';
?>
</main>
<?php if(empty($footerHidden)) { ?>
<!-- Modern Professional Footer -->
<nav id="footerBanner" data-footer-hidden="<?= !empty($footerHidden) ?>" <?= !empty($footerHidden) ? 'style="display: none;"' : '' ?> class="bg-white dark:bg-gray-800 shadow-2xl border-t border-gray-200 dark:border-gray-700 fixed bottom-0 left-0 right-0 z-50 backdrop-blur-lg bg-white/95 dark:bg-gray-800/95">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-around h-20">
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

      <?php if(empty($noInstallation)) { ?>
        <!-- PWA Install Button -->
        <button id="installButton" class="hidden flex flex-col items-center justify-center px-4 py-3 rounded-xl transition-all duration-300 group text-gray-600 dark:text-gray-400 hover:text-purple-600 dark:hover:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20">
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

<!-- Bottom Spacer for Fixed Footer -->
<?php } ?>

</div>

<!-- Enhanced Scripts -->
<script src="<?= $baseUrl ?>/assets/js/app.js?v=<?= $version ?>" defer></script>
<script src="<?= $baseUrl ?>/assets/js/feed-context.js?v=<?= $version ?>" defer></script>

<script>
// Enhanced PWA Installation Handler
let deferredPrompt;
let installButton;

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    installButton = document.getElementById('installButton');
    
    // Check if already installed
    if (window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true) {
        if (installButton) {
            installButton.classList.add('hidden');
        }
        return;
    }

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
});

// Service Worker Registration with Enhanced Error Handling
if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('<?= $baseUrl ?>/assets/js/sw.js')
            .then(registration => {
                // Check for updates
                registration.addEventListener('updatefound', () => {
                    const newWorker = registration.installing;
                    newWorker.addEventListener('statechange', () => {});
                });
            })
            .catch(err => { });
    });
}

// Before Install Prompt Event
window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    
    if (installButton) {
        installButton.classList.remove('hidden');
        
        // Add entrance animation
        installButton.style.opacity = '0';
        installButton.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            installButton.style.transition = 'all 0.3s ease-out';
            installButton.style.opacity = '1';
            installButton.style.transform = 'translateY(0)';
        }, 100);
    }
});

// App Installed Event
window.addEventListener('appinstalled', (evt) => {
    if (installButton) {
        // Add success animation before hiding
        installButton.style.transform = 'scale(1.1)';
        installButton.style.backgroundColor = '#10B981';
        
        setTimeout(() => {
            installButton.classList.add('hidden');
        }, 500);
    }
    deferredPrompt = null;
    
    // Show success notification
    showNotification('App installed successfully! 🎉', 'success');
});

// Enhanced Install Button Click Handler
document.addEventListener('click', function(e) {
    if (e.target.closest('#installButton')) {
        e.preventDefault();
        
        if (deferredPrompt) {
            // Add loading state
            const button = e.target.closest('#installButton');
            const originalContent = button.innerHTML;
            
            button.innerHTML = `
                <div class="flex flex-col items-center">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                    </div>
                    <span class="text-xs mt-1 font-medium">Installing...</span>
                </div>
            `;
            
            deferredPrompt.prompt();
            
            deferredPrompt.userChoice.then((choiceResult) => {
                if (choiceResult.outcome === 'accepted') {
                } else {
                    // Restore original content
                    button.innerHTML = originalContent;
                }
                deferredPrompt = null;
            });
        }
    }
});

// Add CSS animations for enhanced footer
const footerStyle = document.createElement('style');
footerStyle.textContent = `
    #footerBanner {
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
    }
    
    #footerBanner a:hover,
    #footerBanner button:hover {
        transform: translateY(-2px);
    }
    
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }
    
    @media (max-width: 640px) {
        #footerBanner {
            padding-bottom: env(safe-area-inset-bottom);
        }
    }
`;
document.head.appendChild(footerStyle);
</script>
</body>

</html>