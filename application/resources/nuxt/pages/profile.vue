<template>
    <v-card class="mx-auto my-12" max-width="374">
        <v-card-title class="white--text mt-8">
            <v-avatar size="56">
                <img alt="user" src="/images/biker.png" />
            </v-avatar>
            <p class="ml-3">
                {{ user.name }}
            </p>
        </v-card-title>

        <v-card-actions>
            <v-btn
                color="deep-purple lighten-2"
                text
                :to="{ name: 'edit-profile' }"
            >
                {{ $t('profile.edit') }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    middleware: 'auth',
}
</script>
