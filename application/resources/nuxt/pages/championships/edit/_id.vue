<template>
    <ChampionshipForm
        :form-data="data"
        :update="true"
        @championshipSubmit="update"
    />
</template>
<script>
export default {
    middleware: 'auth',
    validate({ params }) {
        return !isNaN(+params.id)
    },
    asyncData({ $axios, params }) {
        return $axios.get(`/api/championships/${params.id}`).then((res) => {
            return {
                data: res.data,
            }
        })
    },
    methods: {
        update(form) {
            this.$axios
                .put(`/api/championships/update/${this.$route.params.id}`, form)
                .then(() => {
                    this.$router.push(`/ranking/${this.$route.params.id}`)
                })
        },
    },
}
</script>
