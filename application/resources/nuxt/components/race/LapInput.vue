<template>
    <v-card-text class="mt-10"
        ><v-row>
            <v-col cols="12">
                <v-text-field
                    ref="input"
                    v-model="lapNumberModel"
                    :error="!!lapNumberErrorMessage"
                    :error-messages="lapNumberErrorMessage"
                    append-outer-icon="mdi-send"
                    filled
                    :disabled="!raceStarted || lapSaving"
                    :label="$t('race.doLapField')"
                    type="number"
                    persistent-hint
                    @click:append-outer="$emit('doLap')"
                    @keydown.enter="keyDown()"
                ></v-text-field>
            </v-col>
        </v-row>
    </v-card-text>
</template>

<script>
export default {
    // eslint-disable-next-line vue/require-prop-types
    props: ['lapNumber', 'lapSaving', 'lapNumberErrorMessage', 'raceStarted'],
    computed: {
        lapNumberModel: {
            get() {
                return this.lapNumber
            },
            set(newValue) {
                this.$emit('lapNumberUpdate', newValue)
            },
        },
    },
    methods: {
        focus() {
            this.$refs.input.focus()
        },
        keyDown() {
            setTimeout(() => {
                this.$emit('doLap')
            }, 500)
        },
    },
}
</script>
