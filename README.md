# Joomla! base theme
A Joomla! template that can be used to create templates for Bootstrap 3. It contains a basic files structure as well as template overrides to match Boostrap 4 syntax.

## Requirements
* PHP 7
* Composer installed globaly
* Node

## Installation
* Just copy contents of this directory to a new template folder in /templates directory and run for example like this: `http://example.com/templates/yourtemplate/build.php`
* I youre creating template on a remote server (eg. hosting or vps) download the changes.
* Install all php requirements: `composer install`
* Install all required assets: `npm install`

## Usage
Template uses `Webpack Encore` to build assets. Most of the required things should be configured out of the box in template. If you need something more please refer the [documentation](https://symfony.com/doc/current/frontend.html).

### Development environment start
Webpack can build youre template assets on the fly after reach change in JS or SCSS files. Just run the command below:
* `npm run watch`

### Build template for production
In production build Webpack will compress all the required assets:
* `npm run prod`

### Build template in development
In development build Webpack will build all the required assets once and finish its job:
* `npm run dev`

## Module positions
* toolbar
* menu
* content-before
* left
* right
* content-after
* footer
* footer-menu
