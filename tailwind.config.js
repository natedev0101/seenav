import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
    
    safelist: [
        // Háttérszínek
        {
            pattern: /bg-(blue|green|yellow|red|purple|gray|indigo)-(400|500|600|700|800|900)/,
            variants: ['hover', 'focus', 'active'],
        },
        // Szövegszínek
        {
            pattern: /text-(blue|green|yellow|red|purple|gray|indigo)-(400|500|600|700|800|900)/,
            variants: ['hover', 'focus', 'active'],
        },
        // Átlátszóság
        {
            pattern: /opacity-\d+/,
            variants: ['hover', 'focus', 'active'],
        },
        // Border színek
        {
            pattern: /border-(blue|green|yellow|red|purple|gray|indigo)-(400|500|600|700|800|900)/,
            variants: ['hover', 'focus', 'active'],
        }
    ]
};
