<template>
    <RaceForm @raceSubmit="create" />
</template>
<script>
export default {
    middleware: 'auth',
    methods: {
        create() {},
    },
}
</script>
