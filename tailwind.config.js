import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{html,svelte,js,ts}',
        './node_modules/flowbite-svelte/**/*.{html,js,svelte,ts}',
        "./node_modules/flowbite-svelte-icons/**/*.{html,js,svelte,ts}",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Kanit', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // flowbite-svelte
                primary: {
                    50: '#FFF5F2',
                    100: '#FFF1EE',
                    200: '#FFE4DE',
                    300: '#FFD5CC',
                    400: '#FFBCAD',
                    500: '#FE795D',
                    600: '#EF562F',
                    700: '#EB4F27',
                    800: '#CC4522',
                    900: '#A5371B'
                }
            },
        },
    },

    plugins: [forms],

    darkMode: 'selector'
};
