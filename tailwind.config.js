import defaultTheme from 'tailwindcss/defaultTheme';
/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            borderRadius: {
                'luxury': '2rem', // Custom for TC Service Center dashboard cards
            },
            colors: {
                'tc-blue': '#2563eb',
            }
        },
    },
    plugins: [],
}
