module.exports = {
  content: ["./src/**/*.{html,js}"],
  darkMode: 'class',
  theme: {
    extend: {

      backgroundImage: {
        'toggle-btn-grad-on': 'linear-gradient(270deg, rgba(14,0,113,1) 0%, rgba(194,194,194,1) 100%)',
        'toggle-btn-grad-off': 'linear-gradient(90deg, rgba(14,0,113,1) 0%, rgba(194,194,194,1) 100%)',
      },

      colors:{
        'light-text' : '#f8f8f8',
        'dark-text' : '#1c1c1c',
      },

      fontFamily: {
        'Ubuntu': ['Ubuntu', 'sans-serif'], // Add custom font
      },

    },
  },
  plugins: [],
};
