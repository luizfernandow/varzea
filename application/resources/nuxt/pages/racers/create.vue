<template>
    <RacerForm @racerSubmit="create" />
</template>
<script>
export default {
    middleware: 'auth',
    methods: {
        create(form) {
            this.$axios.post('/api/racers/create', form).then(() => {
                this.$router.push('/racers')
            })
        },
    },
}
</script>
