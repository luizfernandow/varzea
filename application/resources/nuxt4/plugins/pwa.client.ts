export default defineNuxtPlugin(() => {
  const updateAvailable = useState('pwa-update-available', () => false)
  const registration = useState<ServiceWorkerRegistration | null>('pwa-registration', () => null)

  if (!('serviceWorker' in navigator)) return

  navigator.serviceWorker.addEventListener('controllerchange', () => {
    window.location.reload()
  })

  void navigator.serviceWorker.register('/sw.js').then((registered) => {
    registration.value = registered
    if (registered.waiting && navigator.serviceWorker.controller) updateAvailable.value = true

    registered.addEventListener('updatefound', () => {
      const worker = registered.installing
      worker?.addEventListener('statechange', () => {
        if (worker.state === 'installed' && navigator.serviceWorker.controller) {
          updateAvailable.value = true
        }
      })
    })

    window.setInterval(() => void registered.update(), 60 * 60 * 1000)
  })
})
