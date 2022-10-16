<template>
    <v-card>
        <v-card-title>{{ user.name }}</v-card-title>
    </v-card>
</template>

<script>
export default {
    middleware: 'auth',
}
</script>
