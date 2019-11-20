var Encore = require('@symfony/webpack-encore');
var path = require('path');
var templateName = path.basename(__dirname);

// Template front-end build configuration
Encore
    .setOutputPath('assets/build')
    .setPublicPath('/templates/'+templateName+'/assets/build')
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSassLoader()
    .enableVersioning(Encore.isProduction())
    .enableSingleRuntimeChunk()
    .enableSourceMaps(!Encore.isProduction())
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3,
        includeNodeModules: ['swiper','dom7','ssr-window']
    })
    .addExternals({
        jquery: 'jQuery',
        joomla: 'Joomla'
    })
    .addEntry('theme',[
        './.dev/sass/index.scss',
        './.dev/js/theme.js'
    ])
    .addEntry('animated', [
        './.dev/js/animated.js'
    ])
    .addEntry('backtotop', [
        './.dev/js/backtotop.js'
    ])
    .addEntry('classonscroll', [
        './.dev/js/classonscroll.js'
    ])
    .addEntry('lightbox', [
        './.dev/js/lightbox.js'
    ])
    .addStyleEntry('editor',[
        './.dev/sass/editor.scss'
    ])
    .configureFilenames({
        css: function(e) {
            return (e.chunk.id=='editor' ? '[name].css': '[name]-[hash:6].css');
        },
        js: '[name]-[hash:6].js'
    });

// Export configurations
module.exports = Encore.getWebpackConfig();