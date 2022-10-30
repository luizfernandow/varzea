import { join } from 'path'
import fs from 'fs'
import { copySync, removeSync } from 'fs-extra'

export default {
    ssr: false,
    /*
     ** Nuxt target
     ** See https://nuxtjs.org/api/configuration-target
     */
    target: 'static',

    buildDir: join(__dirname, '/.nuxt'),
    srcDir: __dirname,
    /*
     ** Headers of the page
     */
    head: {
        titleTemplate: '%s - ' + process.env.npm_package_name,
        title: process.env.npm_package_name || '',
        meta: [
            { charset: 'utf-8' },
            {
                name: 'viewport',
                content: 'width=device-width, initial-scale=1',
            },
            {
                hid: 'description',
                name: 'description',
                content: process.env.npm_package_description || '',
            },
        ],
        link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }],
    },
    /*
     ** Customize the progress-bar color
     */
    loading: { color: '#fff' },
    /*
     ** Global CSS
     */
    css: [],

    router: {
        middleware: ['clearValidationErrors'],
    },

    /*
     ** Plugins to load before mounting the App
     */
    plugins: [
        '~/plugins/mixins/validation',
        '~/plugins/mixins/user',
        '~/plugins/i18n',
        '~/plugins/axios',
        '~/plugins/serviceWorker.client.js',
    ],
    /*
     ** Auto import components
     ** See https://nuxtjs.org/api/configuration-components
     */
    components: true,
    /*
     ** Nuxt.js dev-modules
     */
    buildModules: [
        // Doc: https://github.com/nuxt-community/eslint-module
        '@nuxtjs/eslint-module',
        // Doc: https://github.com/nuxt-community/stylelint-module
        '@nuxtjs/stylelint-module',
        '@nuxtjs/vuetify',
    ],
    eslint: {
        fix: true,
    },
    /*
     ** Nuxt.js modules
     */
    modules: [
        // Doc: https://axios.nuxtjs.org/usage
        '@nuxtjs/axios',
        '@nuxtjs/auth',
        '@nuxtjs/pwa',
    ],
    /*
     ** Axios module configuration
     ** See https://axios.nuxtjs.org/options
     */
    axios: {
        credentials: true,
        baseURL: process.env.NUXT_API_URL,
    },
    /*
     ** Auth module configuration
     ** See https://auth.nuxtjs.org/
     */
    auth: {
        strategies: {
            local: {
                endpoints: {
                    login: {
                        url: '/api/auth/login',
                        method: 'post',
                        withCredentials: true,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                        },
                    },
                    user: {
                        url: '/api/auth/user',
                        method: 'get',
                        propertyName: false,
                        withCredentials: true,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                        },
                    },
                },
                tokenRequired: false,
                tokenType: false,
            },
        },
    },
    /*
     ** vuetify module configuration
     ** https://github.com/nuxt-community/vuetify-module
     */
    vuetify: {
        optionsPath: './vuetify.options.js',
    },

    pwa: {
        manifest: {
            name: 'Várzealand App',
            short_name: 'Várzealand',
            orientation: 'portrait',
        },
        workbox: {
            runtimeCaching: [
                {
                    urlPattern: 'https://fonts.(?:googleapis|gstatic).com/(.*)',
                    handler: 'staleWhileRevalidate',
                    strategyOptions: {
                        cacheName: 'googleapis',
                        cacheExpiration: {
                            maxEntries: 30,
                        },
                    },
                },
                {
                    urlPattern: /\.(?:png|gif|jpg|jpeg|svg)$/,
                    handler: 'cacheFirst',
                    strategyOptions: {
                        cacheName: 'images',
                        cacheExpiration: {
                            maxEntries: 60,
                            maxAgeSeconds: 30 * 24 * 60 * 60, // 30 Days
                        },
                    },
                },
            ],
        },
    },

    /*
     ** Build configuration
     */
    build: {
        transpile: ['vee-validate/dist/rules'],
        /*
         ** You can extend webpack config here
         */
        extend(config, ctx) {},
    },
    generate: {
        dir: join(__dirname, '/dist'),
    },
    hooks: {
        generate: {
            // Used for server the static files on same server as Laravel API
            done(generator) {
                if (
                    generator.nuxt.options.dev === false &&
                    generator.nuxt.options.mode === 'spa'
                ) {
                    const publicDir = join(
                        generator.nuxt.options.rootDir,
                        'public'
                    )
                    fs.readdirSync(publicDir, (err, files) => {
                        if (err) {
                            return
                        }

                        files.forEach((file) => {
                            if (file !== 'index.php' && file !== 'robots.txt') {
                                removeSync(join(publicDir, file))
                            }
                        })
                    })

                    copySync(generator.nuxt.options.generate.dir, publicDir)
                }
            },
        },
    },
}
