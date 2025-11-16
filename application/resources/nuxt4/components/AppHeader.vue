<script setup lang="ts">
const route = useRoute()

const items = computed(() => [
  {
    label: 'Ranking',
    icon: 'i-lucide-trophy',
    to: '/ranking',
    active: route.path.startsWith('/ranking')
  },
  {
    label: 'Races',
    icon: 'i-lucide-flag',
    to: '/races'
  },
  {
    label: 'Racers',
    icon: 'i-lucide-bike',
    to: '/racers'
  }
])
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
      <UColorModeButton />
    </template>

    <template #body>
      <UNavigationMenu :items="items" orientation="vertical" class="-mx-2.5" />
    </template>
  </UHeader>
</template>
