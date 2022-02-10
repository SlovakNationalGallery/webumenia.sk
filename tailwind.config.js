module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  important: '.tailwind-rules',
  theme: {
    fontSize: {
      'xs': '10px',
      'sm': '12px',
      'base': '14px',
      'lg': '16px',
      'xl': '18px',
      '2xl': '24px',
      '3xl': '28px',
      '4xl': '36px',
      '5xl': '42px',
    },
    extend: {
      colors: {
        gray: {
          200: '#ededed',
          300: '#cdcdcd',
          500: '#969696',
          600: '#777',
          800: '#333',
        },
      },
      spacing: {
        '104': '26rem',
      },
      textDecorationThickness: {
        3: '3px',
      }
    },
  },
  plugins: [],
  prefix: 'tw-',
  corePlugins: {
    preflight: false, // TODO Not needed. Re-enable after switching from Bootstrap
  }
}
