// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    ssr: false,
    devtools: {enabled: false},
    css: ['~/assets/css/main.css'],
    modules: [
        '@nuxtjs/tailwindcss',
        '@nuxtjs/i18n',
        '@nuxtjs/eslint-module'
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
