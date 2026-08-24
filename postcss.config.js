// postcss.config.js
import autoprefixer from 'autoprefixer';

export default {
    plugins: [
        // include whatever plugins you want
        // but make sure you install these via npm!

        // add browserslist config to package.json (see below)
        autoprefixer(),
    ],
};