<template>
  <v-app dark>
    <v-navigation-drawer
      v-model="drawer"
      clipped fixed app
    >
      <v-list>
        <v-list-item
          v-for="(item, i) in items"
          :key="i"
          :to="item.to"
          router
          exact
        >
          <v-list-item-action>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title v-text="item.title" />
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-navigation-drawer>
    <v-app-bar clipped-left fixed app>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-avatar class="mr-2">
        <v-img src="/images/icon.png"></v-img>
      </v-avatar>
      <v-toolbar-title v-text="title" />
      <v-spacer></v-spacer>

      <v-btn icon v-if="!$auth.loggedIn" to="/login">
        <v-icon>mdi-login</v-icon>
      </v-btn>

      <v-btn icon v-else>
        <v-icon>mdi-account</v-icon>
      </v-btn>

    </v-app-bar>
    <v-content>
      <v-container>
        <nuxt />
      </v-container>
    </v-content>
    <v-footer
      :fixed="fixed"
      app
    >
      <span>&copy; {{ new Date().getFullYear() }}</span>
    </v-footer>
  </v-app>
</template>

<script>
export default {
  data () {
    return {
      clipped: false,
      drawer: false,
      fixed: false,
      items: [
        {
          icon: 'mdi-apps',
          title: this.$t('menu.welcome'),
          to: '/'
        },
        {
          icon: 'mdi-calendar',
          title: this.$t('menu.calendar'),
          to: '/calendar'
        }
      ],
      miniVariant: false,
      right: true,
      rightDrawer: false,
      title: 'Várzealand'
    }
  }
}
</script>
