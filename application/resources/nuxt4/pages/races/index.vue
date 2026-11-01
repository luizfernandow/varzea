<script setup lang="ts">
type Race = { id: number; name: string; date_start: string; time_start: string; laps?: number }
const auth = useAuth()
const language = useAppLocale()
const search = ref('')
const { data, status, error } = await useAsyncData('races', () =>
  useApi<{ data: Race[] }>('/api/races')
)
const races = computed(() =>
  (data.value?.data ?? []).filter((r) => r.name.toLowerCase().includes(search.value.toLowerCase()))
)
</script>

<template>
  <UContainer class="py-8">
    <UPageHeader
      :title="language.t('races', 'title')"
      description="Calendário e resultados das corridas."
    />
    <div class="my-5 flex flex-wrap gap-3">
      <UInput v-model="search" icon="i-lucide-search" placeholder="Pesquisar corridas" />
      <UButton v-if="auth.loggedIn.value" to="/races/create" icon="i-lucide-plus"
        >Nova corrida</UButton
      >
    </div>
    <UAlert v-if="error" color="error" title="Não foi possível carregar as corridas." />
    <div v-else-if="status === 'pending'" class="py-8 text-center">Carregando…</div>
    <div v-else class="divide-y divide-default rounded-lg border border-default">
      <NuxtLink
        v-for="race in races"
        :key="race.id"
        :to="`/races/${race.id}`"
        class="block p-4 hover:bg-elevated"
      >
        <div class="font-semibold">{{ race.name }}</div>
        <div class="text-sm text-muted">
          {{ race.date_start }} · {{ race.time_start
          }}<span v-if="race.laps"> · {{ language.t('races', 'laps', { laps: race.laps }) }}</span>
        </div>
      </NuxtLink>
      <p v-if="!races.length" class="p-4 text-muted">Nenhuma corrida encontrada.</p>
    </div>
  </UContainer>
</template>
