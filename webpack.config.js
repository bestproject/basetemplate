const Encore = require('@symfony/webpack-encore');
const path = require('path');
const templateName = path.basename(__dirname);
const PostBuildPlugin = require('./.dev/js/build/PostBuildPlugin');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

let themeAssets = [
    './.dev/scss/index.scss',
    './.dev/js/theme.js',
    // './.dev/js/accessibility.js', # Uncomment if accessibility tools are used
]
if( Encore.isDev() ) {
    themeAssets = themeAssets.concat(['./.dev/scss/dev.scss']);
}

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

// Template front-end build configuration
Encore
    .setOutputPath('../../media/templates/site/'+templateName)
    .setPublicPath('/media/templates/site/'+templateName)
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSassLoader((options) => {
        options.sassOptions = {
            quietDeps: true, // disable warning msg
        }
    })
    .enableVersioning(Encore.isProduction())
    .enableSingleRuntimeChunk()
    .enableSourceMaps(!Encore.isProduction())
    .configureBabel((config) => {}, {
        includeNodeModules: ['swiper','dom7','ssr-window'],
        useBuiltIns: 'usage',
        corejs: 3
    })
    .configureTerserPlugin((options)=>{
        options.terserOptions = {
            output: {
                comments: false,
            },
            compress: {
                drop_console: true,
            }
        }
    })
    .autoProvidejQuery()
    .enablePostCssLoader()
    .addExternals({
        jquery: 'jQuery',
        joomla: 'Joomla'
    })
    .addEntry('theme',
        themeAssets
    )
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
    .addEntry('slider', [
        './.dev/js/slider.js'
    ])
    .addStyleEntry('editor',[
        './.dev/scss/editor.scss'
    ])
    .copyFiles([
        {from: './.dev/images', to: 'images/[name].[contenthash].[ext]'},
        {from: './.dev/fonts', to: 'fonts/[name].[contenthash].[ext]'},
    ])
    .addPlugin(new PostBuildPlugin)
    .configureFilenames({
        js: 'js/[name]-[contenthash].js',
        css: 'css/[name]-[contenthash].css',
        assets: '[name]-[contenthash].[ext]',
    })
;

const TemplateConfig = Encore.getWebpackConfig();
TemplateConfig.name = 'Template';

// Export configurations
module.exports = [TemplateConfig];