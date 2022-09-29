import jQuery from 'jquery';
import 'magnific-popup';
import 'magnific-popup/dist/magnific-popup.css';

const $ = jQuery;

$(()=>{

    // Setup lightbox for anchors
    jQuery(document).ready(function($){

        // Prepare gallery support
        const galleries = [];
        const $notIgnored = $('a:not([data-lightbox="no"])');

        $notIgnored.filter('a[data-gallery]').each((i,v)=>{
            galleries.push($(v).attr('data-gallery'));
        });

        $.each(galleries, (i,v)=>{
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

        // Single image lightbox
        $notIgnored.filter("a[href$='.jpg'],a[href$='.jpeg'],a[href$='.png'],a[href$='.gif']").magnificPopup({
            type:'image',
            image: {
                verticalFit: true
            },
        });

        // youtube lightbox
        $notIngored.filter("a[href*='youtube.']").magnificPopup({
            disableOn: 700,
            type: 'iframe',
            mainClass: 'mfp-fade',
            removalDelay: 160,
            preloader: false,
            fixedContentPos: false
        });

    });
});