// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
    devtools: {enabled: true},
    modules: [
        '@nuxtjs/tailwindcss',
        '@nuxtjs/i18n',
    ],
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
