module.exports = {
  content: ["./user/**/*.{html,js,php}"],
  darkMode: 'class',
  theme: {
    extend: {
      width:{
        'login-signup-box' : '22rem',
        'card-profile' : '10rem',
      },
      height:{
        'card-profile' : '10rem',
      },

      borderRadius: {
        '10px': '10px', // Custom radius with 24px
        'custom-full': '50%',  // Fully rounded
      },

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

        'important-red' : '#ff6363',
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
