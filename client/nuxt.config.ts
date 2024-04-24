// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    devtools: {enabled: true},
    modules: [
        "@nuxtjs/tailwindcss",
        '@nuxtjs/i18n',
        '@nuxtjs/eslint-module',
        "@nuxt/ui",
        '@zadigetvoltaire/nuxt-gtm',
        'nuxt-gtag',
        'nuxt-headlessui',
        '@nuxtjs/seo'
    ],
    eslint: {
        checker: true,
    },
    pinia: {
        storesDirs: ['./stores/**'],
    },
    i18n: {
        defaultLocale: 'cs',
        locales: [
            {
                code: 'cs',
                name: 'Čeština',
                file: './lang/cs.json',
            },
            {
                code: 'en',
                name: 'English',
                file: './lang/en.json',
            }
        ]
    }
})