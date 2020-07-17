import colors from 'vuetify/es5/util/colors'

const { join } = require('path')
const { copySync, removeSync } = require('fs-extra')

export default {
  /*
   ** Nuxt rendering mode
   ** See https://nuxtjs.org/api/configuration-mode
   */
  mode: 'spa',
  /*
   ** Nuxt target
   ** See https://nuxtjs.org/api/configuration-target
   */
  target: 'static',

  buildDir: __dirname + '/.nuxt',
  srcDir: __dirname,
  /*
  ** Headers of the page
  */
  head: {
    titleTemplate: '%s - ' + process.env.npm_package_name,
    title: process.env.npm_package_name || '',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: process.env.npm_package_description || '' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ]
  },
  /*
  ** Customize the progress-bar color
  */
  loading: { color: '#fff' },
  /*
  ** Global CSS
  */
  css: [
  ],
  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    '~/plugins/i18n.js',
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
    '@nuxtjs/vuetify'
  ],
  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://axios.nuxtjs.org/usage
    '@nuxtjs/axios',
    '@nuxtjs/auth',
    '@nuxtjs/pwa'
  ],
  /*
  ** Axios module configuration
  ** See https://axios.nuxtjs.org/options
  */
  axios: {
    credentials: true,
    baseURL: process.env.NUXT_API_URL
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
              'Content-Type': 'application/json'
            } 
          },
          user: { 
            url: '/api/auth/user', 
            method: 'get', 
            propertyName: false,
            withCredentials: true, 
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Content-Type': 'application/json'
            }
          }
        },
        tokenRequired: false,
        tokenType: false
      }
    }
  },
  /*
  ** vuetify module configuration
  ** https://github.com/nuxt-community/vuetify-module
  */
  vuetify: {
    customVariables: ['~/assets/variables.scss'],
    theme: {
      dark: true,
      themes: {
        dark: {
          primary: colors.blue.darken2,
          accent: colors.grey.darken3,
          secondary: colors.amber.darken3,
          info: colors.teal.lighten1,
          warning: colors.amber.base,
          error: colors.deepOrange.accent4,
          success: colors.green.accent3
        }
      }
    }
  },
  /*
  ** Build configuration
  */
  build: {
    /*
    ** You can extend webpack config here
    */
    extend (config, ctx) {
    }
  },
  generate: {
    dir: __dirname + '/dist',
  },
  hooks: {
    generate: {
      done (generator) {
        if (generator.nuxt.options.dev === false && generator.nuxt.options.mode === 'spa') {
            const publicDirNuxt = join(generator.nuxt.options.rootDir, 'public', '_nuxt')
            const publicDir = join(generator.nuxt.options.rootDir, 'public')
            const publicDirImages = join(publicDir, 'images')
            removeSync(publicDirNuxt)
            removeSync(publicDirImages)
            removeSync(join(publicDir, 'sw.js'))
            copySync(join(generator.nuxt.options.generate.dir, '_nuxt'), publicDirNuxt)
            copySync(join(generator.nuxt.options.generate.dir, 'images'), publicDirImages)
            copySync(join(generator.nuxt.options.generate.dir, '200.html'), join(publicDirNuxt, 'index.html'))
            copySync(join(generator.nuxt.options.generate.dir, 'sw.js'), join(publicDir, 'sw.js'))
        }
      }
    }
  }
}
