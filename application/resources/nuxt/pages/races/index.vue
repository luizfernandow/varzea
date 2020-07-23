<template>
    <v-card class="mx-auto">
        <v-card-title>{{ $t('races.title') }}</v-card-title>
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
