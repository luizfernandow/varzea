<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
const auth = useAuth()
const language = useAppLocale()
const name = ref(auth.user.value?.name ?? '')
const saving = ref(false)
async function update(form: Record<string, unknown>) {
  saving.value = true
  try {
    await useApi('/api/profile/update', { method: 'POST', body: form })
    await auth.refresh()
    await navigateTo('/profile')
  } finally {
    saving.value = false
  }
}
</script>
<template>
  <EntityForm
    :title="language.t('edit-profile', 'form.title')"
    :initial="{ name }"
    :fields="[{ name: 'name', label: language.t('edit-profile', 'form.name') }]"
    :save-label="language.t('edit-profile', 'form.update')"
    @submit="update"
  />
</template>
