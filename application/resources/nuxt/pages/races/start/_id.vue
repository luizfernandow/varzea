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
import { Timer } from 'easytimer.js'
const timer = new Timer()

export default {
    middleware: 'auth',
    validate({ params }) {
        return !isNaN(+params.id)
    },
    asyncData({ $axios, params }) {
        return $axios
            .get(`/api/races/start-groups/${params.id}`)
            .then((res) => {
                return res.data
            })
    },
    data() {
        return {
            race: null,
            racers: {},
            racersPositions: [],
            timerText: '-',
            timeStartedRace: null,
            raceStarted: false,
            resetDialog: false,
            saveDialog: false,
            undoDialog: false,
            undoGroup: null,
            lapNumber: null,
            lapNumberErrorMessage: null,
            lapSaving: false,
            racersTime: {},
            racersByNumber: {},
            lapText: {},
            groupCurrentTime: {},
            loading: false,
            loadingMessage: '',
        }
    },
    computed: {
        storageTimeKey() {
            return `timeStartedRace${this.$route.params.id}`
        },
        storageRacersTimeKey() {
            return `racersTime${this.$route.params.id}`
        },
        storageLapTextKey() {
            return `lapText${this.$route.params.id}`
        },
        storageGroupCurrentTimeKey() {
            return `groupCurrentTime${this.$route.params.id}`
        },
    },
    mounted() {
        this.init()

        const timeStartedRaceStorage = localStorage.getItem(this.storageTimeKey)
        if (timeStartedRaceStorage) {
            this.timeStartedRace = JSON.parse(timeStartedRaceStorage)
            const startTime = new Date(this.timeStartedRace)
            const endTime = new Date()
            let timeDiff = endTime - startTime // in ms
            // strip the ms
            timeDiff /= 1000

            // get seconds
            const seconds = Math.round(timeDiff)
            timer.start({
                precision: 'seconds',
                startValues: { seconds },
            })
            this.timerText = timer.getTimeValues().toString()
        }
        const racersTimeStorage = localStorage.getItem(
            this.storageRacersTimeKey
        )
        if (racersTimeStorage) {
            this.racersTime = JSON.parse(racersTimeStorage)
            this.raceStarted = true
        }
        const lapTextStorage = localStorage.getItem(this.storageLapTextKey)
        if (lapTextStorage) {
            this.lapText = JSON.parse(lapTextStorage)
        }

        const groupCurrentTimeStorage = localStorage.getItem(
            this.storageGroupCurrentTimeKey
        )
        if (groupCurrentTimeStorage) {
            this.groupCurrentTime = JSON.parse(groupCurrentTimeStorage)
        }

        this.sortPositions()

        const self = this
        timer.addEventListener('secondsUpdated', function (e) {
            self.timerText = timer.getTimeValues().toString()
        })
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
                    lapsNumber: [],
                    totalSeconds: 0,
                }
                this.lapText[groupNumber] = []
                this.racersPositions.push({
                    groupNumber,
                    group,
                })
            }
        },
        getGroupId(items) {
            return this.getGroupIdKey(items[0].group)
        },
        getGroupIdKey(groupId) {
            return `group_${groupId}`
        },
        startTimer() {
            timer.start()
            this.raceStarted = true
            if (!this.timeStartedRace) {
                this.timeStartedRace = new Date()
                localStorage.setItem(
                    this.storageTimeKey,
                    JSON.stringify(this.timeStartedRace)
                )
            }
        },
        stop() {
            this.raceStarted = false
            timer.pause()
        },
        doLap() {
            this.lapSaving = true
            const racer = this.racersByNumber[this.lapNumber]
            this.lapNumberErrorMessage = null
            if (!racer) {
                this.lapNumberErrorMessage = this.$t('race.doLapFieldError')
            }
            if (timer.isRunning() && racer) {
                const groupNumber = this.getGroupIdKey(racer.group)
                const racerTimer = this.racersTime[groupNumber]

                const currentTime = timer.getTimeValues().toString()
                const totalSeconds = timer.getTotalTimeValues().seconds

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
                racerTimer.lapsNumber.push(racer.racer.id)
                racerTimer.lap++
                racerTimer.totalSeconds = totalSeconds
                this.lapText[groupNumber].push(
                    `${racerTimer.lap} - ${time} (${this.lapNumber})`
                )
                this.groupCurrentTime[groupNumber] = currentTime
                this.localStorageSet()
                this.lapNumber = null
                this.sortPositions()
            }
            this.lapSaving = false
        },
        localStorageSet() {
            localStorage.setItem(
                this.storageRacersTimeKey,
                JSON.stringify(this.racersTime)
            )
            localStorage.setItem(
                this.storageLapTextKey,
                JSON.stringify(this.lapText)
            )
            localStorage.setItem(
                this.storageGroupCurrentTimeKey,
                JSON.stringify(this.groupCurrentTime)
            )
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
                    racerTimer.lapsNumber.pop()
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
                    `/api/races/save-laps-groups/${this.$route.params.id}`,
                    this.racersTime
                )
                .then((res) => {
                    this.loading = false
                    this.$router.push(`/races/${this.$route.params.id}`)
                })
        },
        handleReset(reset) {
            this.resetDialog = false
            if (reset) {
                localStorage.clear(this.storageTimeKey)
                localStorage.clear(this.storageRacersTimeKey)
                localStorage.clear(this.storageLapTextKey)
                localStorage.clear(this.storageGroupCurrentTimeKey)
                this.raceStarted = false
                this.timerText = '-'
                this.lapText = {}
                this.groupCurrentTime = {}
                this.init()
                timer.stop()
            }
        },
    },
}
</script>
