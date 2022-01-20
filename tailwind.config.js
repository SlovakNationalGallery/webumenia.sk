module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  important: '#tailwind-rules',
  theme: {
    extend: {},
  },
  plugins: [],
  prefix: 'tw-',
  corePlugins: {
    preflight: false, // TODO re-enable after switching from Bootstrap
  }
}
