<template>
    <v-row align="center" justify="center">
        <v-col cols="12" sm="8" md="4">
            <ValidationObserver ref="form">
                <v-card
                    slot-scope="{ invalid, validated }"
                    class="elevation-12"
                >
                    <v-form autocomplete="off" @submit.prevent="handleSubmit">
                        <v-toolbar color="primary" dark flat>
                            <v-toolbar-title>{{
                                $t('championship-form.form.title')
                            }}</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <ValidationProvider name="name" rules="required">
                                <v-text-field
                                    v-model="form.name"
                                    slot-scope="{ errors, valid }"
                                    :label="$t('championship-form.form.name')"
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
                            <v-btn color="secondary" @click="cancel">{{
                                $t('championship-form.form.cancel')
                            }}</v-btn>
                            <v-btn
                                color="primary"
                                type="submit"
                                :disabled="invalid || !validated"
                                >{{
                                    update
                                        ? $t('championship-form.form.update')
                                        : $t('championship-form.form.create')
                                }}</v-btn
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
    // eslint-disable-next-line vue/require-prop-types
    props: ['formData', 'update'],
    data() {
        return {
            form: {
                name: '',
            },
        }
    },
    created() {
        if (this.formData) {
            this.form = { ...this.formData }
        }
    },
    methods: {
        handleSubmit() {
            this.$emit('championshipSubmit', this.form)
        },
        cancel() {
            this.$router.go(-1)
        },
    },
}
</script>
