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