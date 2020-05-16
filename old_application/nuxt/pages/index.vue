<template>
  <v-layout column justify-center align-center>
    <v-flex xs12 sm8 md6>
      <v-img src="/mapa.jpeg"></v-img>
    </v-flex>
  </v-layout>
</template>

<script>
export default {
  head() {
    return {
      title: this.$t('head.welcome.title')
    }
  }
}
</script>
