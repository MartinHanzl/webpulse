import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
  plugins: [forms],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        // Legacy admin-ish tokens kept for existing pages
        primary: '#4f46e5',
        'primary-light': '#818cf8',
        'primary-dark': '#3730a3',
        secondary: '#0ea5e9',
        accent: '#f59e0b',
        surface: '#f8fafc',
        'text-primary': '#0f172a',
        'text-secondary': '#475569',

        // === ThemeForest "Lawnc" demo palette ===
        // Per-variant theming is driven at runtime via CSS variables
        // (see assets/css/theme.css). These map to those variables so
        // utilities like `text-brand` / `bg-brand` re-color per demo.
        brand: 'rgb(var(--brand) / <alpha-value>)',
        'brand-dark': 'rgb(var(--brand-dark) / <alpha-value>)',
        'brand-soft': 'rgb(var(--brand-soft) / <alpha-value>)',
        'brand-cream': 'rgb(var(--brand-cream) / <alpha-value>)',
        'brand-accent': 'rgb(var(--brand-accent) / <alpha-value>)',
        'brand-ink': 'rgb(var(--brand-ink) / <alpha-value>)',
        'brand-muted': 'rgb(var(--brand-muted) / <alpha-value>)',

        // Fixed reference greens from the theme
        leaf: {
          50: '#F8FFF0',
          100: '#ECF8DF',
          200: '#E7FDCF',
          300: '#B0DB58',
          500: '#3DA35C',
          600: '#1FA12E',
          700: '#19200C',
          800: '#111608',
          900: '#061D00',
        },
      },
      fontFamily: {
        display: ['Fustat', 'Inter', 'sans-serif'],
        sans: ['Fustat', 'Inter', 'sans-serif'],
      },
      maxWidth: {
        container: '1320px',
      },
      borderRadius: {
        '4xl': '2rem',
      },
    },
  },
};
