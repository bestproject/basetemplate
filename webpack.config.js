var path = require('path');

var templateName = path.basename(__dirname);

var Encore = require('@symfony/webpack-encore');

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
    .disableSingleRuntimeChunk()
    .addExternals({
        jquery: 'jQuery'
    })
    .addEntry('theme',[
        './.dev/sass/index.scss',
        './.dev/js/theme.js'
    ]);
    
const themeConfig = Encore.getWebpackConfig();

// Template editor build configuration
Encore.reset();
Encore
    .setOutputPath('assets/build')
    .setPublicPath('/templates/'+templateName+'/assets/build')
    .enableBuildNotifications()
    .enableSassLoader()
    .disableSingleRuntimeChunk()
    .addEntry('editor',[
        './.dev/sass/editor.scss'
    ]);
    
const editorConfig = Encore.getWebpackConfig();

// Export configurations
module.exports = [themeConfig, editorConfig];
