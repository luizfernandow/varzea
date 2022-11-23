<template>
    <RaceForm @raceSubmit="create" />
</template>
<script>
export default {
    middleware: 'auth',
    methods: {
        create(form) {
            this.$axios.post('/api/races/create', form).then(() => {
                this.$router.push('/races')
            })
        },
    },
}
</script>
