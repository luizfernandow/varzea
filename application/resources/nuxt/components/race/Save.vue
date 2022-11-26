<template>
    <v-dialog v-model="saveDialog" max-width="290" :persistent="true">
        <v-card>
            <v-card-title class="text-h5">
                {{ $t('race.saveDialog.title') }}
            </v-card-title>

            <v-card-text> {{ $t('race.saveDialog.content') }} </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn color="green darken-1" text @click="close">
                    {{ $t('race.saveDialog.cancel') }}
                </v-btn>

                <v-btn color="red darken-1" text @click="confirm">
                    {{ $t('race.saveDialog.confirm') }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    // eslint-disable-next-line vue/require-prop-types
    props: ['dialog'],
    computed: {
        saveDialog() {
            return this.dialog
        },
    },
    methods: {
        close() {
            this.$emit('saveRace', false)
        },
        confirm() {
            this.$emit('saveRace', true)
        },
    },
}
</script>
