<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const language = useAppLocale()
async function create(form: Record<string, unknown>) {
  await useApi('/api/racers/create', { method: 'POST', body: form })
  await navigateTo('/racers')
}
</script>
<template>
  <EntityForm
    :title="language.t('racer-form', 'form.title')"
    :fields="[{ name: 'name', label: language.t('racer-form', 'form.name') }]"
    :save-label="language.t('racer-form', 'form.create')"
    @submit="create"
  />
</template>
