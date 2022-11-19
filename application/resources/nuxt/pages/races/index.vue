<template>
    <v-card class="mx-auto">
        <v-card-title>
            <p class="ma-0 mr-auto">{{ $t('races.title') }}</p>
            <v-btn
                v-if="authenticated"
                class="mx-2"
                fab
                small
                dark
                color="indigo"
                :to="{ name: 'races-create' }"
            >
                <v-icon dark> mdi-plus </v-icon>
            </v-btn>
        </v-card-title>

        <v-list>
            <v-list-item
                v-for="item in races"
                :key="item.id"
                :to="{ name: 'races-id', params: { id: item.id } }"
                :two-line="!item.laps"
                :three-line="!!item.laps"
            >
                <v-list-item-content>
                    <v-list-item-title v-text="item.name"></v-list-item-title>
                    <v-list-item-subtitle>
                        {{ item.date_start }} - {{ item.time_start }}
                    </v-list-item-subtitle>
                    <v-list-item-subtitle
                        v-if="item.laps"
                        v-text="$t('races.laps', { laps: item.laps })"
                    >
                    </v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-card>
</template>

<script>
export default {
    asyncData({ $axios }) {
        return $axios.get('/api/races').then((res) => {
            return { races: res.data.data }
        })
    },
}
</script>
