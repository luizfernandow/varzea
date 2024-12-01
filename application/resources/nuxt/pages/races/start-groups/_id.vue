<template>
    <v-card class="mx-auto">
        <v-row align="start" no-gutters>
            <v-col cols="12" sm="6">
                <RaceHeader
                    :race="race"
                    :timer-text="timerText"
                    :race-started="raceStarted"
                    :controls="true"
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
            </v-col>
            <v-col cols="12" sm="6">
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
                                <h3>
                                    {{
                                        groupCurrentTime[
                                            getGroupId(items.group)
                                        ]
                                    }}
                                </h3>
                            </v-list-item-content>
                            <v-list-item-content>
                                <v-alert
                                    v-for="lap in lapText[
                                        getGroupId(items.group)
                                    ]"
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
            </v-col>
        </v-row>
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
import startGroup from '../../../components/race/mixins/start-group'

export default {
    mixins: [start, startGroup],
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
        doLap() {
            this.lapSaving = true

            const lapNumber = `${this.lapNumber}`.trim()

            const racer =
                this.racersByNumber[lapNumber] || this.racersByRfid[lapNumber]
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
                if (lapTime > 60 * 8) {
                    racerTimer.laps.push(lapTime)
                    racerTimer.lapsNumber.push(racer.racer.id)
                    racerTimer.lap++
                    racerTimer.totalSeconds = totalSeconds
                    const numberText = racer.group
                        .toString()
                        .concat(racer.number.toString())
                    this.lapText[groupNumber].push(
                        `${racerTimer.lap} - ${time} (${numberText})`
                    )
                    this.groupCurrentTime[groupNumber] = currentTime
                    this.localStorageSet()
                    this.sortPositions()
                    this.uploadLive()
                    const snackbarText = `${racer.racer.name} (${numberText}) - ${time}`
                    this.snackbarText = this.snackbar
                        ? `${this.snackbarText} \n ${snackbarText}`
                        : snackbarText
                    this.snackbar = true
                } else {
                    this.lapNumberErrorMessage = this.$t(
                        'race.doLapFieldTimeError'
                    )
                }
            }
            this.lapNumber = null
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
                    racerTimer.lapsNumber.pop()
                    racerTimer.lap--
                    racerTimer.totalSeconds = racerTimer.totalSeconds - lapTime
                    this.lapText[this.undoGroup].pop()
                    this.groupCurrentTime[this.undoGroup] = this.$toHHMMSS(
                        racerTimer.totalSeconds.toString()
                    )
                    this.localStorageSet()
                    this.sortPositions()
                    this.uploadLive()
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
