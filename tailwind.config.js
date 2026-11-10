/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                mandar: {
                    50:  '#fdf4f4',
                    100: '#fbe8e8',
                    200: '#f5c5c5',
                    300: '#ec9191',
                    400: '#df5a5a',
                    500: '#c93030',
                    600: '#8B1A1A',
                    700: '#721515',
                    800: '#5c1111',
                    900: '#4a0e0e',
                    950: '#2d0909',
                },
                gold: {
                    50:  '#fdfaed',
                    100: '#faf2d0',
                    200: '#f5e4a0',
                    300: '#edd068',
                    400: '#e5bc3c',
                    500: '#C9A84C',
                    600: '#b08930',
                    700: '#8c6825',
                    800: '#735224',
                    900: '#614523',
                },
                cream: {
                    50:  '#FAF3E0',
                    100: '#f5e8c6',
                    200: '#ecd49a',
                },
            },
            fontFamily: {
                sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
