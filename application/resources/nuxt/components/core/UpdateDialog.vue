<template>
    <v-dialog v-model="updateDialog" width="500" :persistent="true">
        <v-card>
            <v-card-title primary-title>
                {{ $t('update.title') }}
            </v-card-title>

            <v-card-text>
                {{ $t('update.message') }}
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="reloadSW">
                    {{ $t('update.click') }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    computed: {
        updateDialog() {
            return this.$store.state.serviceWorker.updated
        },
    },
    methods: {
        reloadSW() {
            location.reload()
        },
    },
}
</script>
