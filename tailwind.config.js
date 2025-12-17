/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",
    "./**/*.html",
    "./**/*.js",
    "./**/*.jsx",
    "./**/*.ts",
    "./**/*.tsx",

    //Site Editor
    "./templates/**/*.html",
    "./parts/**/*.html",
    "./patterns/**/*.php",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};
