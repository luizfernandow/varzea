<template>
    <v-card class="mx-auto">
        <v-card-title>{{ $t('ranking.championships') }}</v-card-title>
        <v-list>
            <v-list-item
                v-for="item in championships"
                :key="item.id"
                :to="{ name: 'ranking-id', params: { id: item.id } }"
            >
                <v-list-item-content>
                    <v-list-item-title v-text="item.name"></v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-card>
</template>

<script>
export default {
    asyncData({ $axios }) {
        return $axios.get('/api/ranking/championships').then((res) => {
            return { championships: res.data.data }
        })
    },
}
</script>
