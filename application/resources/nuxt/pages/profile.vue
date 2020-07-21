<template>
    <h1>{{ user.name }}</h1>
</template>

<script>
export default {
    middleware: 'auth',
}
</script>
