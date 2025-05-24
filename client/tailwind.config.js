/** @type {import('tailwindcss').Config} */
export default {
	plugins: [require('@tailwindcss/forms')],
	darkMode: false,
	theme: {
		extend: {
			colors: {
				primary: '#083344',
				secondary: '#155e75',
				dark: '#164e63',
				light: '#cffafe',
			},
			fontFamily: {
				sans: ['Montserrat', 'sans-serif'],
			},
		},
	},
};
