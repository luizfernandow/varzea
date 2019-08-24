<template>
  <v-card tile>
    <v-list three-line>
      <v-subheader>{{ $t('races.title') }}</v-subheader>
      <v-list-item v-for="race in races" :key="race.id">
        <v-list-item-content>
          <nuxt-link :to="{ name: 'races-id', params: { id: race.id } }">
            <v-list-item-title>{{ race.name }}</v-list-item-title>
          </nuxt-link>
          <v-list-item-subtitle>
            {{ race.date_start }} -
            {{ race.time_start }}
          </v-list-item-subtitle>
          <v-list-item-subtitle>
            {{ race.laps }} {{ $t('races.laps') }}
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-card>
</template>

<script>
export default {
  head() {
    return {
      title: this.$t('head.races.title')
    }
  },
  data: () => ({
    races: []
  }),
  created() {
    // fetch the data when the view is created and the data is
    // already being observed
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.$axios
        .get('races')
        .then((response) => {
          this.races = response.data.data
        })
        .catch(() => {
          this.races = []
        })
    }
  }
}
</script>
