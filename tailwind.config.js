// /** @type {import('tailwindcss').Config} */
// module.exports = {
//   content: [
//     "./assets/**/*.js",
//     "./templates/**/*.html.twig",
//     "./node_modules/flowbite/**/*.js",
//     './node_modules/tw-elements/dist/js/**/*.js'
//   ],
//   theme: {
//     extend: {},
//   },
//   plugins: [
//     require('flowbite/plugin'),
//     require('@tailwindcss/forms'),
//     require('tw-elements/dist/plugin')
//   ],
//   darkMode: "class",
// }
/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './assets/**/*.js',
    './templates/**/*.html.twig',
    './node_modules/tw-elements/dist/js/**/*.js'
  ],
  theme: {
    extend: {
      spacing: {
        '60': '240px',
      },
    },
  },
  plugins: [
    require('tw-elements/dist/plugin'),
  ],
}

