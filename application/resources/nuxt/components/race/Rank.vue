<template>
    <v-list>
        <v-list-group
            v-for="(item, index) in rankingList.rank"
            :key="item.racer.id"
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
                        {{ item.racer.name }}</v-list-item-title
                    >
                    <v-list-item-subtitle v-text="item.time">
                    </v-list-item-subtitle>
                    <v-list-item-subtitle
                        v-text="$t('race.points', { points: item.points })"
                    ></v-list-item-subtitle>
                </v-list-item-content>
            </template>
            <v-list-item
                v-for="(subItem, indexSub) in rankingList.time_laps[
                    item.racer.id
                ]"
                :key="'sub' + index + indexSub"
            >
                <v-list-item-content>
                    <v-list-item-title>
                        {{ indexSub + 1 }}. {{ subItem }}
                        <v-icon v-if="subItem === rankingList.best_lap.time"
                            >mdi-medal</v-icon
                        >
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
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
}
</script>
