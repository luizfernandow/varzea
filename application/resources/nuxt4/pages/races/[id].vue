<script setup lang="ts">
type RaceRank = { id: number; racer: { name: string }; time: string; laps: number; points: number }
type GroupRank = { group: number | string; time: string; laps: number }
type RaceDetails = {
  race: { name: string; date_start: string; time_start: string; locked: boolean; type: string }
  data: {
    best_lap?: { racer?: { name: string }; time?: string }
    rank?: RaceRank[]
    rank_group?: GroupRank[]
    groups?: Record<string, { racer?: { name: string } }[]>
  }
}

const id = useRoute().params.id
const auth = useAuth()
const { data, error } = await useAsyncData(`race-${id}`, () =>
  useApi<RaceDetails>(`/api/races/${id}`)
)
const race = computed(() => data.value?.race)
function groupNames(group: number | string) {
  return (data.value?.data.groups?.[String(group)] ?? [])
    .map((member) => member.racer?.name)
    .filter((name): name is string => Boolean(name))
    .join(', ')
}
</script>
<template>
  <UContainer class="py-8"
    ><UAlert v-if="error" color="error" title="Não foi possível carregar a corrida." /><UPageHeader
      v-if="race"
      :title="race.name"
      :description="`${race.date_start} · ${race.time_start}`"
      ><template #links
        ><UButton
          v-if="auth.loggedIn.value && !race.locked"
          :to="`/races/edit/${id}`"
          icon="i-lucide-pencil"
          >Editar</UButton
        ><UButton
          v-if="auth.loggedIn.value && !race.locked"
          :to="`/races/select${race.type === 'hours' ? '-groups' : ''}/${id}`"
          icon="i-lucide-users"
          >Inscrições</UButton
        ><UButton
          v-if="auth.loggedIn.value && !race.locked"
          :to="`/races/start${race.type === 'hours' ? '-groups' : ''}/${id}`"
          icon="i-lucide-flag"
          >Iniciar</UButton
        ><UButton
          v-if="!auth.loggedIn.value && !race.locked"
          :to="`/races/live${race.type === 'hours' ? '-groups' : ''}/${id}`"
          >Acompanhar ao vivo</UButton
        ></template
      ></UPageHeader
    ><UCard v-if="data?.data" class="mt-5"
      ><h2 class="text-lg font-semibold">Melhor volta</h2>
      <p>{{ data.data.best_lap?.racer?.name }} · {{ data.data.best_lap?.time }}</p>
      <ol v-if="data.data.rank" class="mt-4 divide-y divide-default">
        <li v-for="(row, i) in data.data.rank" :key="row.id" class="flex justify-between py-3">
          <span>{{ i + 1 }}. {{ row.racer.name }}</span
          ><span>{{ row.time }} · {{ row.laps }} voltas · {{ row.points }} pts</span>
        </li>
      </ol>
      <ol v-else-if="data.data.rank_group" class="mt-4 divide-y divide-default">
        <li v-for="(row, i) in data.data.rank_group" :key="row.group" class="py-3">
          <div class="flex justify-between font-semibold">
            <span>{{ i + 1 }}. Grupo {{ row.group }}</span
            ><span>{{ row.time }} · {{ row.laps }} voltas</span>
          </div>
          <p class="text-sm text-muted">
            {{ groupNames(row.group) }}
          </p>
        </li>
      </ol>
      <p v-else class="mt-3 text-muted">Sem resultados registrados.</p>
    </UCard></UContainer
  >
</template>
