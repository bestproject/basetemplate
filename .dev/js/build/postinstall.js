import fs from 'fs';

// If we can copy variables from Boostrap, do it
let src_path = 'node_modules/bootstrap/scss/_variables.scss';
let dest_path = 'node_modules/bootstrap/scss/_variables.scss';

if (fs.existsSync(dest_path)) {
    console.info("\x1b[32m", 'The _variables.scss file already exists in this template.');
} else if (fs.existsSync(src_path)) {
    fs.copyFile(src_path, dest_path, (err) => {
        if (err) {
            console.error("\x1b[31m", 'Unable to copy _variables file from Bootstrap package to template.');
            throw err;
        } else {
            console.info("\x1b[32m", 'Copy _variables.scss from Bootstrap package to template.')
        }
    });
} else {
    console.error("\x1b[31m", 'Unable to locate Bootstrap package in ./node_modules');
}

// Reset console styling
console.log("\x1b[0m");
