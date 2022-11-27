<template>
    <v-dialog v-model="loadingDialog" hide-overlay persistent width="300">
        <v-card color="primary" dark>
            <v-card-text>
                {{ messageDialog }}
                <v-progress-linear
                    indeterminate
                    color="white"
                    class="mb-0"
                ></v-progress-linear>
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    // eslint-disable-next-line vue/require-prop-types
    props: ['dialog', 'message'],
    computed: {
        loadingDialog() {
            return this.dialog
        },
        messageDialog() {
            return this.message
        },
    },
}
</script>
