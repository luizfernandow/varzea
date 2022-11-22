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
                                $t('race-form.form.title')
                            }}</v-toolbar-title>
                        </v-toolbar>
                        <v-card-text>
                            <ValidationProvider name="name" rules="required">
                                <v-text-field
                                    v-model="form.name"
                                    slot-scope="{ errors, valid }"
                                    :label="$t('race-form.form.name')"
                                    :error-messages="errors"
                                    :success="valid"
                                    name="name"
                                    type="text"
                                    required
                                ></v-text-field>
                            </ValidationProvider>
                            <ValidationProvider
                                name="championship_id"
                                rules="required"
                            >
                                <v-select
                                    v-model="form.championship_id"
                                    slot-scope="{ errors, valid }"
                                    :items="championships"
                                    item-text="name"
                                    item-value="id"
                                    :error-messages="errors"
                                    :success="valid"
                                    name="championship_id"
                                    :label="$t('race-form.form.championship')"
                                ></v-select>
                            </ValidationProvider>
                            <v-menu
                                ref="menuDateStart"
                                v-model="menuDateStart"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="dateFormatted"
                                        :label="$t('race-form.form.date_start')"
                                        persistent-hint
                                        prepend-icon="mdi-calendar"
                                        v-bind="attrs"
                                        @blur="date = parseDate(dateFormatted)"
                                        v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="form.date_start"
                                    name="date_start"
                                    no-title
                                    @input="menuDateStart = false"
                                ></v-date-picker>
                            </v-menu>

                            <v-menu
                                ref="menuTimeStart"
                                v-model="menuTimeStart"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                :return-value.sync="form.time_start"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <ValidationProvider
                                        name="time_start"
                                        rules="required"
                                    >
                                        <v-text-field
                                            v-model="form.time_start"
                                            slot-scope="{ errors, valid }"
                                            :label="
                                                $t('race-form.form.time_start')
                                            "
                                            :error-messages="errors"
                                            :success="valid"
                                            prepend-icon="mdi-clock-time-four-outline"
                                            readonly
                                            name="time_start"
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </ValidationProvider>
                                </template>
                                <v-time-picker
                                    v-if="menuTimeStart"
                                    v-model="form.time_start"
                                    full-width
                                    @click:minute="
                                        $refs.menuTimeStart.save(
                                            form.time_start
                                        )
                                    "
                                ></v-time-picker>
                            </v-menu>
                            <v-checkbox
                                v-model="form.type"
                                :label="$t('race-form.form.type')"
                            ></v-checkbox>
                            <v-text-field
                                v-if="!form.type"
                                v-model="form.laps"
                                :label="$t('race-form.form.laps')"
                                name="name"
                                type="number"
                            ></v-text-field>
                            <v-text-field
                                v-if="form.type"
                                v-model="form.hours"
                                :label="$t('race-form.form.hours')"
                                name="hours"
                                type="number"
                            ></v-text-field>
                            <v-text-field
                                v-if="form.type"
                                v-model="form.group"
                                :label="$t('race-form.form.group')"
                                name="group"
                                type="number"
                            ></v-text-field>
                        </v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="accent" @click="cancel">{{
                                $t('race-form.form.cancel')
                            }}</v-btn>
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
    // eslint-disable-next-line vue/require-prop-types
    props: ['formData'],
    async fetch() {
        this.championships = await this.$axios
            .get('/api/championships')
            .then((res) => res.data.data)
    },
    data() {
        return {
            form: {
                name: '',
                championship_id: null,
                date_start: new Date(
                    Date.now() - new Date().getTimezoneOffset() * 60000
                )
                    .toISOString()
                    .substring(0, 10),
                time_start: null,
                type: false,
                laps: null,
                hours: null,
                group: null,
            },
            championships: [],
            dateFormatted: this.formatDate(
                new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
                    .toISOString()
                    .substring(0, 10)
            ),
            menuDateStart: false,
            menuTimeStart: false,
        }
    },
    computed: {
        computedDateFormatted() {
            return this.formatDate(this.form.date_start)
        },
    },
    watch: {
        'form.date_start'() {
            this.dateFormatted = this.formatDate(this.form.date_start)
        },
    },
    created() {
        if (this.formData) {
            this.form = { ...this.formData }
        }
    },
    methods: {
        formatDate(date) {
            if (!date) return null

            const [year, month, day] = date.split('-')
            return `${day}/${month}/${year}`
        },
        parseDate(date) {
            if (!date) return null

            const [month, day, year] = date.split('/')
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
        },
        handleSubmit() {
            this.$emit('raceSubmit', this.form)
        },
        cancel() {
            this.$router.go(-1)
        },
    },
}
</script>
