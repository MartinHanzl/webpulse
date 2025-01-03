// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({

	modules: [['nuxt-auth-sanctum', {
		baseUrl: process.env.API_URL ?? 'https://web-pulse.cz/',
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
	}], '@nuxt/eslint', '@nuxt/ui', '@nuxt/image', '@vee-validate/nuxt', '@pinia/nuxt'],
	devtools: { enabled: true },

	css: ['~/assets/css/main.css'],

	routeRules: {
		'/api/**': {
			proxy: `${process.env.API_URL ?? 'https://web-pulse.cz/'}/api/**`,
		},
	},
	compatibilityDate: '2024-11-01',

	vite: {
		plugins: [
			require('vite-svg-loader')(),
		],
	},

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
