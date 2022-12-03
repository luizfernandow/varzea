<template>
    <v-card class="mx-auto">
        <v-card-title>
            <p class="ma-0 mr-auto">{{ race.name }}</p>
        </v-card-title>
        <v-card-text>
            <v-autocomplete
                v-model="racerSelected"
                :items="racers"
                item-text="name"
                item-value="id"
                :label="$t('race-form.select-groups.racer')"
            ></v-autocomplete>
            <v-divider class="mx-4"></v-divider>

            <div v-if="racerSelected">
                <v-row>
                    <v-col cols="6">
                        <v-text-field
                            v-model="racerGroup"
                            :label="$t('race-form.select-groups.group')"
                            type="number"
                        ></v-text-field>
                    </v-col>
                    <v-col cols="6">
                        <v-text-field
                            v-model="racerNumber"
                            :label="$t('race-form.select-groups.member')"
                            type="number"
                        ></v-text-field>
                    </v-col>
                </v-row>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="secondary" @click="addToGroup">{{
                        $t('race-form.select-groups.add')
                    }}</v-btn>
                </v-card-actions>
            </div>
        </v-card-text>
        <v-list>
            <v-list-item
                v-for="item in Object.values(groups)"
                :key="item.racer_id"
            >
                <v-list-item-content>
                    <v-list-item-title
                        v-text="racersById[item.racer_id]"
                    ></v-list-item-title>
                    <v-list-item-subtitle>
                        {{ item.group }} - {{ item.number }}
                    </v-list-item-subtitle>
                </v-list-item-content>
                <v-list-item-action @click="remove(item.racer_id)">
                    <v-list-item-action-text
                        v-text="$t('race-form.select-groups.delete')"
                    ></v-list-item-action-text>

                    <v-icon color="grey lighten-1"> mdi-delete </v-icon>
                </v-list-item-action>
            </v-list-item>
        </v-list>
        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="primary" @click="save">{{
                $t('race-form.select-groups.save')
            }}</v-btn>
        </v-card-actions>
    </v-card>
</template>
<script>
export default {
    middleware: 'auth',
    validate({ params }) {
        return !isNaN(+params.id)
    },
    asyncData({ $axios, params }) {
        return $axios
            .get(`/api/races/select-groups/${params.id}`)
            .then((res) => {
                return res.data
            })
    },
    data() {
        return {
            racerSelected: null,
            racerGroup: null,
            racerNumber: null,
            race: null,
            racers: [],
            racersById: {},
            groups: {},
            form: {
                racers: {},
                group: {},
                number: {},
            },
        }
    },
    created() {
        for (const group of Object.values(this.groups)) {
            const key = this.getRacerKey(group.racer_id)
            this.form.racers[key] = parseInt(group.racer_id)
            this.form.group[key] = group.group
            this.form.number[key] = group.number
        }
    },
    methods: {
        getRacerKey(racer) {
            return `key_${racer}`
        },
        addToGroup() {
            const key = this.getRacerKey(this.racerSelected)
            this.groups[key] = {
                racer_id: this.racerSelected,
                group: this.racerGroup,
                number: this.racerNumber,
            }
            this.form.racers[key] = parseInt(this.racerSelected)
            this.form.group[key] = this.racerGroup
            this.form.number[key] = this.racerNumber
            this.racerSelected = null
            this.racerGroup = null
            this.racerNumber = null
        },
        remove(racerId) {
            const key = this.getRacerKey(racerId)
            this.$delete(this.groups, key)
            this.$delete(this.form.group, key)
            this.$delete(this.form.number, key)
            this.$delete(this.form.racers, key)
        },
        save() {
            this.$axios
                .post(
                    `/api/races/save-groups/${this.$route.params.id}`,
                    this.form
                )
                .then(() => {
                    this.$router.push(`/races/${this.$route.params.id}`)
                })
        },
    },
}
</script>
