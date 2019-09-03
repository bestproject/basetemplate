# Joomla! base theme
A Joomla! template that can be used to create templates for Bootstrap 4. It contains a basic files structure as well as template overrides to match Boostrap 4 syntax.

## Requirements
* PHP 7
* Composer installed globally
* Node

## Installation
* Just copy contents of this directory to a new template folder in /templates directory.
* Install all php requirements: `composer install`
* Build template: `composer build`
* Install all required assets: `npm install`

## Usage
Template uses `Webpack Encore` to build assets. Most of the required things should be configured out of the box in template. If you need something more please refer the [documentation](https://symfony.com/doc/current/frontend.html).

### Development environment start
Webpack can build your template assets on the fly after each change in JS or SCSS files. Just run the command below:
* `npm run watch`

### Build template for production
In production build Webpack will compress all the required assets:
* `npm run prod`

### Build template in development
In development build Webpack will build all the required assets once and finish its job:
* `npm run dev`

### Notes
* Keep your article/text styling `./dev/sass/_typography.scss` file. This way the build system will automaticaly keep the front-end styling available also in your wysiwyg editor.
* If you need to change the `Intro`/`Full text` you can do it in `./dev/sass/editor.scss`.

## Libraries included by default
### Front-end 
* Font Awesome: 5.6.3
* Animate.css: 3.7.0
* Bootstrap: 4.1.3
* Magnific Popup 1.1.0
* Popper.js 1.14.6

### Back-end
* Webpack Encore 0.22.4
* Node Sass 4.11.0

## Module positions
* toolbar
* menu
* slider
* slider-after
* content-before
* left
* right
* content-after
* footer
* footer-menu
