// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  ssr: false,
  devtools: { enabled: false },
  routeRules: {
    '/api/**': {
      proxy: process.env.API_URL + '/api/**' || 'http://api.web-pulse.cz/api/**',
    },
  },
  css: ['~/assets/css/main.css'],
  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      title: 'Admin | Web Pulse' || '',
      titleTemplate: '%s - ' + 'Admin | Web Pulse',
    }
  },
  modules: [
    '@nuxtjs/tailwindcss',
    '@nuxtjs/eslint-module',
    '@pinia/nuxt',
    'nuxt-headlessui',
    "@nuxt/image"
  ],
  eslint: {
    cache: false
  }
})