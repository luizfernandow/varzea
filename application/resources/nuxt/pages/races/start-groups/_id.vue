<template>
    <v-card class="mx-auto">
        <v-card-title>
            <p class="ma-0 mr-auto">{{ race.name }}</p>
        </v-card-title>
        <p class="d-flex justify-center mb-6 title">{{ timerText }}</p>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                :disabled="raceStarted"
                @click="startTimer"
                >{{ $t('race.start') }}</v-btn
            >
            <v-btn color="secondary" :disabled="!raceStarted" @click="stop">{{
                $t('race.stop')
            }}</v-btn>
            <v-btn
                color="green"
                :disabled="raceStarted"
                @click="saveDialog = true"
                >{{ $t('race.save') }}</v-btn
            >
            <v-btn
                color="red"
                :disabled="raceStarted"
                @click="resetDialog = true"
                >{{ $t('race.reset') }}</v-btn
            >
        </v-card-actions>
        <v-card-text class="mt-10"
            ><v-row>
                <v-col cols="12">
                    <v-text-field
                        v-model="lapNumber"
                        append-outer-icon="mdi-send"
                        filled
                        :disabled="!raceStarted || lapSaving"
                        :label="$t('race.doLapField')"
                        type="number"
                        :hint="lapNumberHint"
                        persistent-hint
                        @click:append-outer="doLap"
                    ></v-text-field>
                </v-col>
            </v-row>
        </v-card-text>
        <v-list>
            <v-list-item
                v-for="(items, index) in racers"
                :key="index"
                :three-line="true"
            >
                <v-list-item-content>
                    {{ index }}
                    <div v-for="item in items" :key="item.group + item.number">
                        <div>{{ item.racer.name }}</div>
                    </div>
                </v-list-item-content>
            </v-list-item>
        </v-list>
        <RaceReset :dialog="resetDialog" @resetRace="handleReset" />
        <RaceSave :dialog="saveDialog" @saveRace="handleSave" />
    </v-card>
</template>

<script>
import { Timer } from 'easytimer.js'
const toHHMMSS = function (string) {
    const secNum = parseInt(string, 10) // don't forget the second param
    let hours = Math.floor(secNum / 3600)
    let minutes = Math.floor((secNum - hours * 3600) / 60)
    let seconds = secNum - hours * 3600 - minutes * 60

    if (hours < 10) {
        hours = '0' + hours
    }
    if (minutes < 10) {
        minutes = '0' + minutes
    }
    if (seconds < 10) {
        seconds = '0' + seconds
    }
    return hours + ':' + minutes + ':' + seconds
}
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
            racers: [],
            timerText: '-',
            timeStartedRace: null,
            raceStarted: false,
            resetDialog: false,
            saveDialog: false,
            lapNumber: null,
            lapNumberHint: null,
            lapSaving: false,
            racersTime: {},
            racersByNumber: {},
            lapText: {},
        }
    },
    computed: {
        storageTimeKey() {
            return `timeStartedRace${this.$route.params.id}`
        },
        storageRacersTimeKey() {
            return `racersTime${this.$route.params.id}`
        },
    },
    mounted() {
        for (const group of Object.values(this.racers)) {
            for (const racer of group) {
                this.racersByNumber[`${racer.group}${racer.number}`] = racer
            }
            const groupNumber = group[0].group
            this.racersTime[groupNumber] = {
                lap: 0,
                laps: [],
                lapsNumber: [],
                totalSeconds: 0,
            }
            this.lapText[groupNumber] = []
        }
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

        const self = this
        timer.addEventListener('secondsUpdated', function (e) {
            self.timerText = timer.getTimeValues().toString()
        })
    },
    methods: {
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
            this.lapNumberHint = null
            if (!racer) {
                this.lapNumberHint = this.$t('race.doLapFieldError')
            }
            if (timer.isRunning() && racer) {
                const groupNumber = racer.group
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
                    time = toHHMMSS(lapTime.toString())
                }
                this.lapText[groupNumber].push(time)
                racerTimer.laps.push(lapTime)
                racerTimer.lapsNumber.push(parseInt(this.lapNumber))
                racerTimer.lap++
                localStorage.setItem(
                    this.storageRacersTimeKey,
                    JSON.stringify(this.racersTime)
                )
                this.lapNumber = null
            }
            this.lapSaving = false
        },
        handleSave() {
            this.saveDialog = false
        },
        handleReset(reset) {
            this.resetDialog = false
            if (reset) {
                localStorage.clear(this.storageTimeKey)
                localStorage.clear(this.storageRacersTimeKey)
                this.raceStarted = false
                this.timerText = '-'
                timer.stop()
            }
        },
    },
}
</script>
