const CACHE_NAME = 'sips-v2';
const STATIC_CACHE = 'sips-static-v2';
const DYNAMIC_CACHE = 'sips-dynamic-v2';

// Assets to cache on install
const STATIC_ASSETS = [
    '/',
    '/index.php',
    '/manifest.json',
    '/favicon.ico',
    '/icons/langgar.png',
    '/icons/langgar.png'
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(STATIC_CACHE)
            .then((cache) => {
                console.log('Caching static assets');
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => self.skipWaiting())
    );
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((keys) => {
            return Promise.all(
                keys.filter((key) => {
                    return key !== STATIC_CACHE && key !== DYNAMIC_CACHE;
                }).map((key) => {
                    console.log('Deleting old cache:', key);
                    return caches.delete(key);
                })
            );
        }).then(() => self.clients.claim())
    );
});

// Fetch event - serve from cache, fallback to network
self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip chrome-extension and other non-http(s) requests
    if (!url.protocol.startsWith('http')) {
        return;
    }

    // Skip external requests except for CDN
    if (url.origin !== location.origin && !url.hostname.includes('cdn.') && !url.hostname.includes('googleapis') && !url.hostname.includes('jsdelivr')) {
        return;
    }

    // For same-origin requests
    if (url.origin === location.origin) {
        event.respondWith(
            caches.match(request)
                .then((cachedResponse) => {
                    if (cachedResponse) {
                        // Return cached response and update cache in background
                        event.waitUntil(
                            fetch(request)
                                .then((networkResponse) => {
                                    if (networkResponse.ok) {
                                        caches.open(DYNAMIC_CACHE)
                                            .then((cache) => {
                                                cache.put(request, networkResponse.clone());
                                            });
                                    }
                                })
                                .catch(() => {})
                        );
                        return cachedResponse;
                    }

                    // Not in cache, fetch from network
                    return fetch(request)
                        .then((networkResponse) => {
                            if (networkResponse.ok) {
                                const responseClone = networkResponse.clone();
                                caches.open(DYNAMIC_CACHE)
                                    .then((cache) => {
                                        cache.put(request, responseClone);
                                    });
                            }
                            return networkResponse;
                        })
                        .catch(() => {
                            // Return offline page for navigation requests
                            if (request.mode === 'navigate') {
                                return caches.match('/');
                            }
                            return new Response('Offline', { status: 503 });
                        });
                })
        );
    } else {
        // For cross-origin requests (like fonts, CDNs)
        event.respondWith(
            caches.match(request)
                .then((cachedResponse) => {
                    return cachedResponse || fetch(request).then((networkResponse) => {
                        if (networkResponse.ok) {
                            const responseClone = networkResponse.clone();
                            caches.open(DYNAMIC_CACHE)
                                .then((cache) => {
                                    cache.put(request, responseClone);
                                });
                        }
                        return networkResponse;
                    });
                })
        );
    }
});

// Handle messages from client
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
});

