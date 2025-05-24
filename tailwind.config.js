import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Important for the dark theme to be controlled by the html class

    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                'eh-dark': '#1a202c',        // Darker Gray (like gray-900)
                'eh-dark-card': '#2d3748',   // Medium Dark Gray (like gray-800)
                'eh-text-primary': '#f7fafc',// Off-white (like gray-100)
                'eh-text-secondary': '#a0aec0',// Light Gray (like gray-500)
                'eh-accent-primary': '#4299e1', // Blue (like blue-500)
                'eh-accent-secondary': '#ed64a6',// Pink (like pink-500)
                'eh-highlight': '#f56565',    // Red (like red-500 for highlights/warnings)
                'eh-border': '#4a5568',      // Gray border (like gray-700)
            },
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                serif: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
        },
    },

    plugins: [forms],
};