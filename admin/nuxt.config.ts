// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({

	modules: [['nuxt-auth-sanctum', {
		baseUrl: process.env.API_URL ?? 'http://srv512428.hstgr.cloud',
		mode: 'token',
		endpoints: {
			csrf: '/sanctum/csrf-cookie',
			login: '/api/admin/auth/login',
			logout: '/api/admin/auth/logout',
			user: '/api/admin/auth/me',
		},
		csrf: {
			cookie: 'XSRF-TOKEN',
			header: 'X-XSRF-TOKEN',
		},
	}], '@nuxt/eslint', '@nuxt/ui'],
	devtools: { enabled: true },

	css: ['~/assets/css/main.css'],

	routeRules: {
		'/api/**': {
			proxy: `${process.env.API_URL ?? 'https://web-pulse.cz/'}/api/**`,
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
});
