module.exports = {
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
    important: '.tailwind-rules',
    theme: {
        extend: {
            colors: {
                gray: {
                    200: '#ededed',
                    300: '#cdcdcd',
                    500: '#969696',
                    600: '#777',
                    800: '#333',
                },
                sky: {
                    300: '#66ccf4',
                    400: '#37bcf1',
                },
            },
            fontFamily: {
                sans: ['GTWalsheim', 'sans-serif'],
                serif: [
                    'Source Serif Pro',
                    'Minion',
                    'Georgia',
                    'Times New Roman',
                    'Times',
                    'serif',
                ],
            },
        },
    },
    plugins: [],
    prefix: 'tw-',
    corePlugins: {
        preflight: false, // TODO Not needed. Re-enable after switching from Bootstrap
    },
};
