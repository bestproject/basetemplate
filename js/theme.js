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
(function ($) {

	$.fn.Animated = function () {

		var $window = $(window);

		$window.on('scroll', revealOnScroll);

		function revealOnScroll() {
			var win_height_padded = $window.height() * 1.1;
			var scrolled = $window.scrollTop(),
					win_height_padded = $window.height() * 1.1;

			// Showed...
			$('[class*="animated-"]').each(function () {
				var $this = $(this);
				var offsetTop = $this.offset().top;

				if (scrolled + win_height_padded > offsetTop) {
					$this[0].className = $this[0].className.replace('animated-','animated ');
				}
			});

			// Hidden...
			$(".animated").each(function (index) {
				var $this = $(this);
				var offsetTop = $this.offset().top;
				if (scrolled + win_height_padded < offsetTop) {
					$this[0].className = $this[0].className.replace('animated ','animated-');
				}
			});
		}

		setTimeout(function(){
			revealOnScroll();
		}, 100);

		return this;
	};

}(jQuery));

/* 
 * Copyright (C) 2016 Artur Stępień
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

(function ( $ ) {
 
    /**
     * Adds a back to top button when page is scrolled
     */
    $.fn.backToTopButton = function(button_text){
        
        var $btn = $("<a href='#' id='back-to-top' class='btn btn-default'><i class='fa fa-angle-up'></i><span class='sr-only'>"+button_text+"</span></a>");
        $('body').append($btn);
        
        if ($('#back-to-top').length) {
            var scrollTrigger = 100, // px
                backToTop = function () {
                    var scrollTop = $(window).scrollTop();
                    if (scrollTop > scrollTrigger) {
                        $('#back-to-top').addClass('show');
                    } else {
                        $('#back-to-top').removeClass('show');
                    }
                };
            backToTop();
            $(window).on('scroll', function () {
                backToTop();
            });
            $('#back-to-top').on('click', function (e) {
                e.preventDefault();
                $('html,body').animate({
                    scrollTop: 0
                }, 700);
            });
        }
    };
 
}( jQuery ));
/* 
 * Copyright (C) 2016 Artur Stępień
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

(function ( $ ) {
 
    /**
    * Changes navbar position to fixed on scroll
    */
    $.fn.menuClassOnScroll = function(){
        
        this.scrollTest = function(){
            var scroll = $(window).scrollTop();
            var $nav = $(".navbar-container");
            if (scroll > 0) {
                var $navbar = $(".navbar.navbar-default");
                $nav.addClass("scrolled");
                $('body>header').css('padding-bottom',($navbar.outerHeight()+parseInt($navbar.css('margin-bottom')))+'px');
            } else {
                $nav.removeClass("scrolled");
                 $('body>header').css('padding-bottom','0px'); 
            }
        };

		$(window).scroll(this.scrollTest);
        
	};
 
}( jQuery ));
/* 
 * Copyright (C) 2016 Artur Stępień
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

(function ( $ ) {

    /**
     * Scroll to section if link starts with #
     */
    $.fn.scrollToSection = function(){
        var $elements = $('a[href*="#"]');
        
        for(var i=0, ic=$elements.length; i<ic; i++) {
            var $href = $($elements[i]).attr('href');
            if( $href.charAt(0)==='#' && $($elements[i]).attr('id')!=='back-to-top' ) {
                $($elements[i]).click(function(e){
                    e.preventDefault();
                    
                    $('html,body').animate({
                        scrollTop: $($(this).attr('href')).offset().top
                    }, 700); 
                });
            }
            
        }
        
    };
 
}( jQuery ));