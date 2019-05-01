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
                var $navbar = $(".navbar");
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