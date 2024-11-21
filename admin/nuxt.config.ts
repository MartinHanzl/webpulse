// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({

	modules: ['nuxt-auth-sanctum', '@nuxt/eslint'],
	devtools: { enabled: true },

	css: ['~/assets/css/main.css'],

	routeRules: {
		'/api/**': {
			proxy: `${process.env.API_URL ?? 'http://srv512428.hstgr.cloud'}/api/**`,
		},
	},
	compatibilityDate: '2024-11-01',

	postcss: {
		plugins: {
			tailwindcss: {},
			autoprefixer: {},
		},
	},

	eslint: {
		config: {
			stylistic: {
				indent: 'tab',
				semi: true,
			},
		},
	},

	sanctum: {
		baseUrl: 'http://localhost:8010/api/admin/auth', // Laravel API
		mode: 'token',
	},
});
