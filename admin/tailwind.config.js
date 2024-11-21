/** @type {import('tailwindcss').Config} */
module.exports = {
	content: [
		'./components/**/*.{js,vue,ts}',
		'./layouts/**/*.vue',
		'./pages/**/*.vue',
		'./plugins/**/*.{js,ts}',
		'./app.vue',
		'./error.vue',
	],
	theme: {
		extend: {
			colors: {
				'primary': '#075985',
				'primary-hover': '#0369a1',
				'primary-light': '#e0f2fe',
				'primary-light-hover': '#bae6fd',
				'primary-dark': '#082f49',
				'danger': '#9f1239',
				'danger-hover': '#be123c',
				'success': '#065f46',
				'success-hover': '#047857',
				'slate': '#f8fafc',
				'slate-dark': '#64748b',
			},
		},
	},
	plugins: [
		require('@tailwindcss/forms'),
	],
};
