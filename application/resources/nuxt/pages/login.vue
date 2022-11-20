<template>
    <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="4">
            <ValidationObserver ref="form">
                <v-card
                    slot-scope="{ invalid, validated }"
                    class="elevation-12"
                >
                    <v-form @submit.prevent="login">
                        <v-toolbar color="primary" dark flat>
                            <v-toolbar-title>{{
                                $t('auth.form.title')
                            }}</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <ValidationProvider
                                name="email"
                                rules="required|email"
                            >
                                <v-text-field
                                    v-model="email"
                                    slot-scope="{ errors, valid }"
                                    :label="$t('auth.form.email')"
                                    :error-messages="errors"
                                    :success="valid"
                                    name="email"
                                    prepend-icon="mdi-account"
                                    type="text"
                                    required
                                ></v-text-field>
                            </ValidationProvider>
                            <ValidationProvider
                                name="password"
                                rules="required"
                            >
                                <v-text-field
                                    v-model="password"
                                    slot-scope="{ errors, valid }"
                                    :label="$t('auth.form.password')"
                                    :error-messages="errors"
                                    :success="valid"
                                    name="password"
                                    prepend-icon="mdi-lock"
                                    type="password"
                                    required
                                ></v-text-field>
                            </ValidationProvider>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="primary"
                                type="submit"
                                :disabled="invalid || !validated"
                                >{{ $t('auth.form.login') }}</v-btn
                            >
                        </v-card-actions>
                    </v-form>
                </v-card>
            </ValidationObserver>
        </v-col>
    </v-row>
</template>
<script>
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate'
import { required, email } from 'vee-validate/dist/rules'

extend('required', required)
extend('email', email)

export default {
    middleware: 'guest',
    components: {
        ValidationProvider,
        ValidationObserver,
    },
    data() {
        return {
            email: '',
            password: '',
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
                        this.$auth
                            .loginWith('local', {
                                data: {
                                    email: this.email,
                                    password: this.password,
                                },
                            })
                            .catch((e) => {
                                this.$refs.form.setErrors(this.errorsApi)
                            })
                    }.bind(this)
                )
        },
    },
}
</script>
