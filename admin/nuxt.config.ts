// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
	modules: ['@nuxt/eslint'],
	devtools: { enabled: true },
	css: ['~/assets/css/main.css'],
	compatibilityDate: '2024-04-03',

	postcss: {
		plugins: {
			tailwindcss: {},
			autoprefixer: {},
		},
	},
	eslint: {
		checker: true,
		config: {
			stylistic: {
				indent: 'tab',
				semi: true,
			},
		},
	},
});
