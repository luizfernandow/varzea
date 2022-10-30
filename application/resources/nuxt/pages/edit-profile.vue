<template>
    <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="4">
            <ValidationObserver ref="form">
                <v-card
                    slot-scope="{ invalid, validated }"
                    class="elevation-12"
                >
                    <v-toolbar color="primary" dark flat>
                        <v-toolbar-title>{{
                            $t('edit-profile.form.title')
                        }}</v-toolbar-title>
                    </v-toolbar>
                    <v-card-text>
                        <v-form>
                            <ValidationProvider name="name" rules="required">
                                <v-text-field
                                    v-model="name"
                                    slot-scope="{ errors, valid }"
                                    :label="$t('edit-profile.form.name')"
                                    :error-messages="errors"
                                    :success="valid"
                                    name="name"
                                    type="text"
                                    required
                                ></v-text-field>
                            </ValidationProvider>
                        </v-form>
                    </v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn
                            color="primary"
                            :disabled="invalid || !validated"
                            @click="update()"
                            >{{ $t('edit-profile.form.update') }}</v-btn
                        >
                    </v-card-actions>
                </v-card>
            </ValidationObserver>
        </v-col>
    </v-row>
</template>
<script>
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate'
import { required } from 'vee-validate/dist/rules'

extend('required', required)

export default {
    middleware: 'auth',
    components: {
        ValidationProvider,
        ValidationObserver,
    },
    data() {
        return {
            name: this.$store.getters.user.name,
        }
    },
    methods: {
        update() {
            this.$axios
                .post('/api/profile/update', {
                    name: this.name,
                })
                .then(() => {
                    this.$auth.fetchUser().then(() => {
                        this.$router.push('/profile')
                    })
                })
        },
    },
}
</script>
