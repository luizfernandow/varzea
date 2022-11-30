<template>
    <v-dialog v-model="undoLapDialog" max-width="290" :persistent="true">
        <v-card>
            <v-card-title class="text-h5">
                {{ $t('race.undoLapDialog.title') }}
            </v-card-title>

            <v-card-text> {{ $t('race.undoLapDialog.content') }} </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn color="green darken-1" text @click="close">
                    {{ $t('race.undoLapDialog.cancel') }}
                </v-btn>

                <v-btn color="red darken-1" text @click="confirm">
                    {{ $t('race.undoLapDialog.confirm') }}
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
        undoLapDialog() {
            return this.dialog
        },
    },
    methods: {
        close() {
            this.$emit('undoLap', false)
        },
        confirm() {
            this.$emit('undoLap', true)
        },
    },
}
</script>
