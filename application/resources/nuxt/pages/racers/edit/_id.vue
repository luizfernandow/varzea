<template>
    <RacerForm :form-data="data" :update="true" @racerSubmit="update" />
</template>
<script>
export default {
    middleware: 'auth',
    validate({ params }) {
        return !isNaN(+params.id)
    },
    asyncData({ $axios, params }) {
        return $axios.get(`/api/racers/${params.id}`).then((res) => {
            return {
                data: res.data,
            }
        })
    },
    methods: {
        update(form) {
            this.$axios
                .put(`/api/racers/update/${this.$route.params.id}`, form)
                .then(() => {
                    this.$router.push('/racers')
                })
        },
    },
}
</script>
