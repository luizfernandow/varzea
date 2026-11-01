<script setup lang="ts">
const props = defineProps<{
  title: string
  initial?: Record<string, unknown>
  fields: {
    name: string
    label: string
    type?: string
    required?: boolean
    showWhen?: { name: string; value: string }
    options?: { label: string; value: string | number }[]
  }[]
  saveLabel?: string
  cancelLabel?: string
  onSubmit?: (form: Record<string, unknown>) => Promise<void>
}>()
const language = useAppLocale()
type FormValue = string | number | undefined
const form = reactive<Record<string, FormValue>>({
  ...((props.initial ?? {}) as Record<string, FormValue>)
})
const error = ref('')
const saving = ref(false)
const router = useRouter()

async function submit() {
  error.value = ''
  saving.value = true
  try {
    await props.onSubmit?.({ ...form })
  } catch (cause: unknown) {
    const message = (cause as { data?: { message?: string } }).data?.message
    error.value = message || 'Não foi possível salvar. Confira os dados e tente novamente.'
  } finally {
    saving.value = false
  }
}
</script>

<template>
  <UContainer class="max-w-2xl py-8">
    <UPageHeader :title="title" />
    <UCard class="mt-5">
      <form class="space-y-4" @submit.prevent="submit">
        <template v-for="field in fields" :key="field.name">
          <UFormField
            v-if="!field.showWhen || form[field.showWhen.name] === field.showWhen.value"
            :label="field.label"
            :name="field.name"
            :required="field.required !== false"
          >
            <USelect
              v-if="field.options"
              v-model="form[field.name]"
              :items="field.options"
              value-key="value"
              label-key="label"
              class="w-full"
            />
            <UInput
              v-else
              v-model="form[field.name]"
              :type="field.type || 'text'"
              class="w-full"
              :required="field.required !== false"
            />
          </UFormField>
        </template>
        <UAlert v-if="error" color="error" :title="error" />
        <slot name="error" />
        <div class="flex justify-end gap-2">
          <UButton type="button" variant="ghost" @click="router.back()">{{
            cancelLabel || language.t('championship-form', 'form.cancel')
          }}</UButton
          ><UButton type="submit" :loading="saving">{{
            saveLabel || language.t('championship-form', 'form.create')
          }}</UButton>
        </div>
      </form>
    </UCard>
  </UContainer>
</template>
