<template>
    <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="4">
            <v-card class="elevation-12">
                <v-toolbar color="primary" dark flat>
                    <v-toolbar-title>{{
                        $t('auth.form.title')
                    }}</v-toolbar-title>
                </v-toolbar>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            v-model="email"
                            :label="$t('auth.form.email')"
                            :class="{ 'error--text': errors.email }"
                            :error-messages="errors.email"
                            name="email"
                            prepend-icon="mdi-account"
                            type="text"
                        ></v-text-field>

                        <v-text-field
                            id="password"
                            v-model="password"
                            :label="$t('auth.form.password')"
                            :class="{ 'error--text': errors.password }"
                            :error-messages="errors.password"
                            name="password"
                            prepend-icon="mdi-lock"
                            type="password"
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="primary" @click="login()">{{
                        $t('auth.form.login')
                    }}</v-btn>
                </v-card-actions>
            </v-card>
        </v-col>
    </v-row>
</template>
<script>
export default {
    data() {
        return {
            email: '',
            password: '',
        }
    },
    beforeCreate() {
        const self = this
        /* Make Sure We Only Load Login Page If Not Authenticated */
        if (self.$auth.loggedIn) {
            /* nextick make sure our modal wount be visible before redirect */
            return self.$nextTick(() => self.$router.push({ path: '/' }))
        }
    },
    methods: {
        login() {
            this.$axios
                .get('/sanctum/csrf-cookie', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    withCredentials: true,
                })
                .then(
                    function () {
                        this.$auth.loginWith('local', {
                            data: {
                                email: this.email,
                                password: this.password,
                            },
                        })
                    }.bind(this)
                )
        },
    },
}
</script>
