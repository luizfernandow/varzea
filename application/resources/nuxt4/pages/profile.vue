<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const auth = useAuth()
const language = useAppLocale()
</script>
<template>
  <UContainer class="max-w-lg py-8"
    ><UPageHeader :title="language.t('auth', 'profile')" /><UCard class="mt-5"
      ><div class="flex items-center gap-4">
        <img src="/biker.png" alt="Ciclista" class="size-14 rounded-full" />
        <div>
          <p class="text-lg font-semibold">{{ auth.user.value?.name }}</p>
          <p class="text-muted">{{ auth.user.value?.email }}</p>
        </div>
      </div>
      <template #footer
        ><UButton to="/edit-profile">{{ language.t('profile', 'edit') }}</UButton></template
      ></UCard
    ></UContainer
  >
</template>
