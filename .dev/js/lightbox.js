import jQuery from 'jquery';
import 'magnific-popup';
import 'magnific-popup/dist/magnific-popup.css';

let $ = jQuery;

$(function(){

    // Setup lightbox for anchors
    jQuery(document).ready(function($){

        var galleries = [];

        $('a[data-gallery]').each(function(i,v){
            galleries.push($(v).attr('data-gallery'));
        });

        $.each(galleries, function(i,v){
            $('a[data-gallery="'+v+'"]').magnificPopup({
                type:'image',
                gallery: {
                    enabled: true,
                },
                image: {
                    verticalFit: true
                },
            });
        });

        $("a[href$='.jpg']:not([data-gallery]),a[href$='.jpeg']:not([data-gallery]),a[href$='.png']:not([data-gallery]),a[href$='.gif']:not([data-gallery])").magnificPopup({
            type:'image',
            image: {
                verticalFit: true
            },
        });

    });
});