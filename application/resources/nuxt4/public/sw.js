const CACHE_PREFIX = 'varzealand-v4'
const API_CACHE = `${CACHE_PREFIX}-api`
const STATIC_CACHE = `${CACHE_PREFIX}-static`

self.addEventListener('install', (event) => {
  event.waitUntil(caches.open(STATIC_CACHE).then((cache) => cache.addAll(['/'])))
})

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((key) => !key.startsWith(CACHE_PREFIX)).map((key) => caches.delete(key)))
    )
  )
})

self.addEventListener('message', (event) => {
  if (event.data?.type === 'SKIP_WAITING') self.skipWaiting()
})

self.addEventListener('fetch', (event) => {
  const request = event.request
  const url = new URL(request.url)
  if (request.method !== 'GET' || url.origin !== self.location.origin) return

  if (url.pathname.startsWith('/api/')) {
    if (/^\/api\/(auth|profile)(\/|$)/.test(url.pathname)) {
      event.respondWith(fetch(request))
      return
    }
    event.respondWith(
      fetch(request)
        .then((response) => {
          if (response.ok) caches.open(API_CACHE).then((cache) => cache.put(request, response.clone()))
          return response
        })
        .catch(async () => (await caches.match(request)) || Response.error())
    )
    return
  }

  if (/\.(png|jpe?g|svg|ico|webmanifest)$/.test(url.pathname)) {
    event.respondWith(
      caches.match(request).then((cached) => cached || fetch(request).then((response) => {
        if (response.ok) caches.open(STATIC_CACHE).then((cache) => cache.put(request, response.clone()))
        return response
      }))
    )
  }
})
