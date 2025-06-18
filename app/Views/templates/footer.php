</main>
<nav id="footerBanner" data-footer-hidden="<?= $footerHidden ?>" class="bg-white shadow-lg fixed bottom-0 left-0 right-0 z-50">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-around h-16">
      <?php if (!$footerHidden) { ?>
        <a href="<?= $baseUrl ?>" class="flex flex-col items-center justify-center px-3 py-2 text-gray-600 hover:text-blue-500">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span class="text-xs mt-1">Home</span>
        </a>
        <a href="<?= $baseUrl ?>/notifications" class="flex flex-col items-center justify-center px-3 py-2 text-gray-600 hover:text-blue-500">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          <span class="text-xs mt-1">Notifices</span>
        </a>
        <a href="<?= $baseUrl ?>/chat" class="flex flex-col items-center justify-center px-3 py-2 text-gray-600 hover:text-blue-500">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
          </svg>
          <span class="text-xs mt-1">Chat</span>
        </a>
        <a href="<?= $baseUrl ?>/profile" class="flex flex-col items-center justify-center px-3 py-2 text-gray-600 hover:text-blue-500">
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
          </svg>
          <span class="text-xs mt-1">Profile</span>
        </a>
      <?php } ?>
      <button id="installButton" class="hidden flex flex-col items-center justify-center px-3 py-2 text-gray-600 hover:text-blue-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        <span class="text-xs mt-1">Install</span>
      </button>
    </div>
  </div>
</nav>
</div>
<script src="<?= $baseUrl ?>/assets/js/app.js?v=<?= $version ?>" defer></script>
<script src="<?= $baseUrl ?>/assets/js/feed-context.js?v=<?= $version ?>" defer></script>
<script>
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
      navigator.serviceWorker.register('<?= $baseUrl ?>/assets/js/sw.js?v=<?= $version ?>&path=<?= $baseUrl ?>')
        .then(registration => {
          console.log('ServiceWorker registration successful');
        })
        .catch(err => {
          console.log('ServiceWorker registration failed: ', err);
        });
    });
  }

  let deferredPrompt;
  const installButton = document.getElementById('installButton');

  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    deferredPrompt = e;
    if (installButton) {
      installButton.classList.remove('hidden');
    }
  });

  const footerBanner = document.getElementById('footerBanner');
  window.addEventListener('appinstalled', (evt) => {
    if (installButton) {
      installButton?.classList?.add('hidden');
      if (footerBanner.getAttribute('data-footer-hidden') === '1') {
        footerBanner.classList.add('hidden');
      }
    }
    deferredPrompt = null;
  });

  installButton?.addEventListener('click', async () => {
    if (deferredPrompt) {
      deferredPrompt.prompt();
      const {
        outcome
      } = await deferredPrompt.userChoice;
      deferredPrompt = null;
      installButton?.classList?.add('hidden');
    }
  });
</script>
</body>

</html>