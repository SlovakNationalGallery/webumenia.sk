module.exports = {
    content: ['./resources/**/*.blade.php', './resources/**/*.js', './resources/**/*.vue'],
    theme: {
        extend: {
            colors: {
                amber: {
                    100: '#fcf8e3',
                },
                gray: {
                    50: '#f5f5f5', // @lighter-grey
                    100: '#f0f0f0', // @light-grey
                    200: '#ededed', //
                    300: '#cdcdcd', // @mid-grey
                    500: '#969696', // @grey
                    600: '#777',
                    700: '#5b5d5c',
                    800: '#333', // @gray-dark
                    900: '#222', // @gray-darker
                },
                green: {
                    800: '#3c763d', // @brand-success
                },
                red: {
                    500: '#ce2525',
                    800: '#a94442',
                },
                sky: {
                    300: '#66ccf4', // @primary-color
                    400: '#37bcf1',
                },
                stone: {
                    700: '#484224',
                }
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
            minHeight: {
                12: '3em',
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/forms'),
        require('tailwind-scrollbar')({ nocompatible: true }),
    ],
    prefix: 'tw-',
    corePlugins: {
        preflight: false, // TODO Not needed. Re-enable after switching from Bootstrap
    },
}
