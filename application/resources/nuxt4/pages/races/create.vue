<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const language = useAppLocale()
const { data } = await useAsyncData('championship-select', () =>
  useApi<{ data: { id: number; name: string }[] }>('/api/championships')
)
const fields = computed(() => [
  { name: 'name', label: language.t('race-form', 'form.name') },
  {
    name: 'championship_id',
    label: language.t('race-form', 'form.championship'),
    options: (data.value?.data ?? []).map((c) => ({ value: c.id, label: c.name }))
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
async function create(form: Record<string, unknown>) {
  await useApi('/api/races/create', {
    method: 'POST',
    body: { ...form, type: form.type === 'hours' }
  })
  await navigateTo('/races')
}
</script>
<template>
  <EntityForm
    :title="language.t('race-form', 'form.title')"
    :initial="{ type: 'laps' }"
    :fields="fields"
    :save-label="language.t('race-form', 'form.create')"
    @submit="create"
  />
</template>
