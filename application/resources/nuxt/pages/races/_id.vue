<template>
    <v-card class="mx-auto">
        <v-card-title>
            <p class="ma-0 mr-auto">{{ race.name }}</p>
            <v-btn
                v-if="authenticated && !race.locked"
                class="mx-2"
                fab
                small
                dark
                color="indigo"
                :to="{
                    name: 'races-edit-id',
                    params: { id: race.id },
                }"
            >
                <v-icon dark> mdi-pencil </v-icon>
            </v-btn>
            <v-btn
                v-if="authenticated && !race.locked"
                class="mx-2"
                fab
                small
                dark
                color="secondary"
                :to="{
                    name: `races-select-${race.type == 'hours' && 'groups-'}id`,
                    params: { id: race.id },
                }"
            >
                <v-icon dark> mdi-account-group </v-icon>
            </v-btn>
            <v-btn
                v-if="authenticated && !race.locked"
                class="mx-2"
                fab
                small
                dark
                color="teal"
                :to="{
                    name: `races-start-${race.type == 'hours' && 'groups-'}id`,
                    params: { id: race.id },
                }"
            >
                <v-icon dark> mdi-flag-checkered </v-icon>
            </v-btn>
        </v-card-title>
        <v-card-text>
            {{ race.date_start }} - {{ race.time_start }}
            <div class="my-4 subtitle-1">
                {{ $t('race.best_lap') }} <v-icon>mdi-medal</v-icon>
            </div>
            {{ ranking.best_lap?.racer.name }} - {{ ranking.best_lap?.time }}
        </v-card-text>
        <v-divider class="mx-4"></v-divider>
        <v-list>
            <v-list-group
                v-for="(item, index) in ranking.rank"
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
                    v-for="(subItem, indexSub) in ranking.time_laps[
                        item.racer.id
                    ]"
                    :key="'sub' + index + indexSub"
                >
                    <v-list-item-content>
                        <v-list-item-title>
                            {{ indexSub + 1 }}. {{ subItem }}
                            <v-icon v-if="subItem === ranking.best_lap.time"
                                >mdi-medal</v-icon
                            >
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
                <v-divider class="mx-4"></v-divider>
            </v-list-group>
        </v-list>
    </v-card>
</template>

<script>
export default {
    validate({ params }) {
        return !isNaN(+params.id)
    },
    asyncData({ $axios, params }) {
        return $axios.get(`/api/races/${params.id}`).then((res) => {
            return {
                ranking: res.data.data,
                race: res.data.race,
            }
        })
    },
}
</script>
