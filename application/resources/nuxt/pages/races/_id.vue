<template>
    <v-card class="mx-auto">
        <v-card-title>
            <p class="ma-0 mr-auto">{{ race.name }}</p>
            <template v-if="authenticated && !race.locked">
                <v-btn
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
                    class="mx-2"
                    fab
                    small
                    dark
                    color="secondary"
                    :to="{
                        name: `races-select-${
                            race.type === 'hours' ? 'groups-' : ''
                        }id`,
                        params: { id: race.id },
                    }"
                >
                    <v-icon dark> mdi-account-group </v-icon>
                </v-btn>
                <v-btn
                    class="mx-2"
                    fab
                    small
                    dark
                    color="teal"
                    :to="{
                        name: `races-start-${
                            race.type === 'hours' ? 'groups-' : ''
                        }id`,
                        params: { id: race.id },
                    }"
                >
                    <v-icon dark> mdi-flag-checkered </v-icon>
                </v-btn>
            </template>
        </v-card-title>
        <v-card-text>
            {{ race.date_start }} - {{ race.time_start }}
            <div class="my-4 subtitle-1">
                {{ $t('race.best_lap') }} <v-icon>mdi-medal</v-icon>
            </div>
            {{ ranking.best_lap?.racer.name }} - {{ ranking.best_lap?.time }}
        </v-card-text>
        <v-divider class="mx-4"></v-divider>
        <RaceRank v-if="ranking.rank" :ranking="ranking" />
        <RaceRankGroup v-if="ranking.rank_group" :ranking="ranking" />
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
