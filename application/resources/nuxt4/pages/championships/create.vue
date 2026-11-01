<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const language = useAppLocale()
async function create(form: Record<string, unknown>) {
  await useApi('/api/championships/create', { method: 'POST', body: form })
  await navigateTo('/ranking')
}
</script>
<template>
  <EntityForm
    :title="language.t('championship-form', 'form.title')"
    :fields="[{ name: 'name', label: language.t('championship-form', 'form.name') }]"
    :save-label="language.t('championship-form', 'form.create')"
    @submit="create"
  />
</template>
