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
		baseUrl: 'http://localhost:8010', // Laravel API
		globalMiddleware: {
			enabled: true,
			allow404WithoutAuth: true,
		},
		redirectIfAuthenticated: true,
		redirectIfUnauthenticated: true,
		redirect: {
			keepRequestedRoute: false,
			onLogin: '/',
			onLogout: '/',
			onAuthOnly: '/login',
			onGuestOnly: '/',
		},
		endpoints: {
			csrf: '/api/admin/auth/refresh',
			login: '/api/admin/auth/login',
			logout: '/api/admin/auth/logout',
			user: '/api/admin/auth/me',
		},
	},
});
