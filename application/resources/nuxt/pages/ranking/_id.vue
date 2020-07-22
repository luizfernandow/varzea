<template>
    <v-card class="mx-auto">
        <v-list two-line subheader>
            <v-subheader>{{ championship.name }}</v-subheader>
            <v-list-item v-for="(item, index) in ranking" :key="item.id">
                <v-list-item-avatar>
                    <v-img src="/images/biker.png"></v-img>
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title
                        >{{ (index + 1) }} - {{ item.name }}</v-list-item-title
                    >
                    <v-list-item-subtitle
                        v-text="$t('ranking.points', { points: item.points })"
                    ></v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>
        </v-list>
    </v-card>
</template>

<script>
export default {
    name: 'Ranking',
    validate({ params }) {
        return !isNaN(+params.id)
    },
    asyncData({ $axios, params }) {
        return $axios
            .get(`/api/ranking/by-championship/${params.id}`)
            .then((res) => {
                return {
                    ranking: res.data.data,
                    championship: res.data.championship,
                }
            })
    },
}
</script>
