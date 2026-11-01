<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const language = useAppLocale()
const id = useRoute().params.id
const { data } = await useAsyncData(`championship-${id}`, () =>
  useApi<Record<string, unknown>>(`/api/championships/${id}`)
)
async function update(form: Record<string, unknown>) {
  await useApi(`/api/championships/update/${id}`, { method: 'PUT', body: form })
  await navigateTo(`/ranking/${id}`)
}
</script>
<template>
  <EntityForm
    :title="language.t('championship-form', 'form.title')"
    :initial="data"
    :fields="[{ name: 'name', label: language.t('championship-form', 'form.name') }]"
    :save-label="language.t('championship-form', 'form.update')"
    @submit="update"
  />
</template>
