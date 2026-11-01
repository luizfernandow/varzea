<script setup lang="ts">
type Racer = { id: number; name: string }
const auth = useAuth()
const language = useAppLocale()
const search = ref('')
const { data, status, error } = await useAsyncData('racers', () =>
  useApi<{ data: Racer[] }>('/api/racers')
)
const racers = computed(() =>
  (data.value?.data ?? []).filter((r) => r.name.toLowerCase().includes(search.value.toLowerCase()))
)
</script>

<template>
  <UContainer class="py-8">
    <UPageHeader :title="language.t('racers', 'title')" description="Atletas cadastrados." />
    <div class="my-5 flex gap-3">
      <UInput v-model="search" icon="i-lucide-search" placeholder="Pesquisar ciclistas" /><UButton
        v-if="auth.loggedIn.value"
        to="/racers/create"
        icon="i-lucide-plus"
        >Novo ciclista</UButton
      >
    </div>
    <UAlert v-if="error" color="error" title="Não foi possível carregar os ciclistas." />
    <div v-else-if="status === 'pending'" class="py-8 text-center">Carregando…</div>
    <div v-else class="divide-y divide-default rounded-lg border border-default">
      <div v-for="racer in racers" :key="racer.id" class="flex items-center justify-between p-4">
        <span>{{ racer.name }}</span
        ><UButton
          v-if="auth.loggedIn.value"
          :to="`/racers/edit/${racer.id}`"
          icon="i-lucide-pencil"
          variant="ghost"
          aria-label="Editar ciclista"
        />
      </div>
      <p v-if="!racers.length" class="p-4 text-muted">Nenhum ciclista encontrado.</p>
    </div>
  </UContainer>
</template>
