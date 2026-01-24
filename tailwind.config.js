import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [], // Empty content array - Tailwind won't process any files
    corePlugins: {
        preflight: false, // Disable Tailwind's base reset
    },
    theme: {
        extend: {},
    },
    plugins: [],
};
