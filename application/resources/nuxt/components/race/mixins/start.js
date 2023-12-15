import { Timer } from 'easytimer.js'

export default {
    validate({ params }) {
        return !isNaN(+params.id)
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
    data() {
        return {
            timer: new Timer(),
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
            racersByRfid: {},
            lapText: {},
            groupCurrentTime: {},
            loading: false,
            loadingMessage: '',
            snackbar: false,
            snackbarText: '',
        }
    },
    methods: {
        load() {
            const timeStartedRaceStorage = localStorage.getItem(
                this.storageTimeKey
            )
            if (timeStartedRaceStorage) {
                this.timeStartedRace = JSON.parse(timeStartedRaceStorage)
                const startTime = new Date(this.timeStartedRace)
                const endTime = new Date()
                let timeDiff = endTime - startTime // in ms
                // strip the ms
                timeDiff /= 1000

                // get seconds
                const seconds = Math.round(timeDiff)
                this.timer.start({
                    precision: 'seconds',
                    startValues: { seconds },
                })
                this.timerText = this.timer.getTimeValues().toString()
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

            const self = this
            this.timer.addEventListener('secondsUpdated', function (e) {
                self.timerText = self.timer.getTimeValues().toString()
            })
        },
        startTimer() {
            this.timer.start()
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
            this.timer.pause()
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
                this.timer.stop()
            }
        },
    },
}
