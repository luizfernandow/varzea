<template>
    <v-dialog v-model="resetDialog" max-width="290" :persistent="true">
        <v-card>
            <v-card-title class="text-h5">
                {{ $t('race.resetDialog.title') }}
            </v-card-title>

            <v-card-text> {{ $t('race.resetDialog.content') }} </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn color="green darken-1" text @click="close">
                    {{ $t('race.resetDialog.cancel') }}
                </v-btn>

                <v-btn color="red darken-1" text @click="confirm">
                    {{ $t('race.resetDialog.confirm') }}
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
        resetDialog() {
            return this.dialog
        },
    },
    methods: {
        close() {
            this.$emit('resetRace', false)
        },
        confirm() {
            this.$emit('resetRace', true)
        },
    },
}
</script>
