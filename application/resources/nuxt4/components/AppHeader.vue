<script setup lang="ts">
const route = useRoute()
const auth = useAuth()
const language = useAppLocale()

const items = computed(() => [
  {
    label: language.t('menu', 'ranking'),
    icon: 'i-lucide-trophy',
    to: '/ranking',
    active: route.path.startsWith('/ranking')
  },
  {
    label: language.t('menu', 'races'),
    icon: 'i-lucide-flag',
    to: '/races'
  },
  {
    label: language.t('menu', 'racers'),
    icon: 'i-lucide-bike',
    to: '/racers'
  }
])

async function signOut() {
  await auth.logout()
  await navigateTo('/')
}
</script>

<template>
  <UHeader>
    <template #left>
      <NuxtLink to="/">
        <NuxtImg src="/icon.png" alt="icon" class="w-auto h-12 shrink-0" />
      </NuxtLink>
    </template>

    <UNavigationMenu :items="items" variant="link" />

    <template #right>
      <UButton
        v-if="auth.loggedIn.value"
        to="/profile"
        variant="ghost"
        :label="auth.user.value?.name"
      />
      <UButton
        v-if="auth.loggedIn.value"
        icon="i-lucide-log-out"
        variant="ghost"
        aria-label="Sair"
        @click="signOut"
      />
      <UButton v-else to="/login" variant="ghost" :label="language.t('auth', 'form.login')" />
      <UButton
        variant="ghost"
        :label="language.locale.value === 'pt-BR' ? 'EN' : 'PT'"
        aria-label="Alterar idioma"
        @click="language.toggle"
      />
      <UColorModeButton />
    </template>

    <template #body>
      <UNavigationMenu :items="items" orientation="vertical" class="-mx-2.5" />
    </template>
  </UHeader>
</template>
