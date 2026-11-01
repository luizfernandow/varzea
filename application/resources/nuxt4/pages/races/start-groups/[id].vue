<script setup lang="ts">
definePageMeta({ middleware: 'auth' })
</script>
<template><RaceTimer :grouped="true" /></template>
