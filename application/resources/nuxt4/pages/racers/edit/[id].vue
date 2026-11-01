<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const language = useAppLocale()
const id = useRoute().params.id
const { data } = await useAsyncData(`racer-${id}`, () =>
  useApi<Record<string, unknown>>(`/api/racers/${id}`)
)
async function update(form: Record<string, unknown>) {
  await useApi(`/api/racers/update/${id}`, { method: 'PUT', body: form })
  await navigateTo('/racers')
}
</script>
<template>
  <EntityForm
    :title="language.t('racer-form', 'form.title')"
    :initial="data"
    :fields="[{ name: 'name', label: language.t('racer-form', 'form.name') }]"
    :save-label="language.t('racer-form', 'form.update')"
    @submit="update"
  />
</template>
