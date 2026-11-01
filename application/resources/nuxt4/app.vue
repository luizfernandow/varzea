<script setup lang="ts">
const auth = useAuth()
const language = useAppLocale()
const updateAvailable = useState('pwa-update-available', () => false)
const registration = useState<ServiceWorkerRegistration | null>('pwa-registration', () => null)

onMounted(() => {
  void auth.refresh()
})

function installUpdate() {
  registration.value?.waiting?.postMessage({ type: 'SKIP_WAITING' })
}

useHead({
  meta: [{ name: 'viewport', content: 'width=device-width, initial-scale=1' }],
  link: [
    { rel: 'icon', href: '/favicon.ico' },
    { rel: 'manifest', href: '/manifest.webmanifest' }
  ],
  titleTemplate: '%s - Várzealand',
  htmlAttrs: { lang: language.locale }
})
</script>

<template>
  <UApp>
    <div
      v-if="updateAvailable"
      class="flex items-center justify-center gap-3 bg-primary px-4 py-2 text-sm text-inverted"
    >
      <span>{{ language.t('update', 'message') }}</span>
      <UButton size="xs" color="neutral" variant="outline" @click="installUpdate">{{
        language.t('update', 'click')
      }}</UButton>
    </div>
    <AppHeader />

    <UMain>
      <NuxtPage />
    </UMain>

    <USeparator />

    <UFooter>
      <template #left>
        <p class="text-sm text-muted">Várzealand • © {{ new Date().getFullYear() }}</p>
      </template>
    </UFooter>
  </UApp>
</template>
