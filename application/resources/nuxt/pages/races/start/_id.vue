<template>
    <v-card class="mx-auto">
        <RaceHeader
            :race="race"
            :timer-text="timerText"
            :race-started="raceStarted"
            @startTimer="startTimer"
            @stop="stop"
            @saveDialog="saveDialog = true"
            @resetDialog="resetDialog = true"
        />
        <RaceLapInput
            ref="lapInput"
            :race-started="raceStarted"
            :lap-number="lapNumber"
            :lap-number-error-message="lapNumberErrorMessage"
            :lap-saving="lapSaving"
            @doLap="doLap"
            @lapNumberUpdate="lapNumber = $event"
        />
        <v-list>
            <div v-for="(item, index) in racersPositions" :key="index">
                <v-divider></v-divider>
                <v-list-item :three-line="true">
                    <v-list-item-content>
                        {{ index + 1 }}

                        <div>
                            {{ item.racer.name }} ({{ item.number.toString() }})
                        </div>
                        <h3>{{ groupCurrentTime[item.racer.id] }}</h3>
                    </v-list-item-content>
                    <v-list-item-content>
                        <v-alert
                            v-for="lap in lapText[item.racer.id]"
                            :key="lap"
                            dense
                            text
                            type="success"
                            border="left"
                            :icon="false"
                        >
                            {{ lap }}
                        </v-alert>
                    </v-list-item-content>
                </v-list-item>
                <div class="d-flex">
                    <v-spacer></v-spacer>
                    <v-btn
                        class="ml-auto mr-4 mb-2"
                        fab
                        dark
                        small
                        color="blue-grey"
                        @click="verifyUndo(item.racer.id)"
                        ><v-icon>mdi-undo</v-icon></v-btn
                    >
                </div>
            </div>
        </v-list>
        <RaceReset :dialog="resetDialog" @resetRace="handleReset" />
        <RaceSave :dialog="saveDialog" @saveRace="handleSave" />
        <RaceUndoLap :dialog="undoDialog" @undoLap="handleUndoLap" />
        <CoreLoadingDialog :dialog="loading" :message="loadingMessage" />
        <RaceSnackbar
            :snackbar="snackbar"
            :snackbar-text="snackbarText"
            @snackbarUpate="snackbar = $event"
        />
    </v-card>
</template>

<script>
import start from '../../../components/race/mixins/start'

export default {
    mixins: [start],
    middleware: 'auth',
    validate({ params }) {
        return !isNaN(+params.id)
    },
    asyncData({ $axios, params }) {
        return $axios.get(`/api/races/start/${params.id}`).then((res) => {
            return res.data
        })
    },
    mounted() {
        this.init()
        this.load()
        this.sortPositions()
    },
    methods: {
        sortPositions() {
            const sortable = this.racersPositions
            const self = this
            sortable.sort((a, b) => {
                const timerA = self.racersTime[a.racer.id]
                const timerB = self.racersTime[b.racer.id]
                if (timerA.lap === timerB.lap) {
                    return timerA.totalSeconds - timerB.totalSeconds
                }
                return timerB.lap - timerA.lap
            })
            this.racersPositions = sortable
        },
        init() {
            this.racersPositions = []
            for (const racer of Object.values(this.racers)) {
                this.racersByNumber[`${racer.number}`] = racer
                this.racersTime[racer.racer.id] = {
                    lap: 0,
                    laps: [],
                    totalSeconds: 0,
                }
                this.lapText[racer.racer.id] = []
                this.racersPositions.push(racer)
            }
        },
        doLap() {
            this.lapSaving = true
            const racer = this.racersByNumber[this.lapNumber]
            this.lapNumberErrorMessage = null
            if (!racer) {
                this.lapNumberErrorMessage = this.$t('race.doLapFieldError')
            }
            if (
                this.timer.isRunning() &&
                racer &&
                this.racersTime[racer.racer.id].lap !== this.race.laps
            ) {
                const racerId = racer.racer.id
                const racerTimer = this.racersTime[racerId]

                const currentTime = this.timer.getTimeValues().toString()
                const totalSeconds = this.timer.getTotalTimeValues().seconds

                let lapTime = 0
                let time = ''
                if (!racerTimer.lap) {
                    time = currentTime
                    lapTime = totalSeconds
                } else {
                    lapTime = totalSeconds
                    racerTimer.laps.forEach(function (item) {
                        lapTime -= item
                    })
                    time = this.$toHHMMSS(lapTime.toString())
                }
                if (lapTime > 60 * 8) {
                    racerTimer.laps.push(lapTime)
                    racerTimer.lap++
                    racerTimer.totalSeconds = totalSeconds
                    this.lapText[racerId].push(`${racerTimer.lap} - ${time}`)
                    this.groupCurrentTime[racerId] = currentTime
                    this.localStorageSet()
                    this.sortPositions()
                    const snackbarText = `${racer.racer.name} - ${time}`
                    this.snackbarText = this.snackbar
                        ? `${this.snackbarText} \n ${snackbarText}`
                        : snackbarText
                    this.snackbar = true
                } else {
                    this.lapNumberErrorMessage = this.$t(
                        'race.doLapFieldTimeError'
                    )
                }

                this.lapNumber = null
            }
            this.lapSaving = false
            this.$refs.lapInput.focus()
        },
        verifyUndo(group) {
            this.undoGroup = group
            this.undoDialog = true
        },
        handleUndoLap(undo) {
            this.undoDialog = false
            if (undo) {
                const racerTimer = this.racersTime[this.undoGroup]
                if (racerTimer.lap > 0) {
                    const lapTime = racerTimer.laps.pop()
                    racerTimer.lap--
                    racerTimer.totalSeconds = racerTimer.totalSeconds - lapTime
                    this.lapText[this.undoGroup].pop()
                    this.groupCurrentTime[this.undoGroup] = this.$toHHMMSS(
                        racerTimer.totalSeconds.toString()
                    )
                    this.localStorageSet()
                    this.sortPositions()
                }
            }
            this.undoGroup = null
        },
        handleSave() {
            this.saveDialog = false
            this.loadingMessage = this.$t('race.saving')
            this.loading = true
            this.$axios
                .post(
                    `/api/races/save-laps/${this.$route.params.id}`,
                    this.racersTime
                )
                .then((res) => {
                    this.loading = false
                    this.$router.push(`/races/${this.$route.params.id}`)
                })
        },
    },
}
</script>
