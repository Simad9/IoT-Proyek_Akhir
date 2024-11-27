/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/**/*.{html,js}",
    "./src/**/*.{html,js}" 
  ],
  theme: {
    extend: {
      colors: {
        'putih': '#FFFFFF',
        'hitam': '#373737',
        'biru' : '#6D90D0'
      },
    },
    fontFamily: {
      digital: ['DS-Digital', "sans-serif"],
      sans: ["Poppins", "Inter", "sans-serif"],
    },
  },
  plugins: [],
};
