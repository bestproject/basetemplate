/*
	Name:			Animated
	Version:		1.0.0
	Description:	Script that uses animate.css on scroll
	Created on:		2016-02-29
	Author:			Artur Stępień  (artur.stepien@bestproject.pl)
	Author URI:		http://www.bestproject.pl
	Copyrights:		BestProject
	License:		GNU GPL 3.0 (See LICENSE file)
	Requires:		animated.css and jQuery
	How to use:		See example.html
*/
import 'animate.css/animate.css';
import $ from 'jquery';

/**
 * Run animate.css animations on element reveal.
 */
$.fn.Animated = function () {

    const $window = $(window);

    $window.on('scroll', revealOnScroll);

    function revealOnScroll() {
        const scrolled = $window.scrollTop(),
            win_height_padded = $window.height() * 1.1;

        // Showed...
        $('[class*="animated-"]').each(function () {
            const $this = $(this);
            const offsetTop = $this.offset().top;

            if (scrolled + win_height_padded > offsetTop) {
                $this[0].className = $this[0].className.replace('animated-', 'animated ');
            }
        });

        // Hidden...
        $(".animated").each(function (index) {
            const $this = $(this);
            const offsetTop = $this.offset().top;
            if (scrolled + win_height_padded < offsetTop) {
                $this[0].className = $this[0].className.replace('animated ', 'animated-');
            }
        });
    }

    setTimeout(()=>{
        revealOnScroll();
    }, 100);

    return this;
};

// Run animated
$(()=>{
    $(document).Animated();
});