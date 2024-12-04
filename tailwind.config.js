module.exports = {
  purge: {
    enabled: true,
    content: ['./resources/templates/**/*.twig'],
  },
  darkMode: false, // or 'media' or 'class'
  theme: {
    fontFamily: {
      body: ['IBM Plex Mono']
    },
    extend: {},
  },
  variants: {
    extend: {},
  },
  plugins: [],
}
