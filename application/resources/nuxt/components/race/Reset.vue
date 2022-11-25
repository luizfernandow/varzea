<template>
    <v-dialog v-model="resetDialog" max-width="290">
        <v-card>
            <v-card-title class="text-h5">
                Tem certeza que quer resetar a corrida?
            </v-card-title>

            <v-card-text> Isso vai... blah blah </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>

                <v-btn color="green darken-1" text @click="close">
                    Cancelar
                </v-btn>

                <v-btn color="red darken-1" text @click="close">
                    Confirmar
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
    },
}
</script>
