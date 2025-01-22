module.exports = {
  content: ["./src/**/*.{html,js}"],
  darkMode: 'class',
  theme: {
    extend: {

      spacing: {
        'halfp': '0.6rem',
      },

      backgroundImage: {
        
      },
      boxShadow: {
        'dark-theme-shadow': '0px 0px 20px 1px #34508d;',
        'light-theme-shadow': '0px 0px 20px 1px #adadad;',
      },

      colors:{
        'theme-dark': '#0f172a',
        'theme-light': '#fafafa',

        'container-light' : '#eaeaea',
        'container-dark' : '#162036',

        'odd-line-dark' : '#22304f',
        'odd-line-light' : '#e1e1e1',

        'light-text' : '#f8f8f8',
        'dark-text' : '#1c1c1c',

        'toggle-btn-grad-on': '#121b2e',
        'toggle-btn-grad-off': '#bfbfbf',
      },

      fontFamily: {
        'Ubuntu': ['Ubuntu', 'sans-serif'], // Add custom font
      },

      filter: {
        'custom-calendar-dark': 'invert(19%) sepia(28%) saturate(537%) hue-rotate(193deg) brightness(85%) contrast(95%)',
      },

    },
  },
  plugins: [
    require('tailwindcss-filters'),
  ],
};
