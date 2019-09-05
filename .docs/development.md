# Template development
Template uses `Webpack Encore` to build assets. Most of the required things should be configured out of the box in template. If you need something more please refer the [documentation](https://symfony.com/doc/current/frontend.html).

### Development environment start
Webpack can build your template assets on the fly after each change in JS or SCSS files. That way you can preview your changes as fast as possible. Just run the command below:
* `npm run watch`
To stop the server just hit CTRL+C

### Build template for production
Before you start using your template in production environment you need to build its assets for production. In production build Webpack will compress all the required assets and remove unused javascript modules. That way you get the smallest possible files. 
To run production build run the following command:
* `npm run prod`

### Build template in development
If you only made a single change and you only need to build dev assets once run the following command. Webpack will build assets and stop.
* `npm run dev`

### Tips
* If you need to change the `Intro`/`Full text` you can do it in `./dev/sass/editor.scss`.
