<script setup lang="ts">
definePageMeta({ middleware: 'guest' })
const auth = useAuth()
const language = useAppLocale()
const email = ref('')
const password = ref('')
const error = ref('')
const busy = ref(false)
async function submit() {
  busy.value = true
  error.value = ''
  try {
    await auth.login(email.value, password.value)
    await navigateTo('/')
  } catch {
    error.value = 'Não foi possível entrar. Confira seu e-mail e senha.'
  } finally {
    busy.value = false
  }
}
</script>
<template>
  <UContainer class="max-w-md py-12"
    ><UPageHeader :title="language.t('auth', 'form.title')" /><UCard class="mt-5"
      ><form class="space-y-4" @submit.prevent="submit">
        <UFormField :label="language.t('auth', 'form.email')"
          ><UInput v-model="email" type="email" required class="w-full" /></UFormField
        ><UFormField :label="language.t('auth', 'form.password')"
          ><UInput v-model="password" type="password" required class="w-full" /></UFormField
        ><UAlert v-if="error" color="error" :title="error" /><UButton
          type="submit"
          :loading="busy"
          block
          >{{ language.t('auth', 'form.login') }}</UButton
        >
      </form></UCard
    ></UContainer
  >
</template>
