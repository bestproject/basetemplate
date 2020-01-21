const Encore = require('@symfony/webpack-encore');
const path = require('path');

const templateName = path.basename(__dirname);

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

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
        './.dev/scss/index.scss',
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
    .copyFiles({
        from: './.dev/images',
        to: 'images/[path][name].[contenthash].[ext]'
    })
    .configureFilenames({
        css: '[name]-[contenthash].css',
        js: '[name]-[contenthash].js'
    });

const ThemeConfig = Encore.getWebpackConfig();

Encore.reset();
Encore
    .setOutputPath('css')
    .setPublicPath('/templates/'+templateName+'/css')
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .disableSingleRuntimeChunk()
    .enableSassLoader()
    .enableSourceMaps(!Encore.isProduction())
    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3,
    })
    .addStyleEntry('editor',[
        './.dev/scss/editor.scss'
    ])
    .configureFilenames({
        css: '[name].css',
    });

const EditorConfig = Encore.getWebpackConfig();

// Export configurations
module.exports = [ThemeConfig, EditorConfig];
