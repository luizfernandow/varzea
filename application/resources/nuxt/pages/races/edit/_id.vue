<template>
    <RaceForm :form-data="data" :update="true" @raceSubmit="update" />
</template>
<script>
export default {
    middleware: 'auth',
    validate({ params }) {
        return !isNaN(+params.id)
    },
    asyncData({ $axios, params }) {
        return $axios.get(`/api/races/${params.id}/edit`).then((res) => {
            return {
                data: res.data,
            }
        })
    },
    methods: {
        update(form) {
            this.$axios
                .put(`/api/races/update/${this.$route.params.id}`, form)
                .then(() => {
                    this.$router.push(`/races/${this.$route.params.id}`)
                })
        },
    },
}
</script>
