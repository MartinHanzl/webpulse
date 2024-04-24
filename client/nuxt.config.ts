// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    ssr: false,
    devtools: {enabled: false},
    css: ['~/assets/css/main.css'],
    app: {
        head: {
            charset: 'utf-8',
            viewport: 'width=device-width, initial-scale=1',
            title: 'Web Pulse'
        }
    },
    modules: [
        '@nuxtjs/tailwindcss',
        '@nuxtjs/i18n',
        '@nuxtjs/eslint-module',
        '@pinia/nuxt',
        'nuxt-headlessui'
    ],
    i18n: {
        defaultLocale: 'cs',
        locales: [
            {code: 'cs', name: 'Čeština', file: './lang/cs.json',},
            {code: 'en', name: 'English', file: './lang/en.json',}
        ]
    },
    eslint: {
        cache: false,
    }
})
