<template>
    <v-app dark>
        <v-navigation-drawer v-model="drawer" clipped fixed app>
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

            <v-btn v-if="!$auth.loggedIn" icon to="/login">
                <v-icon>mdi-login</v-icon>
            </v-btn>

            <v-menu v-else left bottom>
                <template v-slot:activator="{ on, attrs }">
                    <v-btn icon v-bind="attrs" v-on="on">
                        <v-icon>mdi-account</v-icon>
                    </v-btn>
                </template>

                <v-list>
                    <v-list-item @click="logout()">
                        <v-list-item-title
                            >{{ $t('auth.logout') }}
                            <v-icon>mdi-logout</v-icon></v-list-item-title
                        >
                    </v-list-item>
                </v-list>
            </v-menu>
        </v-app-bar>
        <v-main>
            <v-container>
                <nuxt />
            </v-container>
        </v-main>
        <v-footer fixed app>
            <span>&copy; {{ new Date().getFullYear() }}</span>
        </v-footer>
    </v-app>
</template>

<script>
export default {
    data() {
        return {
            drawer: false,
            items: [
                {
                    icon: 'mdi-apps',
                    title: this.$t('menu.welcome'),
                    to: '/',
                },
                {
                    icon: 'mdi-calendar',
                    title: this.$t('menu.calendar'),
                    to: '/calendar',
                },
            ],
            title: 'Várzealand',
        }
    },
    methods: {
        logout() {
            this.$auth.logout()
        },
    },
}
</script>
