var path = require('path');

var templateName = path.basename(__dirname);

var Encore = require('@symfony/webpack-encore');

// Template front-end build configuration
Encore
    .setOutputPath('assets/build')
    .setPublicPath('/templates/'+templateName+'/assets/build')
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSassLoader()
    .enableVersioning(Encore.isProduction())
    .disableSingleRuntimeChunk()
    .enableSourceMaps(!Encore.isProduction())
    .configureBabel(function(babelConfig) {}, {
        include_node_modules: ['swiper','dom7','ssr-window']
    })
    .addExternals({
        jquery: 'jQuery'
    })
    .addEntry('theme',[
        './.dev/sass/index.scss',
        './.dev/js/theme.js'
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