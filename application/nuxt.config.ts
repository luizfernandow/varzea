// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  srcDir: './resources/nuxt4',
  css: ['~/assets/css/main.css'],
  image: {
    dir: 'assets/images'
  },

  modules: ['@nuxt/eslint', '@nuxt/image', '@nuxt/scripts', '@nuxt/ui', '@nuxt/hints']
})
