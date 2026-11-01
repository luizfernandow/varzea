<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const language = useAppLocale()
const id = useRoute().params.id
const [{ data: race }, { data: champs }] = await Promise.all([
  useAsyncData(`race-edit-${id}`, () => useApi<Record<string, unknown>>(`/api/races/${id}/edit`)),
  useAsyncData('championship-select', () =>
    useApi<{ data: { id: number; name: string }[] }>('/api/championships')
  )
])
const fields = computed(() => [
  { name: 'name', label: language.t('race-form', 'form.name') },
  {
    name: 'championship_id',
    label: language.t('race-form', 'form.championship'),
    options: (champs.value?.data ?? []).map((c) => ({ value: c.id, label: c.name }))
  },
  { name: 'date_start', label: language.t('race-form', 'form.date_start'), type: 'date' },
  { name: 'time_start', label: language.t('race-form', 'form.time_start'), type: 'time' },
  {
    name: 'type',
    label: language.t('race-form', 'form.type'),
    options: [
      { value: 'laps', label: language.t('race-form', 'form.laps') },
      { value: 'hours', label: language.t('race-form', 'form.hours') }
    ]
  },
  {
    name: 'laps',
    label: language.t('race-form', 'form.laps'),
    type: 'number',
    showWhen: { name: 'type', value: 'laps' }
  },
  {
    name: 'hours',
    label: language.t('race-form', 'form.hours'),
    type: 'number',
    showWhen: { name: 'type', value: 'hours' }
  },
  {
    name: 'group',
    label: language.t('race-form', 'form.group'),
    type: 'number',
    showWhen: { name: 'type', value: 'hours' }
  }
])
const initial = computed(() =>
  race.value
    ? {
        ...race.value,
        date_start: String(race.value.date_start ?? '')
          .split('/')
          .reverse()
          .join('-')
      }
    : undefined
)
async function update(form: Record<string, unknown>) {
  await useApi(`/api/races/update/${id}`, {
    method: 'PUT',
    body: { ...form, type: form.type === 'hours' }
  })
  await navigateTo(`/races/${id}`)
}
</script>
<template>
  <EntityForm
    :title="language.t('race-form', 'form.title')"
    :initial="initial"
    :fields="fields"
    :save-label="language.t('race-form', 'form.update')"
    @submit="update"
  />
</template>
