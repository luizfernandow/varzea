<template>
    <div>
        <v-card-title>
            <p class="ma-0 mr-auto">{{ race.name }}</p>
        </v-card-title>
        <p class="d-flex justify-center mb-6 title">{{ timerText }}</p>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                :disabled="raceStarted"
                @click="$emit('startTimer')"
                >{{ $t('race.start') }}</v-btn
            >
            <v-btn
                color="secondary"
                :disabled="!raceStarted"
                @click="$emit('stop')"
                >{{ $t('race.stop') }}</v-btn
            >
            <v-btn
                color="green"
                :disabled="raceStarted"
                @click="$emit('saveDialog')"
                >{{ $t('race.save') }}</v-btn
            >
            <v-btn
                color="red"
                :disabled="raceStarted"
                @click="$emit('resetDialog')"
                >{{ $t('race.reset') }}</v-btn
            >
        </v-card-actions>
    </div>
</template>

<script>
export default {
    // eslint-disable-next-line vue/require-prop-types
    props: ['race', 'timerText', 'raceStarted'],
}
</script>
