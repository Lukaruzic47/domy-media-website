import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'primary-dark-blue': 'rgb(31, 41, 55)',
                'secondary-dark-blue': 'rgb(44,57,74)',
                'primary-darker-blue': '#0E131F',
                'secondary-darker-blue': '#141e34',
            },
        },
    },

    plugins: [forms],
};