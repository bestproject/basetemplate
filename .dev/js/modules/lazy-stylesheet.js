import 'jquery';

// Load lazy-loaded stylesheets
$(function(){
    $('link[rel="lazy-stylesheet"]').attr('rel','stylesheet');
});