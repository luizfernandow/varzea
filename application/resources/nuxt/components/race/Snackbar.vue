<template>
    <v-snackbar
        v-model="snackbarModel"
        :timeout="timeout"
        :multi-line="multiLine"
        absolute
        centered
        top
        color="success"
        outlined
        elevation="24"
    >
        <div v-for="(text, index) in snackbarText.split('\n')" :key="index">
            {{ text }}
        </div>
    </v-snackbar>
</template>
<script>
export default {
    // eslint-disable-next-line vue/require-prop-types
    props: ['snackbar', 'snackbarText'],
    data: () => ({
        timeout: 8000,
        multiLine: true,
    }),
    computed: {
        snackbarModel: {
            get() {
                return this.snackbar
            },
            set(newValue) {
                this.$emit('snackbarUpate', newValue)
            },
        },
    },
}
</script>
