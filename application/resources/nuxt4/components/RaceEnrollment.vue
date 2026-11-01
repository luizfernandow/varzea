<script setup lang="ts">
type EnrollmentRacer = { id: number; name: string }
type EnrollmentMember = {
  racer_id: number | string
  number: number | string
  group?: number | string
  rfid_code?: string
  rfid?: string
}
type EnrollmentResponse = {
  race: { name: string }
  racers: EnrollmentRacer[]
  racersById: Record<string, string>
  groups: Record<string, EnrollmentMember>
}

const props = defineProps<{ grouped: boolean }>()
const language = useAppLocale()
const id = useRoute().params.id
const { data } = await useAsyncData(`enroll-${id}`, () =>
  useApi<EnrollmentResponse>(`/api/races/select-groups/${id}`)
)
const selected = ref<number | undefined>()
const group = ref<number | undefined>()
const number = ref<number | undefined>()
const rfid = ref('')
const members = ref<Record<string, EnrollmentMember>>({})
const message = ref('')
const saving = ref(false)
watchEffect(() => {
  if (data.value?.groups)
    members.value = Object.fromEntries(
      Object.values(data.value.groups).map((item) => [`key_${item.racer_id}`, { ...item }])
    )
})
const racers = computed(() => data.value?.racers ?? [])
function removeMember(key: string | number) {
  const next = { ...members.value }
  Reflect.deleteProperty(next, key)
  members.value = next
}
function add() {
  message.value = ''
  if (!selected.value || !number.value) return
  if (rfid.value && rfid.value.length !== 10) {
    message.value = language.t('race-form', 'select-groups.rfid_validation')
    return
  }
  const key = `key_${selected.value}`
  if (
    !props.grouped &&
    Object.values(members.value).some(
      (item) =>
        Number(item.number) === Number(number.value) &&
        Number(item.racer_id) !== Number(selected.value)
    )
  ) {
    message.value = language.t('race-form', 'raceNumberFieldError')
    return
  }
  members.value[key] = {
    racer_id: selected.value,
    number: number.value,
    group: group.value,
    rfid: rfid.value
  }
  selected.value = undefined
  number.value = undefined
  rfid.value = ''
}
async function save() {
  saving.value = true
  message.value = ''
  const rows = Object.values(members.value)
  const payload: {
    racers: Record<string, unknown>
    number: Record<string, unknown>
    rfid: Record<string, unknown>
    group?: Record<string, unknown>
  } = { racers: {}, number: {}, rfid: {} }
  if (props.grouped) payload.group = {}
  for (const row of rows) {
    const key = `key_${row.racer_id}`
    payload.racers[key] = row.racer_id
    payload.number[key] = row.number
    payload.rfid[key] = row.rfid_code || row.rfid || ''
    if (props.grouped) payload.group![key] = row.group
  }
  try {
    await useApi(`/api/races/${props.grouped ? 'save-groups' : 'save-racers'}/${id}`, {
      method: 'POST',
      body: payload
    })
    await navigateTo(`/races/${id}`)
  } catch (cause: unknown) {
    const response = cause as {
      status?: number
      statusCode?: number
      data?: { message?: string; errors?: Record<string, string[]> }
    }
    const validationMessages =
      response.status === 422 || response.statusCode === 422
        ? Object.values(response.data?.errors ?? {}).flat()
        : []
    message.value =
      validationMessages.join(' ') ||
      response.data?.message ||
      'Não foi possível salvar as inscrições.'
  } finally {
    saving.value = false
  }
}
</script>
<template>
  <UContainer class="max-w-3xl py-8"
    ><UPageHeader
      :title="`${language.t('race-form', 'form.title')} · ${data?.race?.name || language.t('head', 'race.title')}`"
    /><UCard class="mt-5"
      ><div class="grid gap-3 sm:grid-cols-2">
        <USelect
          v-model="selected"
          :items="racers.map((r) => ({ label: r.name, value: r.id }))"
          value-key="value"
          label-key="label"
          :placeholder="language.t('race-form', 'select-groups.racer')"
        /><UInput
          v-if="grouped"
          v-model="group"
          type="number"
          :placeholder="language.t('race-form', 'select-groups.group')"
        /><UInput
          v-model="number"
          type="number"
          :placeholder="language.t('race-form', 'select-groups.member')"
        /><UInput v-model="rfid" :placeholder="language.t('race-form', 'select-groups.rfid')" />
      </div>
      <UAlert v-if="message" class="mt-3" color="error" :title="message" /><UButton
        class="mt-3"
        @click="add"
        >{{ language.t('race-form', 'select-groups.add') }}</UButton
      ></UCard
    >
    <ul class="my-4 divide-y divide-default rounded-lg border border-default">
      <li v-for="(item, key) in members" :key="key" class="flex items-center justify-between p-3">
        {{ data?.racersById?.[item.racer_id] }} · {{ grouped ? `Grupo ${item.group} · ` : '' }}nº
        {{ item.number
        }}<UButton
          color="error"
          variant="ghost"
          icon="i-lucide-trash"
          :aria-label="language.t('race-form', 'select-groups.delete')"
          @click="removeMember(key)"
        />
      </li>
    </ul>
    <div class="flex justify-end">
      <UButton :loading="saving" @click="save">{{
        language.t('race-form', 'select-groups.save')
      }}</UButton>
    </div></UContainer
  >
</template>
