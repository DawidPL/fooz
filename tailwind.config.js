/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    // Theme
    "./**/*.{php,html,js,jsx,ts,tsx}",
    "./templates/**/*.html",

    // Plugins 
    "../plugins/fooz-faq/**/*.{php,html,js,jsx,ts,tsx}",
    "../plugins/fooz-library/**/*.{php,html,js,jsx,ts,tsx}",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
