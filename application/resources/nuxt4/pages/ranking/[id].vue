<script setup lang="ts">
const route = useRoute()
const id = route.params.id
const auth = useAuth()
const language = useAppLocale()
const { data, error } = await useAsyncData(`ranking-${id}`, () =>
  useApi<{
    data: { id: number; racer: { name: string }; points: number }[]
    championship: { id: number; name: string }
  }>(`/api/ranking/by-championship/${id}`)
)
</script>
<template>
  <UContainer class="py-8"
    ><UPageHeader :title="data?.championship?.name || 'Classificação'"
      ><template #links
        ><UButton
          v-if="auth.loggedIn.value"
          :to="`/championships/edit/${id}`"
          icon="i-lucide-pencil"
          >Editar</UButton
        ></template
      ></UPageHeader
    ><UAlert v-if="error" color="error" title="Não foi possível carregar a classificação." />
    <ol class="mt-5 divide-y divide-default rounded-lg border border-default">
      <li v-for="(row, i) in data?.data" :key="row.id" class="flex justify-between p-4">
        <span>{{ i + 1 }}. {{ row.racer.name }}</span
        ><span>{{ language.t('ranking', 'points', { points: row.points }) }}</span>
      </li>
    </ol></UContainer
  >
</template>
