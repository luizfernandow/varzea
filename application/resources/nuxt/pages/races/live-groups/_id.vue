<template>
    <v-card class="mx-auto">
        <v-row align="start" no-gutters>
            <v-col cols="12">
                <v-alert dense type="info">
                    {{ $t('race.liveUpdate') }}
                </v-alert>
            </v-col>
            <v-col cols="12" sm="6">
                <RaceHeader
                    :race="race"
                    :timer-text="timerText"
                    :race-started="raceStarted"
                    :controls="false"
                />
            </v-col>
            <v-col v-if="timeStartedRace" cols="12" sm="6">
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
                    </div>
                </v-list>
            </v-col>
            <v-col v-else cols="12" sm="6">
                <v-alert border="left" color="indigo" dark>
                    {{ $t('race.liveNotStarted') }}
                </v-alert>
            </v-col>
        </v-row>
        <CoreLoadingDialog :dialog="loading" :message="loadingMessage" />
    </v-card>
</template>

<script>
import start from '../../../components/race/mixins/start'
import startGroup from '../../../components/race/mixins/start-group'

export default {
    mixins: [start, startGroup],
    asyncData({ $axios, params }) {
        return $axios
            .get(`/api/races/start-groups/${params.id}`)
            .then((res) => {
                return res.data
            })
    },
    data() {
        return {
            polling: null,
        }
    },
    async mounted() {
        this.init()
        await this.handleLive()
        this.pooling = setInterval(this.handleLive, 30000)
    },
    beforeDestroy() {
        clearInterval(this.pooling)
    },
    methods: {
        async handleLive() {
            await this.loadLive()
            if (this.timeStartedRace) {
                this.localStorageSet()
                this.load()
                this.sortPositions()
            }
        },
        async loadLive() {
            this.loadingMessage = this.$t('race.liveLoading')
            this.loading = true
            const data = await this.$axios
                .get(`/api/races/live/${this.$route.params.id}`)
                .then((res) => res.data)
            this.timeStartedRace = data.timeStartedRace
            this.racersTime = data.racersTime
            this.lapText = data.lapText
            this.groupCurrentTime = data.groupCurrentTime
            this.loading = false
        },
    },
}
</script>
