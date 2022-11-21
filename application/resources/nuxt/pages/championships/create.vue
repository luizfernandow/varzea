<template>
    <ChampionshipForm @championshipSubmit="create" />
</template>
<script>
export default {
    middleware: 'auth',
    methods: {
        create(form) {
            this.$axios.post('/api/championships/create', form).then(() => {
                this.$router.push('/ranking')
            })
        },
    },
}
</script>
