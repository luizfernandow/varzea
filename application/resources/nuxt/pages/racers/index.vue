<template>
    <v-card class="mx-auto">
        <v-card-title>
            <p class="ma-0 mr-auto">{{ $t('racers.title') }}</p>
            <v-spacer></v-spacer>
            <v-text-field
                v-model="search"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
            <v-spacer></v-spacer>
            <v-btn
                v-if="authenticated"
                class="mx-2"
                fab
                small
                dark
                color="indigo"
                :to="{ name: 'racers-create' }"
            >
                <v-icon dark> mdi-plus </v-icon>
            </v-btn>
        </v-card-title>
        <v-data-table :headers="headers" :items="racers" :search="search">
            <template v-if="authenticated" v-slot:item.actions="{ item }">
                <v-btn
                    class="mr-2"
                    fab
                    small
                    dark
                    :to="{
                        name: 'racers-edit-id',
                        params: { id: item.id },
                    }"
                >
                    <v-icon dark> mdi-pencil </v-icon>
                </v-btn>
            </template></v-data-table
        >
    </v-card>
</template>

<script>
export default {
    asyncData({ $axios }) {
        return $axios.get('/api/racers').then((res) => {
            return { racers: res.data.data }
        })
    },
    data() {
        return {
            search: '',
            headers: [
                {
                    text: this.$t('racers.name'),
                    align: 'start',
                    sortable: false,
                    value: 'name',
                },
                this.$auth.loggedIn && {
                    text: this.$t('racers.actions'),
                    value: 'actions',
                    sortable: false,
                },
            ],
            racers: [],
        }
    },
}
</script>
