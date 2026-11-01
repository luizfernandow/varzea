<script setup lang="ts">
type TimerLap = {
  lap: number
  laps: number[]
  lapsNumber?: (number | string)[]
  totalSeconds: number
}
type RaceTimerRacer = {
  id?: number | string
  name?: string
  number: number | string
  group?: number | string
  rfid_code?: string | number
  racer?: { id: number | string; name: string }
}
type RaceTimerData = {
  race?: { name: string; date_start: string; time_start: string; type: string; laps?: number }
  racers?: Record<string, RaceTimerRacer | RaceTimerRacer[]>
}
type LiveRaceData = {
  elapsed?: number
  running?: boolean
  timeStartedRace?: string | null
  racersTime?: Record<string, TimerLap>
  lapText?: Record<string, string[]>
  groupCurrentTime?: Record<string, string>
}

const props = defineProps<{ grouped: boolean; live?: boolean }>()
const language = useAppLocale()
const id = useRoute().params.id
const { data } = await useAsyncData(
  `race-start-${id}-${props.grouped}`,
  () =>
    useApi<LiveRaceData | RaceTimerData | null>(
      `/api/races/${props.live ? 'live' : props.grouped ? 'start-groups' : 'start'}/${id}`
    ),
  { server: false }
)
const { data: roster } = await useAsyncData(
  `race-roster-${id}-${props.grouped}`,
  () =>
    props.live
      ? useApi<RaceTimerData>(`/api/races/${props.grouped ? 'start-groups' : 'start'}/${id}`)
      : Promise.resolve(null),
  { server: false }
)
const race = computed(() => (data.value as RaceTimerData | null)?.race ?? roster.value?.race)
const time = ref(0)
const running = ref(false)
const input = ref('')
const message = ref('')
const laps = ref<Record<string, TimerLap>>({})
const lapText = ref<Record<string, string[]>>({})
const groupCurrentTime = ref<Record<string, string>>({})
const liveStarted = ref(false)
const startedAt = ref<number | null>(null)
let interval: ReturnType<typeof setInterval> | undefined
const participantMap = computed(() => {
  const map: Record<string, RaceTimerRacer> = {}
  const racers = (props.live ? roster.value : (data.value as RaceTimerData | null))?.racers ?? {}
  for (const row of Object.values(racers)) {
    if (Array.isArray(row))
      for (const racer of row) {
        map[`${racer.group}${racer.number}`] = racer
        map[`${racer.rfid_code}`] = racer
      }
    else {
      map[String(row.number)] = row
      if (row.rfid_code) map[String(row.rfid_code)] = row
    }
  }
  return map
})
const names = computed(() =>
  Object.values(
    (props.live ? roster.value : (data.value as RaceTimerData | null))?.racers ?? {}
  ).flatMap((row) => (Array.isArray(row) ? row : [row]))
)
const groups = computed(() => {
  const result: Record<string, RaceTimerRacer[]> = {}
  for (const row of Object.values(roster.value?.racers ?? {})) {
    if (!Array.isArray(row)) continue
    const key = `group_${row[0]?.group}`
    if (row.length) result[key] = row
  }
  return result
})
const display = computed(() =>
  Object.entries(laps.value)
    .map(([key, value]) => ({
      key,
      value,
      name: props.grouped
        ? (groups.value[key] ?? [])
            .map(
              (racer) => `${racer.racer?.name ?? racer.name ?? ''} (${racer.group}${racer.number})`
            )
            .filter((name) => name !== ' ()')
            .join(', ') || key
        : (names.value.find((r) => String(r.racer?.id ?? r.id) === key)?.racer?.name ?? key)
    }))
    .sort((a, b) => b.value.lap - a.value.lap || a.value.totalSeconds - b.value.totalSeconds)
)
function persist() {
  if (import.meta.client)
    localStorage.setItem(
      `race-timer-${id}-${props.grouped}`,
      JSON.stringify({
        time: time.value,
        startedAt: startedAt.value,
        running: running.value,
        laps: laps.value,
        lapText: lapText.value,
        groupCurrentTime: groupCurrentTime.value
      })
    )
}
function tick() {
  time.value = startedAt.value ? Math.floor((Date.now() - startedAt.value) / 1000) : 0
}
function format(seconds: number) {
  return new Date(seconds * 1000).toISOString().slice(11, 19)
}
function start() {
  startedAt.value = Date.now() - time.value * 1000
  running.value = true
  interval = setInterval(tick, 500)
  void upload()
}
function stop() {
  running.value = false
  if (interval) clearInterval(interval)
  interval = undefined
  persist()
  void upload()
}
async function upload() {
  try {
    await useApi(`/api/races/save-live/${id}`, {
      method: 'POST',
      body: {
        timeStartedRace: startedAt.value ? new Date(startedAt.value).toISOString() : null,
        elapsed: time.value,
        running: running.value,
        racersTime: laps.value,
        lapText: lapText.value,
        groupCurrentTime: groupCurrentTime.value
      }
    })
  } catch {
    /* the local race timer remains usable offline */
  }
}
function recordLap() {
  message.value = ''
  const participant = participantMap.value[input.value.trim()]
  if (!participant) {
    message.value = language.t('race', 'doLapFieldError')
    return
  }
  const key = props.grouped
    ? `group_${participant.group}`
    : String(participant.racer?.id ?? participant.id)
  const racerId = participant.racer?.id ?? participant.id
  if (racerId === undefined) {
    message.value = language.t('race', 'doLapFieldError')
    return
  }
  const current = laps.value[key] ?? {
    lap: 0,
    laps: [],
    ...(props.grouped ? { lapsNumber: [] } : {}),
    totalSeconds: 0
  }
  if (race.value?.type !== 'hours' && current.lap >= Number(race.value?.laps ?? 0)) {
    message.value = language.t('race', 'doLapFieldError')
    return
  }
  const previous = current.laps.reduce((sum, value) => sum + value, 0)
  const lapDuration = time.value - previous
  if (lapDuration <= 480) {
    message.value = language.t('race', 'doLapFieldTimeError')
    return
  }
  current.lap++
  current.laps.push(lapDuration)
  current.totalSeconds = time.value
  const lapTime = format(lapDuration)
  const numberText = props.grouped ? `${participant.group}${participant.number}` : ''
  const text = props.grouped
    ? `${current.lap} - ${lapTime} (${numberText})`
    : `${current.lap} - ${lapTime}`
  lapText.value = { ...lapText.value, [key]: [...(lapText.value[key] ?? []), text] }
  groupCurrentTime.value = { ...groupCurrentTime.value, [key]: format(time.value) }
  if (props.grouped) current.lapsNumber?.push(racerId)
  laps.value = { ...laps.value, [key]: current }
  input.value = ''
  persist()
  void upload()
}
async function finish() {
  if (
    !window.confirm(
      `${language.t('race', 'saveDialog.title')} ${language.t('race', 'saveDialog.content')}`
    )
  )
    return
  const endpoint = props.grouped ? 'save-laps-groups' : 'save-laps'
  await useApi(`/api/races/${endpoint}/${id}`, { method: 'POST', body: laps.value })
  if (import.meta.client) localStorage.removeItem(`race-timer-${id}-${props.grouped}`)
  await navigateTo(`/races/${id}`)
}
function undo(key: string) {
  const current = laps.value[key]
  if (!current?.lap || !window.confirm(language.t('race', 'undoLapDialog.title'))) return
  const lastLap = current.laps.pop() ?? 0
  current.lap--
  current.totalSeconds = Math.max(0, current.totalSeconds - lastLap)
  if (props.grouped) current.lapsNumber?.pop()
  lapText.value = {
    ...lapText.value,
    [key]: (lapText.value[key] ?? []).slice(0, -1)
  }
  groupCurrentTime.value = {
    ...groupCurrentTime.value,
    [key]: current.totalSeconds ? format(current.totalSeconds) : ''
  }
  laps.value = { ...laps.value, [key]: current }
  persist()
  void upload()
}
function reset() {
  if (
    !window.confirm(
      `${language.t('race', 'resetDialog.title')} ${language.t('race', 'resetDialog.content')}`
    )
  )
    return
  stop()
  time.value = 0
  startedAt.value = null
  laps.value = {}
  lapText.value = {}
  groupCurrentTime.value = {}
  persist()
  void upload()
}
onMounted(() => {
  if (props.live) return
  const saved = localStorage.getItem(`race-timer-${id}-${props.grouped}`)
  if (saved) {
    try {
      const state = JSON.parse(saved) as {
        time?: number
        startedAt?: number | null
        running?: boolean
        laps?: Record<string, TimerLap>
        lapText?: Record<string, string[]>
        groupCurrentTime?: Record<string, string>
      }
      time.value = state.time ?? 0
      startedAt.value = state.startedAt ?? null
      laps.value = state.laps ?? {}
      lapText.value = state.lapText ?? {}
      groupCurrentTime.value = state.groupCurrentTime ?? {}
      if (state.running && startedAt.value) {
        tick()
        running.value = true
        interval = setInterval(tick, 500)
      }
    } catch {
      /* ignore invalid local state */
    }
  }
  if (!saved) {
    const oldStart = localStorage.getItem(`timeStartedRace${id}`)
    const oldLaps = localStorage.getItem(`racersTime${id}`)
    if (oldStart || oldLaps) {
      try {
        const oldStartValue = oldStart ? (JSON.parse(oldStart) as string | null) : null
        startedAt.value = oldStartValue ? new Date(oldStartValue).getTime() : null
        if (startedAt.value) {
          time.value = Math.floor((Date.now() - startedAt.value) / 1000)
          running.value = true
          interval = setInterval(tick, 500)
        }
        laps.value = oldLaps ? JSON.parse(oldLaps) : {}
        lapText.value = JSON.parse(localStorage.getItem(`lapText${id}`) || '{}')
        groupCurrentTime.value = JSON.parse(localStorage.getItem(`groupCurrentTime${id}`) || '{}')
        persist()
      } catch {
        /* ignore invalid state from the previous Nuxt app */
      }
    }
  }
  if (!Object.keys(laps.value).length)
    for (const row of Object.values((data.value as RaceTimerData | null)?.racers ?? {})) {
      if (Array.isArray(row)) {
        const key = `group_${row[0]?.group}`
        laps.value[key] = { lap: 0, laps: [], lapsNumber: [], totalSeconds: 0 }
      } else {
        const key = String(row.racer?.id ?? row.id)
        laps.value[key] = { lap: 0, laps: [], totalSeconds: 0 }
      }
    }
})
onBeforeUnmount(() => {
  if (interval) clearInterval(interval)
})
if (props.live)
  onMounted(() => {
    const applyLiveData = (live: LiveRaceData | null | undefined) => {
      if (!live) return
      const elapsed = Number(live.elapsed ?? 0)
      liveStarted.value = !!live.timeStartedRace
      time.value =
        (live.running ?? !!live.timeStartedRace) && live.timeStartedRace
          ? elapsed + Math.floor((Date.now() - new Date(live.timeStartedRace).getTime()) / 1000)
          : elapsed
      laps.value = live.racersTime ?? {}
      lapText.value = live.lapText ?? {}
      groupCurrentTime.value = live.groupCurrentTime ?? {}
    }
    applyLiveData(data.value as LiveRaceData | null)
    interval = setInterval(async () => {
      try {
        const live = await useApi<LiveRaceData | null>(`/api/races/live/${id}`)
        applyLiveData(live)
      } catch {
        /* retry next cycle */
      }
    }, 3000)
  })
