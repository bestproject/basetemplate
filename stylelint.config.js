/** @type {import('stylelint').Config} */
module.exports = {
  extends: [
    "stylelint-config-sass-guidelines",
    "stylelint-scss",
  ],
  rules: {
    "block-no-empty": true
  },
  overrides: [
    {
      files: ".dev/**/*.scss"
    }
  ]
};