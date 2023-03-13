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
            :race-started="raceStarted"
            :lap-number="lapNumber"
            :lap-number-error-message="lapNumberErrorMessage"
            :lap-saving="lapSaving"
            @doLap="doLap"
            @lapNumberUpdate="lapNumber = $event"
        />
        <RaceReset :dialog="resetDialog" @resetRace="handleReset" />
        <RaceSave :dialog="saveDialog" @saveRace="handleSave" />
        <RaceUndoLap :dialog="undoDialog" @undoLap="handleUndoLap" />
        <CoreLoadingDialog :dialog="loading" :message="loadingMessage" />
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
        return $axios.get(`/api/races/${params.id}`).then((res) => {
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
                const timerA = self.racersTime[a.groupNumber]
                const timerB = self.racersTime[b.groupNumber]
                if (timerA.lap === timerB.lap) {
                    return timerA.totalSeconds - timerB.totalSeconds
                }
                return timerB.lap - timerA.lap
            })
            this.racersPositions = sortable
        },
        init() {
            this.racersPositions = []
            for (const group of Object.values(this.racers)) {
                for (const racer of group) {
                    this.racersByNumber[`${racer.group}${racer.number}`] = racer
                }
                const groupNumber = this.getGroupIdKey(group[0].group)
                this.racersTime[groupNumber] = {
                    lap: 0,
                    laps: [],
                    totalSeconds: 0,
                }
                this.lapText[groupNumber] = []
                this.racersPositions.push({
                    groupNumber,
                    group,
                })
            }
        },
        doLap() {
            this.lapSaving = true
            const racer = this.racersByNumber[this.lapNumber]
            this.lapNumberErrorMessage = null
            if (!racer) {
                this.lapNumberErrorMessage = this.$t('race.doLapFieldError')
            }
            if (this.timer.isRunning() && racer) {
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
                racerTimer.laps.push(lapTime)
                racerTimer.lap++
                racerTimer.totalSeconds = totalSeconds
                this.lapText[racerId].push(`${racerTimer.lap} - ${time}`)
                this.groupCurrentTime[racerId] = currentTime
                this.localStorageSet()
                this.lapNumber = null
                this.sortPositions()
            }
            this.lapSaving = false
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
            // this.$axios
            //     .post(
            //         `/api/races/save-laps-groups/${this.$route.params.id}`,
            //         this.racersTime
            //     )
            //     .then((res) => {
            //         this.loading = false
            //         this.$router.push(`/races/${this.$route.params.id}`)
            //     })
        },
    },
}
</script>
