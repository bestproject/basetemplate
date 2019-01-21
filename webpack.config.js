var path = require('path');

var templateName = path.basename(__dirname);
var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('assets/build')
    .setPublicPath('/templates/'+templateName+'/assets/build')
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSassLoader()
    .enableVersioning(Encore.isProduction())
    .disableSingleRuntimeChunk()
    .addExternals({
        jquery: 'jQuery'
    })
    .addEntry('theme',[
        './.dev/sass/index.scss',
        './.dev/js/theme.js'
    ])
;

module.exports = Encore.getWebpackConfig();