</script>
<template>
  <UContainer class="py-8"
    ><UPageHeader
      :title="
        race?.name || (live ? language.t('race', 'liveWatch') : language.t('head', 'race.title'))
      "
      :description="`${race?.date_start || ''} ${race?.time_start || ''}`"
    /><UAlert
      v-if="live"
      class="mt-4"
      :color="liveStarted ? 'info' : 'warning'"
      :title="language.t('race', liveStarted ? 'liveUpdate' : 'liveNotStarted')"
    /><UCard class="my-5"
      ><div class="text-center text-4xl font-mono">{{ format(time) }}</div>
      <div v-if="!live" class="my-4 flex flex-wrap justify-center gap-2">
        <UButton v-if="!running" @click="start">{{ language.t('race', 'start') }}</UButton
        ><UButton v-else color="neutral" @click="stop">{{ language.t('race', 'stop') }}</UButton
        ><UButton color="error" variant="soft" @click="reset">{{
          language.t('race', 'reset')
        }}</UButton>
      </div>
      <form v-if="!live" class="mx-auto flex max-w-md gap-2" @submit.prevent="recordLap">
        <UInput
          v-model="input"
          :placeholder="language.t('race', 'doLapField')"
          class="flex-1"
        /><UButton type="submit" :disabled="!running">{{
          language.t('race', 'recordLap')
        }}</UButton>
      </form>
      <p v-if="message" class="mt-2 text-center text-error">{{ message }}</p></UCard
    >
    <div class="space-y-3">
      <UCard v-for="(row, index) in display" :key="row.key"
        ><div class="flex justify-between">
          <span>{{ index + 1 }}. {{ row.name }}</span
          ><span>{{ row.value.lap }} voltas · {{ format(row.value.totalSeconds) }}</span>
        </div>
        <div class="mt-2 text-sm text-muted">
          {{ lapText[row.key]?.join(' · ') || row.value.laps.map(format).join(' · ') }}
          <span v-if="groupCurrentTime[row.key]" class="ml-2">
            · {{ groupCurrentTime[row.key] }}
          </span>
        </div>
        <UButton
          v-if="!live && row.value.lap"
          class="mt-2"
          size="xs"
          variant="ghost"
          icon="i-lucide-undo-2"
          @click="undo(row.key)"
          >{{ language.t('race', 'undoLap') }}</UButton
        ></UCard
      >
    </div>
    <div v-if="!live" class="mt-5 flex justify-end">
      <UButton color="primary" @click="finish">{{ language.t('race', 'save') }}</UButton>
    </div></UContainer
  >
</template>
