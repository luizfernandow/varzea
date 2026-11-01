// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: false,
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  srcDir: './resources/nuxt4',
  dir: { public: './resources/nuxt4/public' },
  css: ['~/assets/css/main.css'],
  image: {
    dir: 'assets/images'
  },
  runtimeConfig: {
    public: {
      apiBase:
        (
          globalThis as typeof globalThis & {
            process?: { env?: Record<string, string | undefined> }
          }
        ).process?.env?.NUXT_API_URL || ''
    }
  },
  nitro: {
    prerender: {
      crawlLinks: false,
      routes: ['/']
    }
  },

  modules: ['@nuxt/eslint', '@nuxt/image', '@nuxt/scripts', '@nuxt/ui', '@nuxt/hints']
})
