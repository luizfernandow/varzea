<template>
    <v-list>
        <v-list-group
            v-for="(item, index) in rankingList.rank_group"
            :key="item.group"
            three-line
            no-action
        >
            <template v-slot:activator>
                <v-list-item-content>
                    <v-list-item-title>
                        <strong>{{ index + 1 }}</strong>
                        <p
                            v-for="name in getNames(
                                rankingList.groups[item.group]
                            )"
                            :key="name + '_name'"
                            class="mb-0"
                        >
                            {{ name }}
                        </p>
                    </v-list-item-title>
                    <v-list-item-subtitle
                        v-text="
                            $t('race.timeLaps', {
                                laps: item.laps,
                                time: item.time,
                            })
                        "
                    >
                    </v-list-item-subtitle>
                </v-list-item-content>
            </template>

            <div
                v-for="(itemTime, indexTime) in getRacersIdTimeLaps(
                    rankingList.groups[item.group]
                )"
                :key="indexTime + 'time'"
            >
                <v-list-item
                    v-for="(subItem, indexSub) in rankingList.time_laps[
                        itemTime
                    ]"
                    :key="'sub' + index + indexSub"
                >
                    <v-list-item-content>
                        <v-list-item-title>
                            {{ getLapCount(item.group) }}. {{ subItem }} -
                            {{
                                getName(
                                    rankingList.groups[item.group],
                                    itemTime
                                )
                            }}
                            <v-icon v-if="subItem === rankingList.best_lap.time"
                                >mdi-medal</v-icon
                            >
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </div>
            <v-divider class="mx-4"></v-divider>
        </v-list-group>
    </v-list>
</template>

<script>
export default {
    // eslint-disable-next-line vue/require-prop-types
    props: ['ranking'],
    data() {
        return {
            lapsCount: {},
        }
    },
    computed: {
        rankingList() {
            return this.ranking
        },
    },
    methods: {
        getNames(racers) {
            return racers.map((item) => item.racer.name)
        },
        getName(racers, racerId) {
            return racers
                .filter((item) => item.racer_id === racerId)
                .map((item) => item.racer.name)
                .join('')
        },
        getRacersIdTimeLaps(racers) {
            return racers.map((item) => item.racer_id)
        },
        getLapCount(group) {
            if (!this.lapsCount[group]) {
                this.lapsCount[group] = 0
            }
            return ++this.lapsCount[group]
        },
    },
}
</script>
