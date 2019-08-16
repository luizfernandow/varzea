<template>
  <v-layout>
    <v-flex column fill-height>
      <v-list>
        <v-list-item v-for="race in races" :key="race.id">
          <v-list-item-content>
            <v-list-item-title>{{ race.name }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-flex>
  </v-layout>
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
          this.races = response.data
        })
        .catch(() => {
          this.races = []
        })
    }
  }
}
</script>
