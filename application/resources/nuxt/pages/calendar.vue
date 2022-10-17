<template>
    <div class="map-iframe">
        <h1>Test</h1>
        <iframe
            width="100%"
            height="100%"
            src="https://calendar.google.com/calendar/embed?src=cv3mmahedsdpuerd1r4t9kh2q8@group.calendar.google.com&ctz=America/Sao_Paulo&pli=1"
        >
        </iframe>
    </div>
</template>

<script>
export default {
    head() {
        return {
            title: this.$t('head.calendar.title'),
        }
    },
}
</script>

<style scoped>
.map-iframe {
    height: calc(100vh - 125px);
}
</style>
