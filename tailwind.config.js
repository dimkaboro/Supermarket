/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/**/*.html",   // все HTML файлы во всех папках внутри app
    "./app/**/*.php",    // все PHP файлы
    "./app/**/*.js",     // все JS файлы
    "./app/**/*.css"     // все CSS файлы
  ],
  theme: {
    extend: {},
  },
  plugins: [],
};