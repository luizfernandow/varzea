<template>
  <v-card v-if="race">
    <v-card-title>{{ race.name }}</v-card-title>
    <v-card-text>
      {{ race.date_start }} - {{ race.time_start }}
      <br />
      <v-chip
        v-if="race.best_lap"
        class="mt-2"
        color="indigo"
        text-color="white"
      >
        <v-avatar left>
          <v-icon>mdi-medal</v-icon>
        </v-avatar>
        {{ race.best_lap.racer.name }} - {{ race.best_lap.time }}
      </v-chip>
    </v-card-text>
    <v-list>
      <v-list-group
        v-for="(item, index) in race.rank"
        :key="item.racer.id"
        no-action
        two-line
      >
        <template v-slot:activator>
          <v-list-item-content>
            <v-list-item-title>
              <v-chip pill>
                <v-avatar left color="primary">
                  {{ index + 1 }}
                </v-avatar>
                {{ item.racer.name }}
              </v-chip>
            </v-list-item-title>
            <v-list-item-subtitle
              ><v-chip small class="mt-2">
                {{ item.time }} / {{ item.points }}
                {{ $t('race.points') }}</v-chip
              ></v-list-item-subtitle
            >
          </v-list-item-content>
        </template>

        <v-list-item
          v-for="(time, index_laps) in race.time_laps[item.racer.id]"
          :key="item.racer.id + time + index_laps"
        >
          <v-list-item-content>
            <v-list-item-title
              >{{ index_laps + 1 }} - {{ time }}
              <v-icon v-if="time == race.best_lap.time"
                >mdi-medal</v-icon
              ></v-list-item-title
            >
          </v-list-item-content>
        </v-list-item>
      </v-list-group>
    </v-list>
  </v-card>
</template>

<script>
export default {
  head() {
    return {
      title: this.$t('head.race.title')
    }
  },
  data: () => ({
    race: null
  }),
  created() {
    // fetch the data when the view is created and the data is
    // already being observed
    this.fetchData()
  },
  methods: {
    fetchData() {
      this.$axios
        .get('races/' + this.$route.params.id)
        .then((response) => {
          this.race = response.data.data
        })
        .catch(() => {
          this.race = null
        })
    }
  }
}
</script>
