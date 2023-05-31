
const fs = require('fs');
const path = require('path');

/**
 * Copy file.
 *
 * @param {string} from  A source file path relative to theme (e.g. '/node_modules/bootstrap/scss/_variables.scss')
 * @param {string}  to A target file path relative to theme (e.g. '/.dev/scss/_variables.scss')
 * @param {Array}  replaceStrings   Strings to replace.
 */
function copyTemplateFile(from, to, replaceStrings = []) {

    // If we can copy variables from Bootstrap, do it
    const theme_path = path.dirname(path.dirname(path.dirname(path.resolve(__dirname))));
    const src_path = theme_path + from;
    const dest_path = theme_path + to;
    const filename = path.basename(src_path);

    if (fs.existsSync(dest_path)) {
        console.info("\x1b[32m", 'The ['+filename+'] file already exists in this template.');
    } else if (fs.existsSync(src_path)) {
        fs.copyFile(src_path, dest_path, (err) => {
            if (err) {
                console.error("\x1b[31m", 'Unable to copy ['+filename+'] file.');
                throw err;
            } else {
                console.info("\x1b[32m", 'Copied ['+filename+'] file.')

                // Replace strings
                if( Object.keys(replaceStrings).length ) {

                    /**
                     * @type {string} buffer
                     */
                    let buffer = fs.readFileSync(dest_path).toString();
                    for( let replaceFrom in replaceStrings ) {
                        if (replaceStrings.hasOwnProperty(replaceFrom)) {
                            buffer = buffer.replace(new RegExp(replaceFrom, 'g'), replaceStrings[replaceFrom]);
                        }
                    }

                    fs.writeFileSync(dest_path, buffer);
                }
            }
        });


    } else {
        console.error("\x1b[31m", 'Unable to locate ['+filename+'] file in '+src_path);
    }

    // Reset console styling
    console.log("\x1b[0m");
}

// Copy variables
copyTemplateFile('/node_modules/bootstrap/scss/_variables.scss', '/.dev/scss/_variables.scss')
copyTemplateFile('/node_modules/bootstrap/scss/_variables-dark.scss', '/.dev/scss/_variables-dark.scss')

// Copy main boostrap file to allow disabling some components
copyTemplateFile('/node_modules/bootstrap/scss/bootstrap.scss', '/.dev/scss/_bootstrap.scss', {
    '@import "' : '@import "~bootstrap/scss/'
})

