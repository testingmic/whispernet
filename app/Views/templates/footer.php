</main>
<!-- Bottom Navigation -->
<nav class="bg-white shadow-lg fixed bottom-0 left-0 right-0 z-50">
  <div class="max-w-7xl mx-auto px-4">
    <div class="flex justify-around h-16">
      <a href="/" class="flex flex-col items-center justify-center px-3 py-2 text-gray-600 hover:text-blue-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span class="text-xs mt-1">Home</span>
      </a>
      <a href="/create" class="flex flex-col items-center justify-center px-3 py-2 text-gray-600 hover:text-blue-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="text-xs mt-1">Post</span>
      </a>
      <a href="/chat" class="flex flex-col items-center justify-center px-3 py-2 text-gray-600 hover:text-blue-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <span class="text-xs mt-1">Chat</span>
      </a>
      <a href="/profile" class="flex flex-col items-center justify-center px-3 py-2 text-gray-600 hover:text-blue-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
        </svg>
        <span class="text-xs mt-1">Profile</span>
      </a>
    </div>
  </div>
</nav>
</div>
<!-- PWA Service Worker Registration -->
<script>
  const baseUrl = '<?= $baseUrl ?>';
</script>
<script src="<?= $baseUrl ?>/assets/js/app.js" defer></script>
<script>
  if ('serviceWorker' in navigator) {
      window.addEventListener('load', () => {
          navigator.serviceWorker.register('<?= $baseUrl ?>/assets/js/sw.js')
              .then(registration => {
                  console.log('ServiceWorker registration successful');
              })
              .catch(err => {
                  console.log('ServiceWorker registration failed: ', err);
              });
      });
  }
</script>
</body>

</html>