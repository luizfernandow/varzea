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
        <v-list>
            <div v-for="(items, index) in racersPositions" :key="index">
                <v-divider></v-divider>
                <v-list-item :three-line="true">
                    <v-list-item-content>
                        {{ index + 1 }}
                        <div
                            v-for="item in items.group"
                            :key="item.group + item.number"
                        >
                            <div>
                                {{ item.racer.name }} ({{
                                    item.group
                                        .toString()
                                        .concat(item.number.toString())
                                }})
                            </div>
                        </div>
                        <h3>{{ groupCurrentTime[getGroupId(items.group)] }}</h3>
                    </v-list-item-content>
                    <v-list-item-content>
                        <v-alert
                            v-for="lap in lapText[getGroupId(items.group)]"
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
                        @click="verifyUndo(getGroupId(items.group))"
                        ><v-icon>mdi-undo</v-icon></v-btn
                    >
                </div>
            </div>
        </v-list>
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
    asyncData({ $axios, params }) {
        return $axios
            .get(`/api/races/start-groups/${params.id}`)
            .then((res) => {
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
        doLap() {
            this.lapSaving = true
            const racer = this.racersByNumber[this.lapNumber]
            this.lapNumberErrorMessage = null
            if (!racer) {
                this.lapNumberErrorMessage = this.$t('race.doLapFieldError')
            }
            if (this.timer.isRunning() && racer) {
                const groupNumber = this.getGroupIdKey(racer.group)
                const racerTimer = this.racersTime[groupNumber]

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
    },
}
</script>
