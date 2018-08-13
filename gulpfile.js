'use strict';

var gulp = require('gulp');
var uglify = require('gulp-uglify');
var uglifycss = require('gulp-uglifycss');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var watch = require('gulp-watch');
var sass = require('gulp-sass');
var clean = require('gulp-clean');
var merge = require('merge-stream');
var parallel = gulp.parallel;
var series = gulp.series;

/**
 * Theme assets building.
 * 
 * @type Array
 */
var theme = {
    
    /**
     * Clean theme output (css and js).
     * 
     * @return {unresolved}
     */
    clean: function(){
        return gulp.src([
            'css/**/*',
            'js/**/*'
        ])
        .pipe(clean());
    },
    
    /**
     * Build theme JS.
     * 
     * @return {unresolved}
     */
    js: function(){

        return gulp.src([
            '.dev/js/**/*.js'
        ])
        .pipe(concat('theme.js'))
        .pipe(gulp.dest('js'))
        .pipe(uglify({
            output: {
                comments: /^!/
            }
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('js'));

    },
    
    /**
     * Build theme CSS from SASS files.
     * 
     * @return {unresolved}
     */
    sass: function () {
        return gulp.src([
            '.dev/sass/**/*.scss',
            '.dev/sass/**/*.sass',
        ])
        .pipe(sass({
                outputStyle: 'expanded',
                loadPaths: ['./node_modules']
            }).on('error', sass.logError))
        .pipe(gulp.dest('css'))
        .pipe(sass({
                outputStyle: 'compressed',
                loadPaths: ['./node_modules']
            }).on('error', sass.logError))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('css'));
    },
    
    /**
     * Watch for SASS changes and build CSS when chagnes are made.
     * 
     * @return {undefined}
     */
    watchSass: function(){
        return gulp.watch([
            '.dev/sass/**/*.sass',
            '.dev/sass/**/*.scss'
        ], series('theme:sass'));
    },
    
    /**
     * Watch fo JS chagnes and build JS when changes are made.
     * 
     * @return {undefined}
     */
    watchJs: function(){
        return gulp.watch([
            '.dev/js/**/*.js'
        ], series('theme:js'));
    },
}

/**
 * Vendor assets building.
 * 
 * @type Array
 */
var vendor = {
    
    /**
     * Clean vendor assets directories.
     * 
     * @return {unresolved}
     */
    clean: function(){
        return gulp.src([
            'assets/**/*'
        ])
        .pipe(clean());
    },
    
    /**
     * Copy vendor modules into assets.
     * 
     * @return {unresolved}
     */
    modules: function(){
    
        // Example module to copy into assets
    //    var sampleVendorLibrary = gulp.src([
    //        'node_modules/sampleVendorLibrary/dist/**/*',
    //        'node_modules/sampleVendorLibrary/LICENSE',
    //        'node_modules/sampleVendorLibrary/README.md',
    //    ])
    //    .pipe(gulp.dest('assets/sampleVendorLibrary'));

        console.log('- No libraries to cop as for now. Add them in gulpfile.js');

        return merge(
    //        sampleVendorLibrary,
        );
    },
    
    /**
     * Build vendor JS/CSS into a single bundle file.
     * 
     * @return {unresolved}
     */
    bundle: function(){

        var vendorPackage = gulp.src([
            'node_modules/bootstrap/dist/js/bootstrap.bundle.js'
        ])
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('js'))
        .pipe(uglify({
            output: {comments: /^!/}
        }))
        .pipe(rename({
            suffix: '.min'
        }))
        .pipe(gulp.dest('js'));

        return merge(
            vendorPackage,
        );
    }
}

// Theme javascript
gulp.task('theme:js', theme.js);

// Vendor libraries
gulp.task('vendor:clean', vendor.clean);
gulp.task('vendor:bundle', vendor.bundle);
gulp.task('vendor:modules', vendor.modules);
gulp.task('vendor', parallel('vendor:clean', 'vendor:bundle', 'vendor:modules'));

// Clean theme
gulp.task('theme:clean', theme.clean);

// Theme SASS
gulp.task('theme:sass', theme.sass);
 
// Watch SASS files
gulp.task('sass:watch', theme.watchSass);

// Watch JavaScript files
gulp.task('js:watch', theme.watchJs);

// Watch everything
gulp.task('watch', parallel('sass:watch','js:watch'));

// Run default tasks
gulp.task('default', parallel('theme:js','theme:sass','vendor'));