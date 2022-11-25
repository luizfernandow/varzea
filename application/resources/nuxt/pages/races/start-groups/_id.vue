<template>
    <v-card class="mx-auto">
        <v-card-title>
            <p class="ma-0 mr-auto">{{ race.name }}</p>
        </v-card-title>
        <p class="d-flex justify-center mb-6 title">{{ timerText }}</p>
        <v-btn color="primary" :disabled="raceStarted" @click="startTimer">{{
            $t('race.start')
        }}</v-btn>
        <v-btn color="red" @click="resetDialog = true">{{
            $t('race.reset')
        }}</v-btn>
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
            racers: [],
            timerText: '-',
            timeStartedRace: null,
            raceStarted: false,
            resetDialog: false,
        }
    },
    mounted() {
        const self = this
        const timeStartedRaceStorage = localStorage.getItem(
            `timeStartedRace${this.$route.params.id}`
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
            timer.start({
                precision: 'seconds',
                startValues: { seconds },
            })
            this.timerText = timer.getTimeValues().toString()
            this.raceStarted = true
        }
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
                    `timeStartedRace${this.$route.params.id}`,
                    JSON.stringify(this.timeStartedRace)
                )
            }
        },
        handleReset(reset) {
            this.resetDialog = false
            if (reset) {
                alert('ioa')
            }
        },
    },
}
</script>
