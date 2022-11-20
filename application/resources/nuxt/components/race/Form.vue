<template>
    <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="4">
            <ValidationObserver ref="form">
                <v-card
                    slot-scope="{ invalid, validated }"
                    class="elevation-12"
                >
                    <v-form @submit.prevent="handleSubmit">
                        <v-toolbar color="primary" dark flat>
                            <v-toolbar-title>{{
                                $t('race-form.form.title')
                            }}</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <ValidationProvider name="name" rules="required">
                                <v-text-field
                                    v-model="name"
                                    slot-scope="{ errors, valid }"
                                    :label="$t('race-form.form.name')"
                                    :error-messages="errors"
                                    :success="valid"
                                    name="name"
                                    type="text"
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
                                >{{ $t('race-form.form.create') }}</v-btn
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
import { required } from 'vee-validate/dist/rules'

extend('required', required)

export default {
    components: {
        ValidationProvider,
        ValidationObserver,
    },
    data() {
        return {
            name: '',
        }
    },
    methods: {
        handleSubmit() {
            this.$emit('raceSubmit')
        },
    },
}
</script>
