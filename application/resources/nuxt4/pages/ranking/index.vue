<script setup lang="ts">
const auth = useAuth()
const language = useAppLocale()
const { data, error } = await useAsyncData('championships', () =>
  useApi<{ data: { id: number; name: string }[] }>('/api/ranking/championships')
)
</script>
<template>
  <UContainer class="py-8"
    ><UPageHeader :title="language.t('ranking', 'championships')" />
    <div class="my-5">
      <UButton v-if="auth.loggedIn.value" to="/championships/create" icon="i-lucide-plus"
        >Novo campeonato</UButton
      >
    </div>
    <UAlert v-if="error" color="error" title="Não foi possível carregar os campeonatos." />
    <div class="divide-y divide-default rounded-lg border border-default">
      <NuxtLink
        v-for="item in data?.data"
        :key="item.id"
        :to="`/ranking/${item.id}`"
        class="block p-4 hover:bg-elevated"
        >{{ item.name }}</NuxtLink
      >
      <p v-if="!data?.data?.length" class="p-4 text-muted">Nenhum campeonato cadastrado.</p>
    </div></UContainer
  >
</template>
