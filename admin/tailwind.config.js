/** @type {import('tailwindcss').Config} */
export default {
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
				primary: '#0c4a6e',
				secondary: '#7dd3fc',
				dark: '#0f172a',
				light: '#cbd5e1',
				success: '#16a34a',
				warning: '#ca8a04',
				danger: '#dc2626',
			},
		},
	},
	plugins: [require('@tailwindcss/forms')],
};
