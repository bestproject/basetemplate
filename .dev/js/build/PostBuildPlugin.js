const fs = require('fs');
const path = require('path');

/**
 * Plugin that fixes assets naming to match Joomla! 4 naming convention for template thumbnails and editor styling.
 */
class PostBuildPlugin {

    /**
     * @param {Compiler} compiler the compiler instance
     */
    apply(compiler) {

        compiler.hooks.afterEmit.tap('PostBuildPlugin', (process)=>{

            const template = path.basename(path.dirname(path.dirname(path.dirname(__dirname))));
            const root_path = path.resolve(__dirname+'/../../../../../');
            const assets_relative_path = 'media/templates/site/'+template;
            const assets_path = root_path+'/'+assets_relative_path;

            let buffer = fs.readFileSync(assets_path+'/manifest.json');
            let manifest = JSON.parse(buffer);

            // Template thumbnail rename
            if( manifest[assets_relative_path+'/images/template_preview.png'] ) {
                fs.rename(root_path+manifest[assets_relative_path+'/images/template_preview.png'], assets_path+'/images/template_preview.png', ()=>{});
                manifest[assets_relative_path+'/images/template_preview.png'] = '/'+assets_relative_path+'/images/template_preview.png';
            }

            // Template thumbnail rename
            if( manifest[assets_relative_path+'/images/template_thumbnail.png'] ) {
                fs.rename(root_path+manifest[assets_relative_path+'/images/template_thumbnail.png'], assets_path+'/images/template_thumbnail.png', ()=>{});
                manifest[assets_relative_path+'/images/template_thumbnail.png'] = '/'+assets_relative_path+'/images/template_thumbnail.png';
            }

            // Template editor.css rename
            if( manifest[assets_relative_path+'/editor.css'] ) {
                fs.rename(root_path+manifest[assets_relative_path+'/editor.css'], assets_path+'/css/editor.css', ()=>{});
                manifest[assets_relative_path+'/editor.css'] = '/'+assets_relative_path+'/css/editor.css';

                fs.copyFile(assets_path+'/css/editor.css', assets_path+'/css/editor.min.css', (err) => {
                    if (err) throw err;
                    console.log('editor.css was copied to editor.min.css');
                });

                manifest[assets_relative_path+'/editor.min.css'] = '/'+assets_relative_path+'/css/editor.min.css';
            }

            // Update manifest
            fs.writeFileSync(assets_path+'/manifest.json', JSON.stringify(manifest));

            return true;
        });

    }

}

module.exports = PostBuildPlugin