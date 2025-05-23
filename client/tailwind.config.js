/** @type {import('tailwindcss').Config} */
export default {
	plugins: [require('@tailwindcss/forms')],
	darkMode: false,
	theme: {
		extend: {
			colors: {
				primary: '#fafafa',
				secondary: '#71717b',
				dark: '#18181b',
			},
			fontFamily: {
				sans: ['Inter', 'sans-serif'],
			},
		},
	},
};
