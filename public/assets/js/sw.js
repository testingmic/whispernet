const CACHE_NAME = 'TalkLowKey-v2';
const baseUrl = '';
const ASSETS_TO_CACHE = [
    `/assets/css/app.css`,
    `/assets/js/app.js`,
    `/assets/js/chat.js`,
    `/assets/js/websocket.js`,
    `/assets/images/logo.png`,
    `/assets/icons/Icon.192.png`,
    `/assets/icons/Icon.512.png`
];

// Install Service Worker
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                return cache.addAll(ASSETS_TO_CACHE);
            })
    );
});

// Activate Service Worker
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME) {
                        console.log('Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Fetch Event
self.addEventListener('fetch', (event) => {
    event.respondWith(
        caches.match(event.request)
            .then((response) => {
                // Cache hit - return response
                if (response) {
                    return response;
                }

                // Clone the request
                const fetchRequest = event.request.clone();

                return fetch(fetchRequest).then(
                    (response) => {
                        // Check if we received a valid response
                        if (!response || response.status !== 200 || response.type !== 'basic') {
                            return response;
                        }

                        // Clone the response
                        const responseToCache = response.clone();

                        caches.open(CACHE_NAME)
                            .then((cache) => {
                                cache.put(event.request, responseToCache);
                            });

                        return response;
                    }
                );
            })
    );
});

// Push Notification Event
self.addEventListener('push', (event) => {
    const payload = event.data.json();
    const options = {
        body: payload.body,
        icon: `/assets/icons/Icon.192.png`,
        badge: `/assets/icons/Icon.72.png`,
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1,
            url: payload.urlPath || '/',
        },
        actions: [
            {
                action: 'explore',
                title: 'View Post',
                icon: `/assets/images/icons/checkmark.png`
            },
            {
                action: 'close',
                title: 'Close',
                icon: `/assets/images/icons/xmark.png`
            }
        ]
    };

    event.waitUntil(
        self.registration.showNotification(payload.title, options)
    );
});

// Notification Click Event
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'explore') {
        event.waitUntil(
            clients.openWindow('/')
        );
    }
});

// IndexedDB setup
function openDB() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('TalkLowKeyDB', 1);

        request.onerror = () => reject(request.error);
        request.onsuccess = () => resolve(request.result);

        request.onupgradeneeded = (event) => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains('pendingPosts')) {
                db.createObjectStore('pendingPosts', { keyPath: 'id' });
            }
        };
    });
} 