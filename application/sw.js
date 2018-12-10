workbox.skipWaiting();
workbox.clientsClaim();

workbox.core.setCacheNameDetails({
  prefix: 'varzea',
  suffix: 'v1'
});

self.__precacheManifest = self.__precacheManifest.concat([
  {
      "url": "/offline"
  },
  {
      "url": "/manifest.json"
  },
]);

workbox.precaching.precacheAndRoute(self.__precacheManifest || []);

const FALLBACK_OFFLINE = '/offline';

workbox.routing.registerRoute(
  new RegExp('https://fonts.(?:googleapis|gstatic).com/(.*)'),
  workbox.strategies.staleWhileRevalidate({
    cacheName: 'googleapis',
    plugins: [
      new workbox.expiration.Plugin({
        maxEntries: 30,
      }),
    ],
  }),
);

workbox.routing.registerRoute(
  /\.(?:png|gif|jpg|jpeg|svg)$/,
  workbox.strategies.cacheFirst({
    cacheName: 'images',
    plugins: [
      new workbox.expiration.Plugin({
        maxEntries: 60,
        maxAgeSeconds: 30 * 24 * 60 * 60, // 30 Days
      }),
    ],
  }),
);

workbox.routing.registerRoute(
  /\.(?:js|css)$/,
  workbox.strategies.staleWhileRevalidate({
    cacheName: 'static-resources',
  })
);

const homeHandler = workbox.strategies.networkFirst({
  networkTimeoutSeconds: 10,
});

workbox.routing.registerRoute('/', ({event}) => {
  return homeHandler.handle({event})
    .catch(() => caches.match(FALLBACK_OFFLINE));
});

workbox.routing.registerRoute('/races', ({event}) => {
  return homeHandler.handle({event})
    .catch(() => caches.match(FALLBACK_OFFLINE));
});

workbox.routing.registerRoute('/racers', ({event}) => {
  return homeHandler.handle({event})
    .catch(() => caches.match(FALLBACK_OFFLINE));
});

