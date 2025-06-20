const CACHE_NAME = 'TalkLowKey-v2';
const baseUrl = '';
const ASSETS_TO_CACHE = [
    `/`,
    `/assets/css/app.css`,
    `/assets/js/app.js`,
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
    const options = {
        body: event.data.text(),
        icon: `/assets/icons/Icon.192.png`,
        badge: `/assets/icons/Icon.72.png`,
        vibrate: [100, 50, 100],
        data: {
            dateOfArrival: Date.now(),
            primaryKey: 1
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
        self.registration.showNotification('TalkLowKey', options)
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

// Background Sync
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-posts') {
        event.waitUntil(syncPosts());
    }
});

async function syncPosts() {
    try {
        const db = await openDB();
        const posts = await db.getAll('pendingPosts');
        
        for (const post of posts) {
            try {
                const response = await fetch(`/api/posts`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(post)
                });

                if (response.ok) {
                    await db.delete('pendingPosts', post.id);
                }
            } catch (error) {
                console.error('Error syncing post:', error);
            }
        }
    } catch (error) {
        console.error('Error in sync:', error);
    }
}

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