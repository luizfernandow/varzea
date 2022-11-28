<template>
    <v-list>
        <v-list-group
            v-for="(item, index) in rankingList.rank_group"
            :key="item.group"
            three-line
            no-action
        >
            <template v-slot:activator>
                <v-list-item-avatar>
                    <v-img src="/images/biker.png"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title
                        >{{ index + 1 }} -
                        {{
                            getNames(rankingList.groups[item.group])
                        }}</v-list-item-title
                    >
                    <v-list-item-subtitle v-text="item.time">
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
                            {{ indexTime + indexSub + 1 }}. {{ subItem }} -
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
    computed: {
        rankingList() {
            return this.ranking
        },
    },
    methods: {
        getNames(racers) {
            return racers.map((item) => item.racer.name).join(', ')
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
    },
}
</script>
