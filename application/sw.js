workbox.skipWaiting();
workbox.clientsClaim();

workbox.routing.registerRoute(
  '/',
  workbox.strategies.networkFirst()
);